<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'phone',
        'email',
        'status',
        'assigned_to',
        'last_update_at',
    ];

    protected function casts(): array
    {
        return [
            'last_update_at' => 'datetime',
        ];
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ClientUpdate::class);
    }

    public function isLate(): bool
    {
        $gap = Setting::get('update_gap_days');
        if (!$gap || !$this->last_update_at) {
            return false;
        }
        return $this->last_update_at->diffInDays(now()) > (int) $gap;
    }

    public function getIsLateAttribute(): bool
    {
        return $this->isLate();
    }

    /**
     * Get the formatted international phone number.
     * Removes leading zero and prepends +966 (Saudi Arabia)
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->phone) {
            return null;
        }

        // Remove leading zero and prepend +966
        $phoneWithoutZero = ltrim($this->phone, '0');
        return '+966' . $phoneWithoutZero;
    }

    /**
     * Get the WhatsApp link for this client.
     */
    public function getWhatsappLinkAttribute(): ?string
    {
        if (!$this->phone) {
            return null;
        }

        // Remove leading zero and prepend 966 (no + for WhatsApp links)
        $phoneWithoutZero = ltrim($this->phone, '0');
        return 'https://wa.me/966' . $phoneWithoutZero;
    }

    /**
     * Get the logo URL or a default avatar.
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }

        // Default avatar with first letter of name
        $initial = mb_substr($this->name, 0, 1);
        return 'https://ui-avatars.com/api/?name=' . urlencode($initial) . '&color=1e3a8a&background=dbeafe&bold=true&size=128';
    }

    protected static function booted(): void
    {
        static::created(function (Client $client) {
            $chat = $client->chat()->create();

            $userName = auth()->check() ? auth()->user()->name : 'System';

            $chat->messages()->create([
                'sender_id' => auth()->id(),
                'body' => "تم إضافة العميل  \"{$client->name}\" بواسطة {$userName}.",
                'is_system_log' => true,
                'is_read' => true,
            ]);
        });
    }
}

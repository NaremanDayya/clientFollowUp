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

    protected static function booted(): void
    {
        static::created(function (Client $client) {
            $chat = $client->chat()->create();

            $userName = auth()->check() ? auth()->user()->name : 'System';

            $chat->messages()->create([
                'sender_id' => auth()->id(),
                'body' => "New client \"{$client->name}\" added by {$userName}.",
                'is_system_log' => true,
                'is_read' => true,
            ]);
        });
    }
}

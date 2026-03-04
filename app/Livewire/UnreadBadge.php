<?php

namespace App\Livewire;

use App\Models\Chat;
use Livewire\Component;

class UnreadBadge extends Component
{
    public string $location = 'sidebar';

    public function getUnreadCountProperty(): int
    {
        $query = Chat::whereHas('messages', function ($q) {
            $q->where('is_read', false)
              ->where('is_system_log', false);
        });

        // Filter by user role
        if (auth()->user()->isFollower()) {
            $query->whereHas('client', function ($q) {
                $q->where('assigned_to', auth()->id());
            });
        }

        return $query->count();
    }

    public function render()
    {
        return view('livewire.unread-badge');
    }
}

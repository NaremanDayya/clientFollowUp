<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;

class UnreadBadge extends Component
{
    public string $location = 'sidebar';

    public function getUnreadCountProperty(): int
    {
        return Message::where('is_read', false)
            ->where('is_system_log', false)
            ->whereHas('chat.client', function ($q) {
                if (auth()->user()->isFollower()) {
                    $q->where('assigned_to', auth()->id());
                }
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.unread-badge');
    }
}

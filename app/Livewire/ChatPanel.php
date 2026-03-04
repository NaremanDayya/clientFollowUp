<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Message;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('المحادثات')]
class ChatPanel extends Component
{
    public ?Chat $chat = null;
    public string $messageBody = '';
    public string $chatSearch = '';

    public function mount(?Chat $chat = null): void
    {
        if ($chat && $chat->exists) {
            // Verify access
            if (auth()->user()->isFollower() && $chat->client->assigned_to !== auth()->id()) {
                abort(403);
            }
            $this->chat = $chat;
            $this->markAsRead();
        }
    }

    public function selectChat(int $chatId): void
    {
        $chat = Chat::findOrFail($chatId);

        if (auth()->user()->isFollower() && $chat->client->assigned_to !== auth()->id()) {
            return;
        }

        $this->chat = $chat;
        $this->markAsRead();
    }

    public function markAsRead(): void
    {
        if ($this->chat) {
            $this->chat->messages()
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
    }

    public function sendMessage(): void
    {
        if (!$this->chat || trim($this->messageBody) === '') {
            return;
        }

        $this->chat->messages()->create([
            'sender_id' => auth()->id(),
            'body' => trim($this->messageBody),
            'is_system_log' => false,
            'is_read' => true,
        ]);

        $this->messageBody = '';
        $this->dispatch('message-sent');
    }

    public function render()
    {
        $query = Chat::with(['client.assignedUser', 'latestMessage'])
            ->withCount(['unreadMessages']);

        if (auth()->user()->isFollower()) {
            $query->whereHas('client', fn($q) => $q->where('assigned_to', auth()->id()));
        }

        if ($this->chatSearch) {
            $query->whereHas('client', fn($q) => $q->where('name', 'like', "%{$this->chatSearch}%"));
        }

        $chats = $query->get()->sortByDesc(function ($chat) {
            return [
                $chat->unread_messages_count > 0 ? 1 : 0,
                $chat->updated_at->timestamp
            ];
        })->values();

        $messages = $this->chat
            ? $this->chat->messages()->with('sender')->oldest()->get()
            : collect();

        return view('livewire.chat-panel', compact('chats', 'messages'));
    }
}

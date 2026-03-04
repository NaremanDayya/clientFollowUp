<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\ClientUpdate;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('ملف العميل')]
class ClientProfile extends Component
{
    use WithFileUploads;
    public Client $client;

    // Edit fields
    public bool $editing = false;
    public string $editName = '';
    public $editLogo = null;
    public string $editPhone = '';
    public string $editEmail = '';
    public string $editStatus = '';
    public ?int $editAssignedTo = null;

    // Update fields
    public string $updateTitle = '';
    public string $updateNotes = '';

    public function mount(Client $client): void
    {
        // Follower can only see their own clients
        if (auth()->user()->isFollower() && $client->assigned_to !== auth()->id()) {
            abort(403);
        }
        $this->client = $client;
        $this->fillEditFields();
    }

    public function fillEditFields(): void
    {
        $this->editName = $this->client->name;
        $this->editPhone = $this->client->phone ?? '';
        $this->editEmail = $this->client->email ?? '';
        $this->editStatus = $this->client->status;
        $this->editAssignedTo = $this->client->assigned_to;
    }

    public function saveClient(): void
    {
        $validated = $this->validate([
            'editName' => 'required|string|max:255',
            'editLogo' => 'nullable|image|max:2048',
            'editPhone' => ['required', 'digits:10'],
            'editEmail' => 'nullable|email|max:255',
            'editStatus' => 'required|in:new,active,inactive,completed',
            'editAssignedTo' => 'nullable|exists:users,id',
        ], [
            'editPhone.digits' => 'يجب أن يتكون رقم الجوال من 10 أرقام',
        ]);

        $data = [
            'name' => $this->editName,
            'phone' => $this->editPhone,
            'email' => $this->editEmail,
            'status' => $this->editStatus,
            'assigned_to' => $this->editAssignedTo,
        ];

        // Upload new logo if provided
        if ($this->editLogo) {
            $logoPath = $this->editLogo->store('client-logos', 'public');
            $data['logo'] = $logoPath;
        }

        $data['last_update_at'] = now();
        $this->client->update($data);

        // Log system message to chat
        if ($this->client->chat) {
            $this->client->chat->messages()->create([
                'sender_id' => auth()->id(),
                'body' => 'تم تحديث بيانات العميل بواسطة ' . auth()->user()->name . '.',
                'is_system_log' => true,
                'is_read' => false,
            ]);
        }

        $this->editing = false;
        $this->client->refresh();
        $this->dispatch('client-updated');
    }

    public function addUpdate(): void
    {
        $this->validate([
            'updateTitle' => 'required|string|max:255',
            'updateNotes' => 'nullable|string|max:2000',
        ]);

        ClientUpdate::create([
            'client_id' => $this->client->id,
            'user_id' => auth()->id(),
            'title' => $this->updateTitle,
            'notes' => $this->updateNotes,
        ]);

        $this->client->update(['last_update_at' => now()]);

        // Post system message to chat thread
        if ($this->client->chat) {
            $messageBody = "تحديث: \"{$this->updateTitle}\"";
            if ($this->updateNotes) {
                $messageBody .= "\nالتفاصيل: {$this->updateNotes}";
            }
            $messageBody .= "\n— بواسطة " . auth()->user()->name;
            
            $this->client->chat->messages()->create([
                'sender_id' => auth()->id(),
                'body' => $messageBody,
                'is_system_log' => true,
                'is_read' => false,
            ]);
        }

        $this->reset(['updateTitle', 'updateNotes']);
        $this->client->refresh();
    }

    public function render()
    {
        $updates = $this->client->updates()->with('user')->latest()->get();
        $employees = User::where('role', 'follower')->get();

        return view('livewire.client-profile', compact('updates', 'employees'));
    }
}

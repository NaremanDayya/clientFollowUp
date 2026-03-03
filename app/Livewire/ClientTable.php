<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('العملاء')]
class ClientTable extends Component
{
    use WithPagination, WithFileUploads;

    #[Url]
    public string $search = '';
    #[Url]
    public string $statusFilter = '';
    #[Url]
    public string $employeeFilter = '';

    // Create client modal
    public bool $showCreateModal = false;
    public string $newName = '';
    public $newLogo = null;
    public string $newPhone = '';
    public string $newEmail = '';
    public string $newStatus = 'new';
    public ?int $newAssignedTo = null;

    // Update modal
    public bool $showUpdateModal = false;
    public ?int $selectedClientId = null;
    public string $updateTitle = '';
    public string $updateNotes = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingEmployeeFilter(): void
    {
        $this->resetPage();
    }

    public function createClient(): void
    {
        $this->validate([
            'newName' => 'required|string|max:255',
            'newLogo' => 'required|image|max:2048',
            'newPhone' => ['required', 'digits:10'],
            'newEmail' => 'nullable|email|max:255',
            'newStatus' => 'required|in:new,active,inactive,completed',
            'newAssignedTo' => 'nullable|exists:users,id',
        ], [
            'newPhone.digits' => 'يجب أن يتكون رقم الجوال من 10 أرقام',
            'newLogo.required' => 'شعار العميل مطلوب',
            'newLogo.image' => 'يجب أن يكون الشعار صورة',
            'newLogo.max' => 'حجم الشعار يجب أن لا يتجاوز 2 ميجابايت',
        ]);

        // Upload logo
        $logoPath = $this->newLogo->store('client-logos', 'public');

        Client::create([
            'name' => $this->newName,
            'logo' => $logoPath,
            'phone' => $this->newPhone,
            'email' => $this->newEmail,
            'status' => $this->newStatus,
            'assigned_to' => $this->newAssignedTo,
            'last_update_at' => now(),
        ]);

        $this->reset(['newName', 'newLogo', 'newPhone', 'newEmail', 'newStatus', 'newAssignedTo', 'showCreateModal']);
        $this->dispatch('client-created');
    }

    public function openUpdateModal(int $clientId): void
    {
        $this->selectedClientId = $clientId;
        $this->showUpdateModal = true;
    }

    public function addUpdate(): void
    {
        $this->validate([
            'updateTitle' => 'required|string|max:255',
            'updateNotes' => 'nullable|string',
        ], [
            'updateTitle.required' => 'عنوان التحديث مطلوب',
        ]);

        $client = Client::findOrFail($this->selectedClientId);

        \App\Models\ClientUpdate::create([
            'client_id' => $client->id,
            'user_id' => auth()->id(),
            'title' => $this->updateTitle,
            'notes' => $this->updateNotes,
        ]);

        $client->update(['last_update_at' => now()]);

        // Post system message to chat thread
        if ($client->chat) {
            $messageBody = "تحديث: \"{$this->updateTitle}\"";
            if ($this->updateNotes) {
                $messageBody .= "\nالتفاصيل: {$this->updateNotes}";
            }
            $messageBody .= "\n— بواسطة " . auth()->user()->name;
            
            $client->chat->messages()->create([
                'sender_id' => auth()->id(),
                'body' => $messageBody,
                'is_system_log' => true,
                'is_read' => false,
            ]);
        }

        $this->reset(['updateTitle', 'updateNotes', 'showUpdateModal', 'selectedClientId']);
        $this->dispatch('update-added');
    }

    public function render()
    {
        $query = Client::with('assignedUser');

        if (auth()->user()->isFollower()) {
            $query->where('assigned_to', auth()->id());
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->employeeFilter) {
            $query->where('assigned_to', $this->employeeFilter);
        }

        $clients = $query->latest()->paginate(15);
        $employees = User::where('role', 'follower')->get();

        return view('livewire.client-table', compact('clients', 'employees'));
    }
}

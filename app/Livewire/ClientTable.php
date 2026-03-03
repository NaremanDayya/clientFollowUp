<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('العملاء')]
class ClientTable extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';
    #[Url]
    public string $statusFilter = '';
    #[Url]
    public string $employeeFilter = '';

    // Create client modal
    public bool $showCreateModal = false;
    public string $newName = '';
    public string $newPhone = '';
    public string $newEmail = '';
    public string $newStatus = 'new';
    public ?int $newAssignedTo = null;

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
            'newPhone' => 'nullable|string|max:20',
            'newEmail' => 'nullable|email|max:255',
            'newStatus' => 'required|in:new,active,inactive,completed',
            'newAssignedTo' => 'nullable|exists:users,id',
        ]);

        Client::create([
            'name' => $this->newName,
            'phone' => $this->newPhone,
            'email' => $this->newEmail,
            'status' => $this->newStatus,
            'assigned_to' => $this->newAssignedTo,
            'last_update_at' => now(),
        ]);

        $this->reset(['newName', 'newPhone', 'newEmail', 'newStatus', 'newAssignedTo', 'showCreateModal']);
        $this->dispatch('client-created');
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

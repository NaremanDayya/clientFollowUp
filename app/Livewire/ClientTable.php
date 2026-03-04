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

    // Update modal
    public bool $showUpdateModal = false;
    public ?int $selectedClientId = null;
    public string $updateTitle = '';
    public string $updateNotes = '';

    protected function rules()
    {
        return [
            'newLogo' => 'nullable|image|max:5120',
        ];
    }

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
        // Validate all fields except logo first
        $this->validate([
            'newName' => 'required|string|max:255',
            'newPhone' => ['required', 'digits:10','unique:clients,phone'],
            'newEmail' => 'nullable|email|max:255',
            'newStatus' => 'required|in:new,active,inactive,completed',
        ], [
            'newPhone.digits' => 'يجب أن يتكون رقم الجوال من 10 أرقام',
            'newPhone.unique' => 'رقم الجوال موجود بالفعل',
        ]);

        // Validate logo separately if provided
        if ($this->newLogo) {
            $this->validate([
                'newLogo' => 'image|max:5120',
            ], [
                'newLogo.image' => 'يجب أن يكون الشعار صورة',
                'newLogo.max' => 'حجم الشعار يجب أن لا يتجاوز 2 ميجابايت',
            ]);
        }

        $data = [
            'name' => $this->newName,
            'phone' => $this->newPhone,
            'email' => $this->newEmail,
            'status' => $this->newStatus,
            'assigned_to' => auth()->id(),
            'last_update_at' => now(),
        ];

        // Upload logo if provided
        if ($this->newLogo && is_object($this->newLogo)) {
            try {
                \Log::info('Attempting logo upload', [
                    'filename' => $this->newLogo->getClientOriginalName(),
                    'size' => $this->newLogo->getSize(),
                    'mime' => $this->newLogo->getMimeType(),
                    'temp_path' => $this->newLogo->getRealPath(),
                ]);
                
                $data['logo'] = $this->newLogo->store('client-logos', 'public');
                
                \Log::info('Logo uploaded successfully', ['path' => $data['logo']]);
            } catch (\Exception $e) {
                \Log::error('Logo upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                session()->flash('error', 'فشل رفع الشعار: ' . $e->getMessage());
            }
        } else {
            \Log::warning('No logo provided or invalid object', [
                'newLogo_value' => $this->newLogo,
                'is_object' => is_object($this->newLogo),
            ]);
        }

        Client::create($data);

        $this->reset(['newName', 'newLogo', 'newPhone', 'newEmail', 'newStatus', 'showCreateModal']);
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

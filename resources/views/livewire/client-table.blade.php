<div>
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Clients</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and follow up on your client records.</p>
        </div>
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2.5 bg-[#1e3a8a] text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Client
        </button>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name, phone, email..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] transition-shadow">
            </div>
            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="w-full py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="completed">Completed</option>
            </select>
            {{-- Employee Filter (Admin only) --}}
            @if(auth()->user()->isAdmin())
            <select wire:model.live="employeeFilter" class="w-full py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                <option value="">All Employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Update</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                    <tr class="{{ $client->is_late ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }} transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white text-sm {{ $client->is_late ? 'bg-red-500' : 'bg-[#1e3a8a]' }}">
                                    {{ strtoupper(substr($client->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $client->name }}</p>
                                    @if($client->is_late)
                                        <span class="text-xs text-red-600 font-medium">Late - needs follow-up</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $client->phone ?? '-' }}</p>
                            <p class="text-gray-400 text-xs">{{ $client->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'new' => 'bg-blue-100 text-blue-700',
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-gray-100 text-gray-600',
                                    'completed' => 'bg-purple-100 text-purple-700',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $colors[$client->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($client->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $client->assignedUser->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $client->last_update_at ? $client->last_update_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('clients.show', $client) }}"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:text-[#1e3a8a] hover:bg-blue-50 transition-colors"
                               title="View Profile">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-gray-500 font-medium">No clients found.</p>
                            <p class="text-gray-400 text-sm">Try adjusting your search or filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $clients->links() }}
        </div>
    </div>

    {{-- Create Client Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" x-on:remove="document.body.classList.remove('overflow-hidden')">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('showCreateModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 ring-1 ring-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Client</h3>
                </div>
                <form wire:submit="createClient" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input wire:model="newName" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="Client name">
                        @error('newName') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input wire:model="newPhone" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="05xxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input wire:model="newEmail" type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="email@example.com">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model="newStatus" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                <option value="new">New</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                            <select wire:model="newAssignedTo" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                <option value="">Unassigned</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$set('showCreateModal', false)"
                                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2.5 text-sm font-medium text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-800 shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
                            Create Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

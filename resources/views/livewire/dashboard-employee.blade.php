<div>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- My Clients --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">My Clients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $myClients }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Pending Updates (Late) --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Updates</p>
                    <p class="text-3xl font-bold {{ $pendingUpdates > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">{{ $pendingUpdates }}</p>
                </div>
                <div class="w-12 h-12 {{ $pendingUpdates > 0 ? 'bg-red-100' : 'bg-green-100' }} rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $pendingUpdates > 0 ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Unread Messages --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Unread Messages</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $recentMessages }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Updates --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">My Recent Updates</h3>
        <div class="space-y-3">
            @foreach($myRecentUpdates as $update)
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-[#1e3a8a] rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $update->title }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $update->client->name ?? 'N/A' }} &middot; {{ $update->created_at->diffForHumans() }}
                    </p>
                    @if($update->notes)
                        <p class="text-xs text-gray-400 mt-1">{{ Str::limit($update->notes, 80) }}</p>
                    @endif
                </div>
            </div>
            @endforeach

            @if($myRecentUpdates->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">No updates yet. Start following up with your clients!</p>
            @endif
        </div>
    </div>
</div>

<div>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Clients --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">إجمالي العملاء</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalClients }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Late Clients --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">العملاء المتأخرون</p>
                    <p class="text-3xl font-bold {{ $lateClients > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">{{ $lateClients }}</p>
                </div>
                <div class="w-12 h-12 {{ $lateClients > 0 ? 'bg-red-100' : 'bg-green-100' }} rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $lateClients > 0 ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Total Employees --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">الموظفون</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalEmployees }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Calendar Section --}}
    <div class="mb-8">
        @livewire('dashboard-calendar')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Employee Activity --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">نشاط الموظفين</h3>
            <div class="space-y-4">
                @foreach($employeeStats as $emp)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <img class="w-10 h-10 rounded-full ring-2 ring-gray-200 object-cover" src="{{ $emp->avatar_url }}" alt="">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $emp->name }}</p>
                            <p class="text-xs text-gray-500">{{ $emp->clients_count }} عميل</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-[#1e3a8a]">
                            {{ $emp->client_updates_count }} تحديث
                        </span>
                    </div>
                </div>
                @endforeach

                @if($employeeStats->isEmpty())
                    <p class="text-sm text-gray-500 text-center py-4">لا يوجد موظفون بعد.</p>
                @endif
            </div>
        </div>

        {{-- Recent Updates --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">آخر التحديثات</h3>
            <div class="space-y-3">
                @foreach($recentUpdates as $update)
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-[#1e3a8a] rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-white">{{ strtoupper(substr($update->user->name ?? 'S', 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $update->title }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $update->user->name ?? 'System' }} &middot; {{ $update->client->name ?? 'N/A' }} &middot; {{ $update->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endforeach

                @if($recentUpdates->isEmpty())
                    <p class="text-sm text-gray-500 text-center py-4">لا توجد تحديثات بعد.</p>
                @endif
            </div>
        </div>
    </div>
</div>

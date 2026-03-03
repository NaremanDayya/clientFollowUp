<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
    <div class="flex items-center justify-between h-16 px-6">
        {{-- Mobile menu button --}}
        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        {{-- Page Title --}}
        <div class="hidden lg:flex items-center">
            <h1 class="text-lg font-semibold text-gray-800">
                @php
                    $titles = [
                        'dashboard' => 'لوحة التحكم',
                        'clients.index' => 'العملاء',
                        'clients.show' => 'ملف العميل',
                        'chats.index' => 'المحادثات',
                        'chats.show' => 'المحادثات',
                        'settings' => 'الإعدادات',
                        'profile.edit' => 'الملف الشخصي',
                    ];
                    $currentRoute = request()->route()?->getName();
                @endphp
                {{ $titles[$currentRoute] ?? 'نظام إدارة العملاء' }}
            </h1>
        </div>

        {{-- Right Side --}}
        <div class="flex items-center gap-4">
            {{-- Unread Messages Counter --}}
            <a href="{{ route('chats.index') }}" class="relative p-2 text-gray-500 hover:text-[#1e3a8a] transition-colors rounded-lg hover:bg-blue-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                @livewire('unread-badge', ['location' => 'header'])
            </a>

            {{-- Profile Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-[#1e3a8a] transition-colors">
                    <img class="w-8 h-8 rounded-full ring-2 ring-gray-200 object-cover" src="{{ auth()->user()->avatar_url }}" alt="">
                    <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl ring-1 ring-black/5 py-1 z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الملف الشخصي</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-right block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">تسجيل الخروج</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

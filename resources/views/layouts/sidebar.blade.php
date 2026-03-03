<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-[#1e3a8a] text-white transform transition-transform duration-300 ease-in-out"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    {{-- Logo --}}
    <div class="flex items-center justify-between h-16 px-6 border-b border-blue-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span class="text-lg font-bold tracking-wide">CRM System</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-blue-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="mt-6 px-4 space-y-1">
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150
                  {{ request()->routeIs('dashboard') ? 'bg-blue-700/60 text-white shadow-lg ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-blue-700/40' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
            Dashboard
        </a>

        <a href="{{ route('clients.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150
                  {{ request()->routeIs('clients.*') ? 'bg-blue-700/60 text-white shadow-lg ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-blue-700/40' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Clients
        </a>

        <a href="{{ route('chats.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150
                  {{ request()->routeIs('chats.*') ? 'bg-blue-700/60 text-white shadow-lg ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-blue-700/40' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Chats
            @livewire('unread-badge')
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('settings') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150
                  {{ request()->routeIs('settings') ? 'bg-blue-700/60 text-white shadow-lg ring-1 ring-blue-400/30' : 'text-blue-100 hover:bg-blue-700/40' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Settings
        </a>
        @endif
    </nav>

    {{-- User Info at Bottom --}}
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-700">
        <div class="flex items-center space-x-3">
            <img class="w-10 h-10 rounded-full ring-2 ring-blue-400 object-cover" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-blue-300 capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-300 hover:text-white transition-colors" title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

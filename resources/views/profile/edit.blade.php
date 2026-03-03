<x-app-layout>
    <div class="max-w-5xl mx-auto">
        {{-- Profile Header --}}
        <div class="bg-gradient-to-l from-[#1e3a8a] to-[#1e40af] rounded-2xl shadow-xl p-8 mb-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-32 h-32 bg-white/5 rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-40 h-40 bg-white/5 rounded-full translate-x-20 translate-y-20"></div>
            
            <div class="relative flex items-center gap-6">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" 
                     class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white/20 shadow-xl">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-white mb-2">{{ auth()->user()->name }}</h1>
                    <p class="text-blue-100 text-sm mb-3">{{ auth()->user()->email }}</p>
                    <div class="inline-flex items-center px-3 py-1.5 bg-white/10 backdrop-blur-sm rounded-lg text-white text-sm font-medium">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ auth()->user()->role === 'admin' ? 'مدير النظام' : 'موظف' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 mb-6" x-data="{ activeTab: 'info' }">
            <div class="border-b border-gray-200">
                <nav class="flex gap-2 px-6" aria-label="Tabs">
                    <button @click="activeTab = 'info'" 
                            :class="activeTab === 'info' ? 'border-[#1e3a8a] text-[#1e3a8a]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            المعلومات الشخصية
                        </div>
                    </button>
                    <button @click="activeTab = 'security'" 
                            :class="activeTab === 'security' ? 'border-[#1e3a8a] text-[#1e3a8a]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            الأمان
                        </div>
                    </button>
                    <button @click="activeTab = 'danger'" 
                            :class="activeTab === 'danger' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="py-4 px-4 border-b-2 font-medium text-sm transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            منطقة الخطر
                        </div>
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="p-6">
                {{-- Personal Information Tab --}}
                <div x-show="activeTab === 'info'" x-transition>
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Security Tab --}}
                <div x-show="activeTab === 'security'" x-transition x-cloak>
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Danger Zone Tab --}}
                <div x-show="activeTab === 'danger'" x-transition x-cloak>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

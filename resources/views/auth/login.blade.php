<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">مرحباً بعودتك</h2>
        <p class="text-sm text-gray-500">سجّل دخولك للوصول إلى لوحة التحكم وإدارة عملائك</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- البريد الإلكتروني -->
        <div>
            <x-input-label for="email" :value="'البريد الإلكتروني'" />
            <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="أدخل بريدك الإلكتروني" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- كلمة المرور -->
        <div class="mt-5">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="'كلمة المرور'" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-[#1e3a8a] hover:text-blue-800 font-medium" href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="أدخل كلمة المرور" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- تذكرني -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#1e3a8a] shadow-sm focus:ring-[#1e3a8a]" name="remember">
                <span class="ms-2 text-sm text-gray-600">تذكرني</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="w-full flex justify-center items-center px-4 py-3 bg-[#1e3a8a] text-white text-sm font-semibold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
                تسجيل الدخول
                <svg class="w-4 h-4 mr-2 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </button>
        </div>

        @if (Route::has('register'))
        <p class="text-center text-sm text-gray-500 mt-6">
            ليس لديك حساب؟
            <a href="{{ route('register') }}" class="text-[#1e3a8a] hover:text-blue-800 font-semibold">إنشاء حساب جديد</a>
        </p>
        @endif
    </form>
</x-guest-layout>

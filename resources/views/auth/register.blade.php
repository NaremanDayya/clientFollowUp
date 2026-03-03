<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">إنشاء حساب جديد</h2>
        <p class="text-sm text-gray-500">أنشئ حسابك للبدء في إدارة عملائك بكفاءة</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- الاسم -->
        <div>
            <x-input-label for="name" :value="'الاسم'" />
            <x-text-input id="name" class="block mt-1.5 w-full rounded-xl border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="أدخل اسمك الكامل" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- البريد الإلكتروني -->
        <div class="mt-5">
            <x-input-label for="email" :value="'البريد الإلكتروني'" />
            <x-text-input id="email" class="block mt-1.5 w-full rounded-xl border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="أدخل بريدك الإلكتروني" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- كلمة المرور -->
        <div class="mt-5">
            <x-input-label for="password" :value="'كلمة المرور'" />
            <x-text-input id="password" class="block mt-1.5 w-full rounded-xl border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="أدخل كلمة المرور" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- تأكيد كلمة المرور -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="'تأكيد كلمة المرور'" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full rounded-xl border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="أعد كتابة كلمة المرور" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="w-full flex justify-center items-center px-4 py-3 bg-[#1e3a8a] text-white text-sm font-semibold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
                إنشاء الحساب
            </button>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="text-[#1e3a8a] hover:text-blue-800 font-semibold">تسجيل الدخول</a>
        </p>
    </form>
</x-guest-layout>

<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        هذه منطقة آمنة من التطبيق. يرجى تأكيد كلمة المرور قبل المتابعة.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- كلمة المرور -->
        <div>
            <x-input-label for="password" :value="'كلمة المرور'" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                تأكيد
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

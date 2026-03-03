<x-app-layout>
    <div class="max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">الملف الشخصي</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة معلومات حسابك وإعدادات الأمان.</p>
        </div>

        <div class="space-y-6">
            <div class="p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-200">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

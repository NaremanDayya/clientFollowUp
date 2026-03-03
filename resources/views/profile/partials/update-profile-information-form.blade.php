<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            معلومات الملف الشخصي
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            تحديث معلومات حسابك والبريد الإلكتروني.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="personal_image" :value="'صورة الملف الشخصي'" />
            <div class="mt-2 flex items-center gap-4">
                <img class="w-16 h-16 rounded-full ring-2 ring-gray-200 object-cover" src="{{ $user->avatar_url }}" alt="">
                <input id="personal_image" name="personal_image" type="file" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-[#1e3a8a] hover:file:bg-blue-100" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('personal_image')" />
        </div>

        <div>
            <x-input-label for="name" :value="'الاسم'" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="'البريد الإلكتروني'" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        بريدك الإلكتروني غير موثق.

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            اضغط هنا لإعادة إرسال بريد التحقق.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>حفظ</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >تم الحفظ.</p>
            @endif
        </div>
    </form>
</section>

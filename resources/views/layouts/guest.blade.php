<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'نظام إدارة العملاء') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="text-gray-900 antialiased" style="font-family: 'Tajawal', sans-serif;">
        <div class="min-h-screen flex">
            {{-- Left Side - Branding Panel --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-bl from-[#1e3a8a] via-[#1e40af] to-[#0f172a] relative overflow-hidden">
                {{-- Background decorations --}}
                <div class="absolute inset-0">
                    <div class="absolute -top-40 -left-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 w-80 h-80 bg-indigo-400/5 rounded-full blur-3xl"></div>
                </div>

                <div class="relative z-10 flex flex-col justify-center px-16 w-full">
                    <div class="flex items-center gap-3 mb-12">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-white text-2xl font-bold">إدارة العملاء</span>
                    </div>

                    <h2 class="text-4xl font-extrabold text-white leading-tight mb-6">
                        نظام متكامل
                        <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-l from-blue-200 to-cyan-200">لإدارة علاقات العملاء</span>
                    </h2>

                    <p class="text-white/60 text-lg leading-relaxed mb-10 max-w-md">
                        تتبع بيانات العملاء، متابعة التحديثات، إدارة فريق العمل، والتواصل الفعّال — كل ذلك من مكان واحد.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white/70">إدارة شاملة للعملاء والمتابعات</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white/70">محادثات فورية بين أعضاء الفريق</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-white/70">لوحة تحكم ذكية وتقارير فورية</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side - Form --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12 bg-gray-50">
                {{-- Mobile Logo --}}
                <div class="lg:hidden mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#1e3a8a] rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">نظام إدارة العملاء</span>
                </div>

                <div class="w-full max-w-md">
                    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 ring-1 ring-gray-100 px-8 py-8">
                        {{ $slot }}
                    </div>

                    <p class="text-center text-gray-400 text-xs mt-6">جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام إدارة العملاء</p>
                </div>
            </div>
        </div>
    </body>
</html>

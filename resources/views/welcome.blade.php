<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>نظام إدارة العملاء</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased" style="font-family: 'Tajawal', sans-serif;">
        <div class="min-h-screen bg-gradient-to-bl from-[#1e3a8a] via-[#1e40af] to-[#0f172a] relative overflow-hidden">

            {{-- Background decorations --}}
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -left-40 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute top-1/3 -right-20 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl"></div>
                <div class="absolute top-20 right-1/3 w-2 h-2 bg-white/30 rounded-full"></div>
                <div class="absolute top-40 left-1/4 w-1.5 h-1.5 bg-white/20 rounded-full"></div>
                <div class="absolute bottom-32 right-1/4 w-2.5 h-2.5 bg-white/25 rounded-full"></div>
            </div>

            {{-- Navigation --}}
            <nav class="relative z-10 px-6 lg:px-16 py-6">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-white text-xl font-bold">إدارة العملاء</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}"
                           class="px-5 py-2.5 text-sm font-medium text-white/90 hover:text-white transition-colors">
                            تسجيل الدخول
                        </a>
{{--                        @if (Route::has('register'))--}}
{{--                        <a href="{{ route('register') }}"--}}
{{--                           class="px-5 py-2.5 text-sm font-medium text-[#1e3a8a] bg-white rounded-lg hover:bg-gray-100 shadow-lg shadow-black/10 transition-all">--}}
{{--                            إنشاء حساب--}}
{{--                        </a>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </nav>

            {{-- Hero Section --}}
            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 pt-16 pb-32">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    {{-- Text Content --}}
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-8">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="text-white/80 text-sm">نظام متكامل لإدارة علاقات العملاء</span>
                        </div>

                        <h1 class="text-4xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                            أدِر عملاءك
                            <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-l from-blue-200 to-cyan-200">بذكاء وكفاءة</span>
                        </h1>

                        <p class="text-lg text-white/70 leading-relaxed mb-10 max-w-lg">
                            نظام إدارة عملاء احترافي يساعدك على تتبع بيانات العملاء، متابعة التحديثات، إدارة فريق العمل، والتواصل الفعّال — كل ذلك من مكان واحد.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-[#1e3a8a] bg-white rounded-xl hover:bg-gray-100 shadow-xl shadow-black/20 transition-all">
                                ابدأ الآن
                                <svg class="w-5 h-5 mr-2 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Feature Cards --}}
                    <div class="hidden lg:grid grid-cols-2 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-colors">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h3 class="text-white font-semibold mb-2">إدارة العملاء</h3>
                            <p class="text-white/60 text-sm">إضافة وتعديل وتتبع بيانات العملاء بسهولة تامة.</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-colors mt-8">
                            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <h3 class="text-white font-semibold mb-2">محادثات فورية</h3>
                            <p class="text-white/60 text-sm">تواصل مع فريق العمل حول كل عميل مباشرة.</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-colors">
                            <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h3 class="text-white font-semibold mb-2">لوحة تحكم ذكية</h3>
                            <p class="text-white/60 text-sm">إحصائيات ومؤشرات أداء لحظية لمتابعة سير العمل.</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-colors mt-8">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-white font-semibold mb-2">تنبيهات المتابعة</h3>
                            <p class="text-white/60 text-sm">تنبيهات تلقائية للعملاء المتأخرين عن المتابعة.</p>
                        </div>
                    </div>
                </div>

                {{-- Stats Bar --}}
                <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-white mb-1">100%</div>
                        <div class="text-white/50 text-sm">عربي بالكامل</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-white mb-1">سهل</div>
                        <div class="text-white/50 text-sm">واجهة مستخدم بسيطة</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-white mb-1">آمن</div>
                        <div class="text-white/50 text-sm">حماية كاملة للبيانات</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-white mb-1">سريع</div>
                        <div class="text-white/50 text-sm">أداء عالي ومستقر</div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="relative z-10 border-t border-white/10 py-6">
                <p class="text-center text-white/40 text-sm">جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام إدارة العملاء</p>
            </div>
        </div>
    </body>
</html>

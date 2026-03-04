<div>
    {{-- Calendar Icon Button --}}
    <button wire:click="openCalendar" 
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 transition-colors"
            title="عرض التقويم الشهري">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </button>

    {{-- Calendar Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" x-on:remove="document.body.classList.remove('overflow-hidden')">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeCalendar"></div>
            
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl z-10 ring-1 ring-gray-200 max-h-[90vh] overflow-y-auto">
                {{-- Header --}}
                <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 rounded-t-2xl z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">تقويم التحديثات - {{ $client->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">عرض التحديثات الشهرية للعميل</p>
                        </div>
                        <button wire:click="closeCalendar" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Calendar Content --}}
                <div class="p-6">
                    {{-- Month Navigation --}}
                    <div class="flex items-center justify-between mb-6">
                        <button wire:click="previousMonth" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <h4 class="text-lg font-semibold text-gray-900">{{ $this->monthName }} {{ $year }}</h4>
                        
                        <button wire:click="nextMonth" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Calendar Grid --}}
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        {{-- Week Days Header --}}
                        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                            <div class="px-2 py-3 text-center text-xs font-semibold text-gray-600">الأحد</div>
                            <div class="px-2 py-3 text-center text-xs font-semibold text-gray-600">الإثنين</div>
                            <div class="px-2 py-3 text-center text-xs font-semibold text-gray-600">الثلاثاء</div>
                            <div class="px-2 py-3 text-center text-xs font-semibold text-gray-600">الأربعاء</div>
                            <div class="px-2 py-3 text-center text-xs font-semibold text-gray-600">الخميس</div>
                            <div class="px-2 py-3 text-center text-xs font-semibold text-red-600">الجمعة</div>
                            <div class="px-2 py-3 text-center text-xs font-semibold text-red-600">السبت</div>
                        </div>

                        {{-- Calendar Days --}}
                        @foreach($this->calendarData as $week)
                        <div class="grid grid-cols-7 border-b border-gray-200 last:border-b-0">
                            @foreach($week as $day)
                            <div class="min-h-[100px] border-l border-gray-200 last:border-l-0 p-2 
                                {{ $day['isWeekend'] ? 'bg-gray-100' : '' }}
                                {{ !$day['isCurrentMonth'] ? 'opacity-40' : '' }}
                                {{ $day['hasUpdates'] ? 'bg-green-50' : '' }}">
                                
                                <div class="text-sm font-medium mb-1
                                    {{ $day['isWeekend'] ? 'text-red-600' : 'text-gray-700' }}
                                    {{ $day['hasUpdates'] ? 'text-green-700 font-bold' : '' }}">
                                    {{ $day['day'] }}
                                </div>

                                @if($day['hasUpdates'])
                                    <div class="space-y-1">
                                        @foreach($day['updates'] as $update)
                                        <div class="bg-white rounded px-2 py-1 text-xs border border-green-200 hover:border-green-400 transition-colors">
                                            <div class="font-semibold text-green-700 truncate" title="{{ $update['title'] }}">
                                                {{ $update['title'] }}
                                            </div>
                                            @if($update['notes'])
                                            <div class="text-gray-600 text-[10px] truncate mt-0.5" title="{{ $update['notes'] }}">
                                                {{ Str::limit($update['notes'], 30) }}
                                            </div>
                                            @endif
                                            <div class="text-gray-400 text-[10px] mt-0.5">
                                                {{ $update['user']['name'] ?? 'System' }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>

                    {{-- Legend --}}
                    <div class="mt-4 flex items-center gap-4 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-50 border border-green-200 rounded"></div>
                            <span>يوم به تحديثات</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-gray-100 border border-gray-200 rounded"></div>
                            <span>عطلة نهاية الأسبوع</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

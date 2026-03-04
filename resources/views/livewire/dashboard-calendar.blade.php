<div class="space-y-6">
    {{-- Header with Client Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900">تقويم التحديثات</h3>
            <p class="text-sm text-gray-500 mt-1">عرض جميع التحديثات حسب التاريخ</p>
        </div>
        
        {{-- Client Filter --}}
        <div class="flex items-center gap-2">
            <select wire:model.live="selectedClientId" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                <option value="">جميع العملاء</option>
                @foreach($this->clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
            
            @if($selectedClientId)
            <button wire:click="resetFilter" 
                    class="px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                    title="إعادة تعيين الفلتر">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Calendar Section --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
                {{-- Month Navigation --}}
                <div class="flex items-center justify-between mb-6">
                    <button wire:click="previousMonth" 
                            class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    
                    <h4 class="text-lg font-semibold text-gray-900">{{ $this->monthName }} {{ $year }}</h4>
                    
                    <button wire:click="nextMonth" 
                            class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
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
                        <div wire:click="selectDate('{{ $day['dateString'] }}')"
                             class="min-h-[80px] border-l border-gray-200 last:border-l-0 p-2 transition-all
                                {{ $day['isWeekend'] ? 'bg-gray-100 cursor-not-allowed' : 'cursor-pointer hover:bg-blue-50' }}
                                {{ !$day['isCurrentMonth'] ? 'opacity-40' : '' }}
                                {{ $day['hasUpdates'] ? 'bg-green-50 hover:bg-green-100' : '' }}
                                {{ $selectedDate === $day['dateString'] ? 'ring-2 ring-[#1e3a8a] ring-inset' : '' }}">
                            
                            <div class="text-sm font-medium mb-1
                                {{ $day['isWeekend'] ? 'text-red-600' : 'text-gray-700' }}
                                {{ $day['hasUpdates'] ? 'text-green-700 font-bold' : '' }}
                                {{ $selectedDate === $day['dateString'] ? 'text-[#1e3a8a]' : '' }}">
                                {{ $day['day'] }}
                            </div>

                            @if($day['hasUpdates'])
                            <div class="mt-1">
                                <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-green-600 rounded-full">
                                    {{ $day['updatesCount'] }}
                                </span>
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
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 ring-2 ring-[#1e3a8a] rounded"></div>
                        <span>اليوم المحدد</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Updates List Section --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6 sticky top-6">
                @if($selectedDate)
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">
                            تحديثات يوم {{ Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $this->selectedDateUpdates->count() }} تحديث
                        </p>
                    </div>

                    @if($this->selectedDateUpdates->count() > 0)
                        <div class="space-y-3 max-h-[600px] overflow-y-auto">
                            @foreach($this->selectedDateUpdates as $update)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-green-300 transition-colors">
                                {{-- Client Name --}}
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ $update->client->logo_url }}" 
                                         alt="{{ $update->client->name }}" 
                                         class="w-6 h-6 rounded-full ring-2 ring-gray-200">
                                    <a href="{{ route('clients.show', $update->client) }}" 
                                       class="text-sm font-semibold text-[#1e3a8a] hover:underline">
                                        {{ $update->client->name }}
                                    </a>
                                </div>

                                {{-- Update Title --}}
                                <h5 class="text-sm font-semibold text-gray-900 mb-1">
                                    {{ $update->title }}
                                </h5>

                                {{-- Update Notes --}}
                                @if($update->notes)
                                <p class="text-xs text-gray-600 mb-2">
                                    {{ $update->notes }}
                                </p>
                                @endif

                                {{-- Meta Info --}}
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $update->user->name }}</span>
                                    <span>{{ $update->created_at->format('h:i A') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500">لا توجد تحديثات في هذا اليوم</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-500">اختر يوماً من التقويم</p>
                        <p class="text-xs text-gray-400 mt-1">لعرض التحديثات</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div>
    <div class="max-w-2xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">الإعدادات</h2>
            <p class="text-sm text-gray-500 mt-1">تكوين إعدادات النظام العامة.</p>
        </div>

        @if(session('settings-saved'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm font-medium flex items-center">
            <svg class="w-5 h-5 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('settings-saved') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <label for="updateGapDays" class="block text-sm font-medium text-gray-700 mb-1">
                        فترة التحديث (أيام)
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        عدد الأيام التي يتم بعدها تصنيف العميل كـ"متأخر" إذا لم يتم إجراء متابعة.
                    </p>
                    <div class="flex items-center gap-3">
                        <input wire:model="updateGapDays" type="number" id="updateGapDays" min="1" max="365"
                               class="w-32 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        <span class="text-sm text-gray-500">يوم</span>
                    </div>
                    @error('updateGapDays') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-800 shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
                        حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

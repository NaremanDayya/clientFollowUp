<div>
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('clients.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#1e3a8a] transition-colors">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            العودة إلى العملاء
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Client Info Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden {{ $client->is_late ? 'ring-2 ring-red-400' : '' }}">
                {{-- Header --}}
                <div class="p-6 {{ $client->is_late ? 'bg-red-50' : 'bg-gradient-to-br from-[#1e3a8a] to-blue-700' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-xl font-bold {{ $client->is_late ? 'bg-red-500 text-white' : 'bg-white/20 text-white' }}">
                            {{ strtoupper(substr($client->name, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold {{ $client->is_late ? 'text-red-800' : 'text-white' }}">{{ $client->name }}</h2>
                            @if($client->is_late)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-200 text-red-800 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    متأخر - يحتاج متابعة
                                </span>
                            @else
                                <p class="text-blue-200 text-sm">{{ ucfirst($client->status) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="p-6 space-y-4">
                    @if(!$editing)
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">الهاتف</span>
                                <span class="font-medium text-gray-900">{{ $client->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">البريد</span>
                                <span class="font-medium text-gray-900">{{ $client->email ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">الحالة</span>
                                @php
                                    $colors = ['new'=>'bg-blue-100 text-blue-700','active'=>'bg-green-100 text-green-700','inactive'=>'bg-gray-100 text-gray-600','completed'=>'bg-purple-100 text-purple-700'];
                                @endphp
                                @php $statusLabels = ['new'=>'جديد','active'=>'نشط','inactive'=>'غير نشط','completed'=>'مكتمل']; @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$client->status] ?? '' }}">{{ $statusLabels[$client->status] ?? $client->status }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">معين إلى</span>
                                <span class="font-medium text-gray-900">{{ $client->assignedUser->name ?? 'غير معين' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">آخر تحديث</span>
                                <span class="font-medium text-gray-900">{{ $client->last_update_at ? $client->last_update_at->diffForHumans() : 'لم يتم' }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 pt-4 border-t border-gray-200">
                            <button wire:click="$set('editing', true)"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-[#1e3a8a] bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                تعديل البيانات
                            </button>
                            @if($client->chat)
                            <a href="{{ route('chats.show', $client->chat) }}"
                               class="flex-1 px-4 py-2 text-sm font-medium text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-800 transition-colors text-center">
                                فتح المحادثة
                            </a>
                            @endif
                        </div>
                    @else
                        {{-- Edit Form --}}
                        <form wire:submit="saveClient" class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">الاسم</label>
                                <input wire:model="editName" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                @error('editName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">الهاتف</label>
                                <input wire:model="editPhone" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">البريد</label>
                                <input wire:model="editEmail" type="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">الحالة</label>
                                <select wire:model="editStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                    <option value="new">جديد</option>
                                    <option value="active">نشط</option>
                                    <option value="inactive">غير نشط</option>
                                    <option value="completed">مكتمل</option>
                                </select>
                            </div>
                            @if(auth()->user()->isAdmin())
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">معين إلى</label>
                                <select wire:model="editAssignedTo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                    <option value="">غير معين</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="flex gap-2 pt-3">
                                <button type="button" wire:click="$set('editing', false)"
                                        class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                    إلغاء
                                </button>
                                <button type="submit"
                                        class="flex-1 px-4 py-2 text-sm font-medium text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-800 shadow-sm transition-all">
                                    حفظ
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Updates Section --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Add Update Form --}}
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إضافة تحديث متابعة</h3>
                <form wire:submit="addUpdate" class="space-y-4">
                    <div>
                        <input wire:model="updateTitle" type="text" placeholder="عنوان التحديث (مثل: 'اتصلت بالعميل', 'أرسلت عرض')"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        @error('updateTitle') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <textarea wire:model="updateNotes" rows="3" placeholder="ملاحظات إضافية..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] resize-none"></textarea>
                    </div>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-800 shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
                        إرسال التحديث
                    </button>
                </form>
            </div>

            {{-- Update History --}}
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">سجل التحديثات</h3>
                <div class="space-y-4">
                    @forelse($updates as $update)
                    <div class="relative pr-8 pb-4 {{ !$loop->last ? 'border-r-2 border-gray-200' : '' }} mr-3">
                        <div class="absolute -right-[9px] top-0 w-5 h-5 bg-[#1e3a8a] rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $update->title }}</p>
                                    @if($update->notes)
                                        <p class="text-sm text-gray-600 mt-1">{{ $update->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                {{ $update->user->name ?? 'System' }} &middot; {{ $update->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-gray-500 font-medium">لا توجد تحديثات بعد.</p>
                        <p class="text-gray-400 text-sm">أضف أول تحديث متابعة أعلاه.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

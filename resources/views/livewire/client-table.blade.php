<div>
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">العملاء</h2>
            <p class="text-sm text-gray-500 mt-1">إدارة ومتابعة سجلات العملاء.</p>
        </div>
        @unless(auth()->user()->isAdmin())
        <button wire:click="$set('showCreateModal', true)"
                class="inline-flex items-center px-4 py-2.5 bg-[#1e3a8a] text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إضافة عميل
        </button>
        @endunless
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div class="relative">
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="بحث بالاسم، الهاتف، البريد..."
                       class="w-full pr-10 pl-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] transition-shadow">
            </div>
            {{-- Status Filter --}}
            <select wire:model.live="statusFilter" class="w-full py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                <option value="">جميع الحالات</option>
                <option value="new">جديد</option>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
                <option value="completed">مكتمل</option>
            </select>
            {{-- Employee Filter (Admin only) --}}
            @if(auth()->user()->isAdmin())
            <select wire:model.live="employeeFilter" class="w-full py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                <option value="">جميع الموظفين</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 tracking-wider">العميل</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 tracking-wider">رقم الجوال</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 tracking-wider">البريد</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 tracking-wider">الحالة</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 tracking-wider">معين إلى</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 tracking-wider">آخر تحديث</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 tracking-wider text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clients as $client)
                    <tr class="{{ $client->is_late ? 'bg-red-50 hover:bg-red-100' : 'hover:bg-gray-50' }} transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" class="w-10 h-10 rounded-full object-cover ring-2 {{ $client->is_late ? 'ring-red-300' : 'ring-blue-200' }}">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $client->name }}</p>
                                    @if($client->is_late)
                                        <span class="text-xs text-red-600 font-medium">متأخر - يحتاج متابعة</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($client->phone)
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-700 font-medium">{{ $client->formatted_phone }}</span>
                                    <a href="{{ $client->whatsapp_link }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-500 hover:bg-green-600 transition-colors"
                                       title="فتح محادثة واتساب">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                    </a>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $client->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'new' => 'bg-blue-100 text-blue-700',
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-gray-100 text-gray-600',
                                    'completed' => 'bg-purple-100 text-purple-700',
                                ];
                            @endphp
                            @php
                                $statusLabels = ['new'=>'جديد','active'=>'نشط','inactive'=>'غير نشط','completed'=>'مكتمل'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $colors[$client->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $statusLabels[$client->status] ?? $client->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $client->assignedUser->name ?? 'غير معين' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $client->last_update_at ? $client->last_update_at->diffForHumans() : 'لم يتم' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @unless(auth()->user()->isAdmin())
                                <button wire:click="openUpdateModal({{ $client->id }})"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors"
                                        title="إضافة تحديث">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endunless
                                @livewire('client-calendar', ['client' => $client], key('calendar-'.$client->id))
                                <a href="{{ route('clients.show', $client) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:text-[#1e3a8a] hover:bg-blue-50 transition-colors"
                                   title="عرض الملف">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-gray-500 font-medium">لم يتم العثور على عملاء.</p>
                            <p class="text-gray-400 text-sm">حاول تعديل البحث أو الفلاتر.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $clients->links() }}
        </div>
    </div>

    {{-- Create Client Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" x-on:remove="document.body.classList.remove('overflow-hidden')">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('showCreateModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 ring-1 ring-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">إضافة عميل جديد</h3>
                </div>
                <form wire:submit="createClient" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم *</label>
                        <input wire:model="newName" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="اسم العميل">
                        @error('newName') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">شعار العميل</label>
                        <input wire:model="newLogo" type="file" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                        @error('newLogo') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        @if ($newLogo)
                            <div class="mt-2">
                                <img src="{{ $newLogo->temporaryUrl() }}" class="w-20 h-20 rounded-lg object-cover ring-2 ring-blue-200">
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الجوال *</label>
                            <input wire:model="newPhone" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="05xxxxxxxx" maxlength="10">
                            @error('newPhone') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">أدخل 10 أرقام تبدأ بـ 05</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input wire:model="newEmail" type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="email@example.com">
                            @error('newEmail') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                            <select wire:model="newStatus" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
                                <option value="new">جديد</option>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                                <option value="completed">مكتمل</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$set('showCreateModal', false)"
                                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2.5 text-sm font-medium text-white bg-[#1e3a8a] rounded-lg hover:bg-blue-800 shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a] transition-all">
                            إنشاء عميل
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Add Update Modal --}}
    @if($showUpdateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data x-init="document.body.classList.add('overflow-hidden')" x-on:remove="document.body.classList.remove('overflow-hidden')">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('showUpdateModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 ring-1 ring-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">إضافة تحديث للعميل</h3>
                </div>
                <form wire:submit="addUpdate" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عنوان التحديث *</label>
                        <input wire:model="updateTitle" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="مثال: تم إرسال العرض">
                        @error('updateTitle') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">التفاصيل</label>
                        <textarea wire:model="updateNotes" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]" placeholder="أضف تفاصيل التحديث هنا..."></textarea>
                        @error('updateNotes') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$set('showUpdateModal', false)"
                                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-4 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-all">
                            إضافة التحديث
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

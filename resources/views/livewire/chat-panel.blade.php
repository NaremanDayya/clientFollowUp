<div class="flex h-[calc(100vh-7rem)] -m-6">
    {{-- Chat List Sidebar --}}
    <div class="w-80 bg-white border-l border-gray-200 flex flex-col flex-shrink-0">
        {{-- Search --}}
        <div class="p-4 border-b border-gray-200">
            <div class="relative">
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="chatSearch" type="text" placeholder="بحث عن عميل..."
                       class="w-full pr-9 pl-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a]">
            </div>
        </div>

        {{-- Chat List --}}
        <div class="flex-1 overflow-y-auto">
            @forelse($chats as $chatItem)
            <button wire:click="selectChat({{ $chatItem->id }})" wire:key="chat-{{ $chatItem->id }}"
                    class="w-full flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 text-left
                           {{ $chat && $chat->id === $chatItem->id ? 'bg-blue-50 border-r-4 border-r-[#1e3a8a]' : '' }}">
                <div class="w-10 h-10 rounded-full bg-[#1e3a8a] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($chatItem->client->name ?? '?', 0, 2)) }}
                </div>
                <div class="mr-3 flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $chatItem->client->name ?? 'Unknown' }}</p>
                        @if($chatItem->latestMessage)
                            <span class="text-xs text-gray-400 flex-shrink-0 mr-2">{{ $chatItem->latestMessage->created_at->shortRelativeDiffForHumans() }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500 truncate">
                            {{ $chatItem->latestMessage ? Str::limit($chatItem->latestMessage->body, 35) : 'لا توجد رسائل' }}
                        </p>
                        @if($chatItem->unread_messages_count > 0)
                            <span class="mr-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-[#1e3a8a] rounded-full flex-shrink-0">
                                {{ $chatItem->unread_messages_count }}
                            </span>
                        @endif
                    </div>
                </div>
            </button>
            @empty
            <div class="p-8 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <p class="text-sm text-gray-500">لا توجد محادثات.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Messages Area --}}
    <div class="flex-1 flex flex-col bg-gray-50">
        @if($chat)
            {{-- Chat Header --}}
            <div class="px-6 py-3 bg-white border-b border-gray-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#1e3a8a] flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($chat->client->name ?? '?', 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $chat->client->name ?? 'Unknown' }}</h3>
                        <p class="text-xs text-gray-500">{{ $chat->client->assignedUser->name ?? 'Unassigned' }}</p>
                    </div>
                </div>
                <a href="{{ route('clients.show', $chat->client) }}"
                   class="text-xs text-[#1e3a8a] hover:underline font-medium">عرض الملف</a>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container" wire:poll.5s 
                 x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
                 x-init="scrollToBottom(); $watch('$wire.messages', () => { setTimeout(() => scrollToBottom(), 100) })">
                @foreach($messages as $message)
                    @if($message->is_system_log)
                        {{-- System Log as Employee Message --}}
                        <div class="flex justify-start">
                            <div class="max-w-lg">
                                <div class="flex items-center gap-2 mb-1 mr-1">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-xs font-medium text-gray-600">{{ $message->sender->name ?? 'النظام' }}</p>
                                </div>
                                <div class="bg-blue-50 border-r-4 border-blue-500 px-4 py-3 rounded-lg shadow-sm">
                                    <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ $message->body }}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 mr-1">{{ $message->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    @elseif($message->sender_id === auth()->id())
                        {{-- Sent Message --}}
                        <div class="flex justify-end">
                            <div class="max-w-sm">
                                <div class="bg-[#1e3a8a] text-white px-4 py-2.5 rounded-2xl rounded-bl-md shadow-sm">
                                    <p class="text-sm">{{ $message->body }}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 text-right">{{ $message->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    @else
                        {{-- Received Message --}}
                        <div class="flex justify-start">
                            <div class="max-w-sm">
                                <p class="text-xs text-gray-500 mb-1 mr-1">{{ $message->sender->name ?? 'غير معروف' }}</p>
                                <div class="bg-white px-4 py-2.5 rounded-2xl rounded-br-md shadow-sm ring-1 ring-gray-200">
                                    <p class="text-sm text-gray-800">{{ $message->body }}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $message->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Message Input --}}
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                <form wire:submit="sendMessage" class="flex items-center gap-3">
                    <input wire:model="messageBody" type="text" placeholder="اكتب رسالة..."
                           class="flex-1 border border-gray-300 rounded-full px-5 py-2.5 text-sm focus:ring-2 focus:ring-[#1e3a8a] focus:border-[#1e3a8a] transition-shadow"
                           autocomplete="off">
                    <button type="submit"
                            class="w-10 h-10 bg-[#1e3a8a] text-white rounded-full flex items-center justify-center hover:bg-blue-800 shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-[#1e3a8a]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </div>
        @else
            {{-- No Chat Selected --}}
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <h3 class="text-lg font-semibold text-gray-400">اختر محادثة</h3>
                    <p class="text-sm text-gray-400 mt-1">اختر محادثة عميل من اللوحة الجانبية.</p>
                </div>
            </div>
        @endif
    </div>
</div>

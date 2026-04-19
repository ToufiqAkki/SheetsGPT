<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center whitespace-nowrap">
            <h2 class="font-semibold text-xl leading-tight">
                {{ $workspace->name }}
            </h2>
            <div class="text-sm text-neutral-400">
                <a href="{{ route('workspaces.index') }}" class="hover:text-white transition">← Back to Workspaces</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 h-[calc(100vh-80px)] overflow-hidden flex flex-col pt-[20px]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full flex-1 flex flex-col md:flex-row gap-6 h-full pb-8">
            
            <!-- Sidebar: Documents -->
            <div class="md:w-1/3 bg-neutral-900 border border-neutral-800 shadow-sm sm:rounded-lg overflow-hidden flex flex-col">
                <div class="p-4 border-b border-neutral-800">
                    <h3 class="font-semibold text-lg">Documents Context</h3>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    @foreach($documents as $doc)
                        <div class="bg-neutral-950 border border-neutral-800 p-3 rounded-md flex justify-between items-center group">
                            <span class="text-sm truncate mr-2" title="{{ $doc->original_name }}">
                                {{ $doc->original_name }}
                            </span>
                            <form action="{{ route('documents.destroy', [$workspace, $doc]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-neutral-500 hover:text-red-500 opacity-0 group-hover:opacity-100 transition" onclick="return confirm('Remove document? This removes it from AI context.')">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                    @if($documents->isEmpty())
                        <div class="text-sm text-neutral-500 italic text-center py-4">No documents uploaded. The AI has no context.</div>
                    @endif
                </div>

                <div class="p-4 border-t border-neutral-800 bg-neutral-950/50">
                    <form action="{{ route('documents.store', $workspace) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="file" name="files[]" multiple accept=".csv,.xlsx,.xls" required
                            class="block w-full text-sm text-neutral-400
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-neutral-800 file:text-neutral-200
                                   hover:file:bg-neutral-700 transition">
                        <button type="submit" class="w-full py-2 bg-neutral-200 text-neutral-900 font-semibold rounded-md text-sm hover:bg-white transition shadow-sm">
                            Upload & Parse
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main: Chat Interface -->
            <div class="md:w-2/3 bg-neutral-900 border border-neutral-800 shadow-sm sm:rounded-lg flex flex-col overflow-hidden relative">
                @php
                    $conversation = $conversations->first();
                    $messages = $conversation ? $conversation->messages()->oldest()->get() : collect([]);
                @endphp

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6 scroll-smooth" id="chat-container">
                    @if($messages->isEmpty())
                        <div class="h-full flex flex-col items-center justify-center text-neutral-500 space-y-4">
                            <svg class="h-12 w-12 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p>Upload a document and start chatting.</p>
                        </div>
                    @else
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[85%] rounded-2xl px-5 py-3 {{ $msg->role === 'user' ? 'bg-neutral-200 text-neutral-900 rounded-tr-sm' : 'bg-neutral-800 text-neutral-200 rounded-tl-sm border border-neutral-700' }}">
                                    @if($msg->role === 'assistant')
                                        <div class="overflow-x-auto max-w-full pb-2">
                                            <div class="prose prose-invert prose-sm max-w-none w-full
                                                        prose-table:border-collapse prose-table:w-full prose-table:min-w-max prose-table:my-4
                                                        prose-th:border prose-th:border-neutral-600 prose-th:p-2 prose-th:bg-neutral-900
                                                        prose-td:border prose-td:border-neutral-700 prose-td:p-2">
                                                {!! str()->markdown($msg->content) !!}
                                            </div>
                                        </div>
                                        <div class="mt-3 text-right">
                                            <a href="{{ route('messages.export', $msg) }}" class="text-xs text-neutral-400 hover:text-white inline-flex items-center gap-1 transition">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                Export PDF
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-sm whitespace-pre-wrap">{{ $msg->content }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Input Area -->
                <div class="p-4 border-t border-neutral-800 bg-neutral-950/80 backdrop-blur">
                    <form action="{{ route('chat.store', $workspace) }}" method="POST" class="relative">
                        @csrf
                        <textarea name="message" rows="1" required 
                                  placeholder="Ask about your documents..."
                                  class="w-full bg-neutral-900 border border-neutral-700 rounded-full pl-6 pr-14 py-3 text-sm text-neutral-200 focus:ring-neutral-500 focus:border-neutral-500 resize-none overflow-hidden"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight +'px';"
                                  onkeydown="if(event.keyCode===13 && !event.shiftKey){event.preventDefault(); this.closest('form').submit();}"></textarea>
                        <button type="submit" class="absolute right-2 bottom-2 w-8 h-8 flex items-center justify-center bg-neutral-200 text-neutral-900 rounded-full hover:bg-white transition shadow-sm">
                            <svg class="h-4 w-4 transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        </button>
                    </form>
                </div>
                
            </div>
            
        </div>
    </div>

    <!-- Scroll to bottom script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('chat-container');
            if(container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
</x-app-layout>

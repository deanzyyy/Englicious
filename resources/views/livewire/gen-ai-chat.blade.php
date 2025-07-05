<div class="relative w-full">
    @if(count($messages) === 0)
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center select-none pointer-events-none">
            <div>
                <span class="mx-auto mb-4 w-14 h-14 block">
                    <img src="{{ asset('img/VocaLogoOnly.png') }}" alt="Voca AI" width="56" height="56" class="w-14 h-14">
                </span>
                <p class="text-sm italic text-gray-400 mb-2 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 48 48" class="mr-1">
                        <defs>
                          <linearGradient id="gemini-powered-logo" x1="3.906" x2="45.428" y1="3.906" y2="45.428" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#ca5df5"></stop>
                            <stop offset=".036" stop-color="#c05ff4"></stop>
                            <stop offset=".293" stop-color="#806cea"></stop>
                            <stop offset=".528" stop-color="#4d77e3"></stop>
                            <stop offset=".731" stop-color="#297fdd"></stop>
                            <stop offset=".895" stop-color="#1283da"></stop>
                            <stop offset="1" stop-color="#0a85d9"></stop>
                          </linearGradient>
                        </defs>
                        <path fill="url(#gemini-powered-logo)" d="M46.117,23.081l-0.995-0.04H45.12C34.243,22.613,25.387,13.757,24.959,2.88l-0.04-0.996 C24.9,1.39,24.494,1,24,1s-0.9,0.39-0.919,0.883l-0.04,0.996c-0.429,10.877-9.285,19.733-20.163,20.162l-0.995,0.04C1.39,23.1,1,23.506,1,24s0.39,0.9,0.884,0.919l0.995,0.039c10.877,0.43,19.733,9.286,20.162,20.163l0.04,0.996C23.1,46.61,23.506,47,24,47s0.9-0.39,0.919-0.883l0.04-0.996c0.429-10.877,9.285-19.733,20.162-20.163l0.995-0.039C46.61,24.9,47,24.494,47,24S46.61,23.1,46.117,23.081z"></path>
                    </svg>
                    Powered by Gemini & Englicious
                </p>
                @if(auth()->user()->role === 'teacher' || auth()->user()->role === 'admin')
                    <p class="text-2xl font-semibold mb-2 text-white">Welcome, Teacher!</p>
                    <p class="text-lg text-white">Use Voca AI to help you manage materials, exercises, and classrooms more efficiently. Ask for ideas, generate content, or get teaching support!</p>
                @elseif(auth()->user()->role === 'student')
                    <!-- No welcome message for students -->
                @endif
            </div>
        </div>
    @endif
    <div class="flex flex-col gap-4 px-2 pb-4 pt-2" style="min-height:calc(100vh - 6.5rem);">
        <div class="flex-1 flex flex-col gap-4 overflow-y-auto" id="chat-messages">
            @foreach($messages as $msg)
                <div class="w-full flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] px-4 py-3 rounded-xl shadow border {{ $msg['role'] === 'user' ? 'bg-gradient-to-r from-pink-500 to-orange-500 text-white border-pink-500/30' : 'bg-[#18171d] text-white border-pink-500/10' }}">
                        <span class="whitespace-pre-line text-base">{{ $msg['text'] }}</span>
                    </div>
                </div>
            @endforeach
            @if ($isLoading)
                <div class="flex justify-start">
                    <svg class="animate-spin h-8 w-8 text-pink-400 mx-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                    <span class="text-pink-400 font-bold ml-2">Voca is thinking...</span>
                </div>
            @endif
        </div>
    </div>
    <form wire:submit.prevent="sendPrompt" class="w-full flex gap-2 items-center bg-[#18171d] border-t border-pink-500/20 px-4 py-4 sticky bottom-0 z-10" autocomplete="off" style="box-shadow: 0 -2px 16px 0 #211F27cc;" x-data>
        <input wire:model.defer="prompt" type="text" placeholder="Type your question for Voca..." class="flex-1 px-4 py-3 rounded-lg bg-[#101014] text-white border border-pink-500/30 focus:border-pink-500 focus:ring-2 focus:ring-pink-500 outline-none transition text-lg" required autocomplete="off"
            @keydown.enter.prevent="if (!($event.shiftKey)) { $el.form.dispatchEvent(new Event('submit', {bubbles:true, cancelable:true})); }">
        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg font-semibold hover:opacity-90 transition text-lg flex items-center gap-2">
            <span>Send</span>
            <i class="fi fi-rr-paper-plane-top"></i>
        </button>
        <button type="button" wire:click="refreshChat" class="px-6 py-3 border-2 border-pink-500 text-pink-500 rounded-lg font-semibold hover:bg-pink-500 hover:text-white transition text-lg flex items-center gap-2">
            <span>Refresh Chat</span>
            <i class="fi fi-rr-refresh"></i>
        </button>
    </form>
</div>
<x-layout>
    <div class="min-h-screen bg-[#101014] py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="relative px-4 py-10 bg-[#211F27] shadow-lg sm:rounded-3xl sm:p-20">
                <div class="max-w-md mx-auto">
                    <div class="divide-y divide-gray-200/20">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                            <div class="chat-container h-96 overflow-y-auto mb-4 p-4 bg-[#161618] rounded-lg">
                                <div id="chat-messages" class="space-y-4">
                                    <!-- Messages will be inserted here -->
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <form id="chat-form" class="flex space-x-3">
                                    <input type="text" 
                                           id="message-input"
                                           class="flex-1 form-input rounded-md shadow-sm bg-[#161618] border-gray-700 text-white focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                           placeholder="Type your message...">
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-pink-600 hover:to-orange-600 active:from-pink-700 active:to-orange-700 focus:outline-none focus:border-pink-700 focus:ring ring-pink-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Send
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('head')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const chatMessages = document.getElementById('chat-messages');

        function appendMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'} mb-4`;
            
            messageDiv.innerHTML = `
                <div class="max-w-md px-4 py-2 rounded-lg ${isUser ? 'bg-gradient-to-r from-pink-500 to-orange-500 text-white' : 'bg-[#211F27] text-white'}">
                    <p class="text-sm">${content}</p>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            // Append user message
            appendMessage(message, true);
            messageInput.value = '';

            try {
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                
                if (data.success) {
                    appendMessage(data.message);
                } else {
                    appendMessage('Error: ' + data.error);
                }
            } catch (error) {
                appendMessage('Error: Could not send message');
                console.error('Error:', error);
            }
        });
    });
    </script>
    @endpush
</x-layout> 
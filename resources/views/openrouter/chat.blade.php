<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    @vite('resources/css/app.css')
  </head>
  <body class="bg-[#101014] overflow-hidden">
    
    <div class="flex h-screen">
        <x-sidebar></x-sidebar>
        
        <!-- Main Content Area - With proper margin from sidebar -->
        <div class="flex-1 ml-64">
            <!-- Content Container -->
            <div class="h-screen flex justify-center w-[85%] mx-auto">
                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col h-full">
                    <!-- Header Section -->
                    <div class="p-6">
                        <div class="judul">
                            <h1 class="text-white text-4xl font-bold flex items-center">
                                <i class="fi fi-sr-ai-technology mr-4 text-pink-500"></i>
                                AI Assistant
                            </h1>
                            <p class="text-gray-400 text-lg mt-2">Chat with our AI powered by OpenRouter!</p>
                        </div>
                    </div>

                    <!-- Chat Container with Fixed Height -->
                    <div class="flex-1 p-6 flex flex-col">
                        <div class="bg-[#211F27] rounded-lg shadow-lg flex flex-col h-full">
                            <!-- Chat Messages Container - Scrollable -->
                            <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4">
                                <div class="flex items-start space-x-2">
                                    <div class="bg-[#2D2B33] p-4 rounded-lg max-w-[80%]">
                                        <p class="text-gray-300">Hi! I'm your AI assistant. How can I help you today?</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Input Form - Fixed at bottom -->
                            <form id="chat-form" class="border-t border-gray-700 p-6 bg-[#211F27]" onsubmit="event.preventDefault(); sendMessage();">
                                <div class="flex space-x-4">
                                    <input type="text" 
                                           id="user-input" 
                                           class="flex-1 bg-[#2D2B33] text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500 border border-gray-700"
                                           placeholder="Type your message here..."
                                           autocomplete="off">
                                    <button type="submit"
                                            class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-6 py-3 rounded-lg hover:opacity-90 transition-opacity flex items-center">
                                        <span>Send</span>
                                        <i class="fi fi-rr-paper-plane-top ml-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const chatMessages = document.getElementById('chat-messages');
    const userInput = document.getElementById('user-input');
    const chatForm = document.getElementById('chat-form');

    async function sendMessage() {
        const message = userInput.value.trim();
        if (!message) return;

        // Clear input
        userInput.value = '';

        // Add user message to chat
        appendMessage('user', message);

        // Show loading indicator
        const loadingId = showLoading();

        try {
            const response = await fetch('{{ route("openrouter.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('API Response:', data); // Debug log

            // Remove loading indicator
            removeLoading(loadingId);

            if (data.success) {
                appendMessage('ai', data.message);
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        } catch (error) {
            console.error('Error:', error);
            removeLoading(loadingId);
            appendMessage('error', error.message || 'Sorry, something went wrong. Please try again.');
        }
    }

    function appendMessage(type, message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex items-start ' + (type === 'user' ? 'justify-end' : 'justify-start');
        
        const messageContent = document.createElement('div');
        messageContent.className = `p-4 rounded-lg max-w-[80%] ${
            type === 'user' 
                ? 'bg-pink-500/20 ml-2' 
                : type === 'error' 
                    ? 'bg-red-500/20 text-red-400' 
                    : 'bg-[#2D2B33]'
        }`;
        
        const messageText = document.createElement('p');
        messageText.className = 'text-gray-300 whitespace-pre-wrap';
        messageText.textContent = message;
        
        messageContent.appendChild(messageText);
        messageDiv.appendChild(messageContent);
        chatMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showLoading() {
        const loadingId = Date.now();
        const loadingDiv = document.createElement('div');
        loadingDiv.id = `loading-${loadingId}`;
        loadingDiv.className = 'flex items-start justify-start';
        
        const loadingContent = document.createElement('div');
        loadingContent.className = 'p-4 rounded-lg bg-[#2D2B33] max-w-[80%]';
        loadingContent.innerHTML = `
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-pink-500 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-2 h-2 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
        `;
        
        loadingDiv.appendChild(loadingContent);
        chatMessages.appendChild(loadingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return loadingId;
    }

    function removeLoading(loadingId) {
        const loadingDiv = document.getElementById(`loading-${loadingId}`);
        if (loadingDiv) {
            loadingDiv.remove();
        }
    }
    </script>
  </body>
</html> 
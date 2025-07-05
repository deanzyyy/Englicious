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
    @livewireStyles
    <style>
      .no-scrollbar::-webkit-scrollbar { display: none; }
      .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
  </head>
  <body class="bg-[#101014] overflow-hidden">
    <div class="flex h-screen">
        <x-sidebar></x-sidebar>
        <div class="flex-1 ml-64">
            <div class="h-screen flex justify-center w-[85%] mx-auto">
                <div class="flex-1 flex flex-col h-full">
                    <div class="flex-1 flex flex-col h-full">
                        <div class="flex-1 flex flex-col h-full">
                            <div class="flex-1 overflow-y-auto no-scrollbar" id="chat-scroll-area">
                                @livewire('gen-ai-chat')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
    <script>
      // Auto-scroll to bottom on new message (optional, can be removed if not needed)
      document.addEventListener('livewire:update', function() {
        var chatArea = document.getElementById('chat-scroll-area');
        if(chatArea) chatArea.scrollTop = chatArea.scrollHeight;
      });
    </script>
  </body>
</html>
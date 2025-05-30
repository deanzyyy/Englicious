<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat dengan AI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    
        .chat-box {
            position: relative;
            max-width: 1040px;
            background-color: white;
            margin-left: 280px;
            height: 100%;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    
        .message {
            margin-bottom: 15px;
        }
    
        .user {
            text-align: right;
        }
    
        .user .text {
            background: #d1e7dd;
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px;
        }
    
        .ai .text {
            background: #f8d7da;
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px;
        }
    
        form {
            display: flex;
            margin-top: 20px;
        }
    
        input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
    
        button {
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
    
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class='text-gray-900 h-dvh'>
    <div class="flex min-h-screen bg-gray-800">

    <x-sidebar></x-sidebar>
    
    
    <main class="flex-1 p-6 ">
        <div>
                @livewire('gen-ai-chat')
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    
    @vite('resources/css/app.css')
  </head>
  <body class="bg-gray-800">
    
    {{-- <div class="container">
        <div class="sidebar w-3xs min-h-screen border bg-gray-900 items-start">
            <div class="header flex items-center w-full h-full bg-gray-800 p-2 border-gray-700 border-b-2">
                <div class="logo">
                    <img src="{{ asset('img/Logo.png') }}" alt="" class="w-20 h-20">
                </div>
                <div class="logodetails">
                    <p class="title text-xl text-white font-bold">Englicious</p>
                    <small class="text-sm text-white ">Modern E-Learning</small>
                </div>
            </div>
            <div class="content p-5">
                <div class="title mb-2">
                    <small class="text-gray-300">Menu</small>
                </div>
                <ul>
                    <li class="px-3 py-2 space-x-3 hover:bg-gray-800 text-gray-300 hover:text-white rounded-lg">
                        <i class="fi fi-rs-home "></i>
                        <a href="#" class=" text-lg">Dashboard</a>
                    </li>
                    <li class="px-3 py-2 space-x-3 hover:bg-gray-800 text-gray-300 hover:text-white rounded-lg">
                        <i class="fi fi-rs-chalkboard"></i>
                        <a href="#" class=" text-lg">Classroom</a>
                    </li>
                    <li class="px-3 py-2 space-x-3 hover:bg-gray-800 text-gray-300 hover:text-white rounded-lg">
                        <i class="fi fi-rr-calendar-lines"></i>
                        <a href="#" class=" text-lg">Timetable</a>
                    </li>
                    <li class="px-3 py-2 space-x-3 hover:bg-gray-800 text-gray-300 hover:text-white rounded-lg">
                        <i class="fi fi-rr-dictionary-open"></i>
                        <a href="#" class=" text-lg">Dictionary</a>
                    </li>
                    <li class="px-3 py-2 space-x-3 hover:bg-gray-800 text-gray-300 hover:text-white rounded-lg">
                        <i class="fi fi-sr-ai-technology"></i>
                        <a href="#" class=" text-lg">Gemini AI</a>
                    </li>
                </ul>
            </div>
        </div>
    </div> --}}


<div class="flex min-h-screen">

    <x-sidebar></x-sidebar>
    

        <main class="flex-1 p-6">
            <div>
                {{$slot}}
            </div>
        </main>
</div>

  </body>
</html>
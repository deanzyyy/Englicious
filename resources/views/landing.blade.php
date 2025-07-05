<!doctype html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    
    <!-- icon -->
     <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    {{-- font google--}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">



    <style>
        body {
          font-family: 'Poppins', sans-serif;
        }

        .card1.active {
          width: 800px !important;
          justify-content: center !important;
        }

        .card1.active .number-circle {
          display: none;
        }

        .card1.active video {
          display: block !important;
        }

        .card1.active .hover-text {
          display: block !important;
        }
      </style>
      


    @vite('resources/css/app.css')
    
</head>
<body class="bg-[#101014] items-center min-h-screen">
  
    <header class=" px-6 py-4 flex items-center justify-between">
        <!-- Judul Aplikasi -->
        <div class="text-3xl font-semibold text-pink-500">
          Englicious
        </div>
      
        <!-- Menu Tengah -->
        <nav class="hidden md:flex space-x-8 mx-auto">
          <a href="#" class="text-white hover:text-pink-600 font-integral">Home</a>
          <a href="#" class="text-white hover:text-pink-600">Features</a>
          <a href="#" class="text-white hover:text-pink-600">Pricing</a>
          <a href="#" class="text-white hover:text-pink-600">About</a>
        </nav>
      
        <!-- Tombol Login dan Register -->
        <div class="space-x-4">
          <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-pink-600 border border-pink-600 rounded-lg hover:text-white hover:bg-pink-500 inline-block">
            Login
          </a>
          <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-pink-500 to-orange-500 rounded-lg hover:bg-pink-600 inline-block">
            Register
          </a>
        </div>
      </header>

      <div class="container my-5 space-x-2">
        <div class="top flex m-2 space-x-25 items-center justify-center">
            <div class="kalimat w-[60%]">
              <h1 class="text-white text-5xl" >MAKE ENGLISH MORE FUN <span class="animate-spin">❁</span  > AND EXCITING,CONNECTING TEACHERS AND STUDENTS. <span>Ф</span></h1>
            </div>
            <div class="kalimat2 w-[20%]">
              <h1 class="text-gray-400 text-sm"><span class="font-bold text-white">Englicious is an interactive</span> learning platform that is very fun for students and teachers, Providing a different experience for learning.</h1>
            </div>
        </div>


        <div class="tengah mx-25 py-2">
          <div class="listcard m-2 flex items-center space-x-5">
            <div class="card1 h-96 w-20 flex flex-col justify-end items-center duration-500 bg-pink-500 rounded-4xl">
                <div class="number-circle items-center h-15 w-15 mb-2 bg-white rounded-full">
                  <h1 class="text-pink-500 text-4xl items-center text-center font-bold pt-3">1</h1>
                </div>
                <video
                  class="relative rounded-4xl duration-300 top-0 left-0 w-[800px] h-96 object-cover hidden z-0"
                    autoplay
                    muted
                    loop
                        >
                    <source src="img/vid1.mp4" type="video/mp4" />
                      Browser Anda tidak mendukung tag video.
                </video>
                <h1 class="hover-text absolute text-white hidden">Friendly Use</h1>
            </div>
            <div class="card1 h-96 w-20 duration-500 flex flex-col justify-end items-center bg-pink-300 rounded-4xl">
                <div class="number-circle items-center h-15 w-15 bg-white mb-2 rounded-full">
                  <h1 class="text-pink-500 text-4xl items-center text-center font-bold pt-3">2</h1>
                </div>
                <video
                  class="relative rounded-4xl duration-300 top-0 left-0 w-[800px] h-96 object-cover hidden z-0"
                    autoplay
                    muted
                    loop
                        >
                    <source src="img/vid2.mp4" type="video/mp4" />
                      Browser Anda tidak mendukung tag video.
                </video>
                <h1 class="hover-text absolute text-white hidden">Interactive Learning</h1>
            </div>
            <div class="card1 h-96 w-20 duration-500 flex flex-col justify-end items-center bg-pink-600 rounded-4xl">
                <div class="number-circle items-center h-15 w-15 bg-white rounded-full mb-2">
                  <h1 class="text-pink-500 text-4xl items-center text-center font-bold pt-3">3</h1>
                </div>
                <video
                  class="relative rounded-4xl duration-300 top-0 left-0 w-[800px] h-96 object-cover hidden z-0"
                    autoplay
                    muted
                    loop
                        >
                    <source src="img/vid1.mp4" type="video/mp4" />
                      Browser Anda tidak mendukung tag video.
                </video>
                <h1 class="hover-text absolute text-white hidden">Engaging Content</h1>
            </div>
            <div class="card1 h-96 w-20 duration-500 flex flex-col justify-end items-center bg-pink-200 rounded-4xl">
                <div class="number-circle items-center h-15 w-15 bg-white rounded-full mb-2">
                  <h1 class="text-pink-500 text-4xl items-center text-center font-bold pt-3">4</h1>
                </div>
                <video
                  class="relative rounded-4xl duration-300 top-0 left-0 w-[800px] h-96 object-cover hidden z-0"
                    autoplay
                    muted
                    loop
                        >
                    <source src="img/vid2.mp4" type="video/mp4" />
                      Browser Anda tidak mendukung tag video.
                </video>
                <h1 class="hover-text absolute text-white hidden">Smart Assessment</h1>
            </div>
            <div class="text">
                <h1 class="text-white text-4xl">HOVER OUR FEATURES HERE.</h1>
                <p class="text-gray-400 text-sm">These are the main features that we offer from the Englicious application.</p>
            </div>
          </div>
        </div>


        <div class="tengah bawah">

        <div class="px-25 flex items-center space-x-10">
          <div>
            <h1 class="text-gray-200 text-lg">6 more features available</h1>
          </div>
          <div>
            <h1 class="text-gray-200 text-lg">suitable for all teachers and students</h1>
          </div>
        </div>

        </div>

        <div class="bawah mx-25 my-10">
            <div>
              <h1 class="text-white text-4xl w-[20%]">Ayo Gunakan Aplikasi ini</h1>
            </div>
        </div>

      </div>
      
    
</body>
</html>
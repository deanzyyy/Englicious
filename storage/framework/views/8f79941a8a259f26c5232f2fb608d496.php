<!doctype html>
<html>
<head>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    
    <!-- icon -->
     <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">



    <style>
        body, html {
          font-family: 'Poppins', sans-serif;
          min-height: 100vh;
          height: auto;
          overflow-x: hidden;
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

       
    @keyframes slide-left {
      0% {
        transform: translateX(100%);
      }
      100% {
        transform: translateX(-100%);
      }
    }

    .slide-animation {
      animation: slide-left 30s linear infinite;
    }
    /* Animasi random pelan untuk lingkaran */
    @keyframes float-random-1 {
      0%   { transform: translate(0, 0) scale(1); }
      25%  { transform: translate(30px, -20px) scale(1.05); }
      50%  { transform: translate(-20px, 30px) scale(0.98); }
      75%  { transform: translate(10px, -10px) scale(1.02); }
      100% { transform: translate(0, 0) scale(1); }
    }
    @keyframes float-random-2 {
      0%   { transform: translate(0, 0) scale(1); }
      20%  { transform: translate(-25px, 15px) scale(1.03); }
      50%  { transform: translate(20px, -30px) scale(1.01); }
      80%  { transform: translate(-10px, 20px) scale(0.97); }
      100% { transform: translate(0, 0) scale(1); }
    }
    @keyframes float-random-3 {
      0%   { transform: translate(0, 0) scale(1); }
      30%  { transform: translate(15px, 25px) scale(1.04); }
      60%  { transform: translate(-30px, -15px) scale(0.96); }
      90%  { transform: translate(20px, 10px) scale(1.01); }
      100% { transform: translate(0, 0) scale(1); }
    }
    .circle-anim-1 {
      animation: float-random-1 18s ease-in-out infinite alternate;
    }
    .circle-anim-2 {
      animation: float-random-2 22s ease-in-out infinite alternate;
    }
    .circle-anim-3 {
      animation: float-random-3 26s ease-in-out infinite alternate;
    }
    /* Animasi turun-naik */
    @keyframes updown {
      0%   { transform: translateY(0); }
      50%  { transform: translateY(-30px); }
      100% { transform: translateY(0); }
    }
    .updown-anim {
      animation: updown 2.8s ease-in-out infinite;
    }
    .updown-anim-slow {
      animation: updown 4.2s ease-in-out infinite;
    }
    /* Dekorasi bubble tambahan */
    .bubble-decor {
      position: absolute;
      border-radius: 9999px;
      opacity: 0.18;
      filter: blur(2px);
      z-index: 1;
    }
    .bubble-decor-1 {
      width: 80px; height: 80px;
      left: 10px; top: 60%;
      background: linear-gradient(135deg, #f472b6, #fbbf24);
      animation: updown 3.5s ease-in-out infinite;
    }
    .bubble-decor-2 {
      width: 60px; height: 60px;
      right: 20px; top: 30%;
      background: linear-gradient(135deg, #fbbf24, #f472b6);
      animation: updown 2.7s ease-in-out infinite reverse;
    }
    .bubble-decor-3 {
      width: 40px; height: 40px;
      left: 60px; bottom: 10%;
      background: linear-gradient(135deg, #f472b6, #fbbf24);
      animation: updown 3.1s ease-in-out infinite;
    }
    .bubble-decor-4 {
      width: 100px; height: 100px;
      right: 60px; bottom: 5%;
      background: linear-gradient(135deg, #fbbf24, #f472b6);
      animation: updown 4.5s ease-in-out infinite reverse;
    }


    @keyframes floatSmooth {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-20px); }
}
  </style>


     
      


    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    
</head>
<body class="bg-[#101014] items-center">
  
<div class="relative z-10 w-full bg-black-900 overflow-hidden">
  <!-- Lingkaran pink -->
  <div class="absolute -top-30 -left-30 w-[500px] h-[500px] bg-pink-500 opacity-30 rounded-full blur-3xl circle-anim-1"></div>

  <!-- Lingkaran orange -->
  <div class="absolute -bottom-30 -right-30 w-[500px] h-[500px] bg-orange-400 opacity-30 rounded-full blur-3xl circle-anim-2"></div>

  <!-- Lingkaran campuran -->
  <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-pink-400 opacity-20 rounded-full blur-[100px] mix-blend-screen circle-anim-3"></div>




    <header class=" px-6 py-4 flex items-center justify-between">
        <!-- Judul Aplikasi -->
        <div class="flex flex-cols z-50 items-center space-x-2">
          <img src="<?php echo e(url('img/EngliciousLogo.png')); ?>" alt="Logo" class="w-15 h-15">
          <h1 class="text-3xl font-semibold text-white">Englicious.</h1>
        </div>
      
        <!-- Menu Tengah -->
        <!-- <nav class="hidden md:flex space-x-8 mx-auto">
          <a href="#" class="text-white hover:text-pink-600 font-integral">Home</a>
          <a href="#" class="text-white hover:text-pink-600">Features</a>
          <a href="#" class="text-white hover:text-pink-600">Pricing</a>
          <a href="#" class="text-white hover:text-pink-600">About</a>
        </nav> -->
      
        <!-- Tombol Login dan Register -->
        <!-- <div class="space-x-4">
          <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 text-sm font-medium text-pink-600 border border-pink-600 rounded-lg hover:text-white hover:bg-pink-500 inline-block">
            Login
          </a>
          <a href="<?php echo e(route('register')); ?>" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-pink-500 to-orange-500 rounded-lg hover:bg-pink-600 inline-block">
            Register
          </a>
        </div> -->
      </header>

     <!-- All Content Container -->
  <div class="All-Container flex justify-center items-start">
    <div class="content items-center m-5">
      
      <!-- Top Text -->
      <div class="Top flex justify-center mb-4">
        <p class="text-gray-200 text-md text-center">
          Easy Learning With <span class="font-bold text-md text-white">Englicious</span>
        </p>
      </div>

      <!-- Middle Section -->
      <div class="mid grid grid-cols-3">

        <!-- Left Space -->
        <div class="left relative">
          <div class="relative w-fit">
            <h1 class="absolute flex justify-center left-80 top-25 -rotate-10 items-center rounded-tl-full rounded-tr-full rounded-bl-full text-white w-50 h-15 bg-orange-500 text-xl p-2 updown-anim">
              EN
            </h1>
            <!-- Dekorasi kiri -->
            <div class="bubble-decor bubble-decor-1"></div>
            <div class="bubble-decor bubble-decor-3"></div>
          </div>
        </div>

        <!-- Middle Content -->
        <div class="tengah flex flex-col items-center justify-center -space-y-18">
          <h1 class="text-white text-[120px] font-semibold text-center">LEARN</h1>
          <h1 class="text-white text-[120px] font-semibold text-center pb-5">ENGLISH</h1>
          <div class="relative h-[130px] flex items-center justify-center overflow-hidden" style="height:130px;">
            <span id="animated-text" class="block text-white text-[120px] font-semibold text-center" style="opacity:1;">ANYWHERE</span>
          </div>
        </div>

        <!-- Right Space + Bubble -->
        <div class="right relative">
          <div class="relative w-fit">
            <h1 class="absolute flex justify-center -right-50 top-60 rotate-10 items-center rounded-tl-full rounded-tr-full rounded-br-full text-white w-50 h-15 bg-pink-500 text-xl p-2 updown-anim-slow">
              Welcome!
            </h1>
            <!-- Dekorasi kanan -->
            <div class="bubble-decor bubble-decor-2"></div>
            <div class="bubble-decor bubble-decor-4"></div>
          </div>
        </div>
      </div>

      <!-- Bottom Buttons -->
      <div class="bottom flex items-center justify-center space-x-5 mt-10">
        <a href="<?php echo e(route('login')); ?>" class="w-36 h-10 px-2 rounded-full text-white bg-gradient-to-r from-pink-500 to-orange-500 flex items-center justify-center text-center">Login</a>
        <a href="<?php echo e(route('register')); ?>" class="w-36 h-10 px-2 rounded-full text-pink-500 border-2 border-pink-500 flex items-center justify-center text-center">Register</a>
      </div>

      
      <!-- Running Logo Section (Trusted by...) -->
      <div class="relative w-screen left-1/2 right-1/2 -translate-x-1/2 flex my-10 mt-25 flex-col items-center py-4 px-0 overflow-hidden"
           style="margin-left:50%;margin-right:50%;transform:translateX(-50%);">
        <!-- Vertical gradient fade background with 80% opacity -->
        <div class="absolute inset-0 z-0 pointer-events-none"
             style="background: linear-gradient(to bottom, rgba(16,16,20,0.8) 0%, rgba(16,16,20,1) 20%, rgba(16,16,20,1) 80%, rgba(16,16,20,0.8) 100%);"></div>
        <p class="text-white opacity-60 hover:text-pink-500 hover:opacity-100 mb-4 font-semibold text-lg pb-2 tracking-wider uppercase z-10">Supported by</p>
        <div class="relative w-full max-w-7xl flex items-center justify-center z-10">
          <!-- Gradient Fade Left (solid black, 80% opacity) -->
          <div class="pointer-events-none absolute left-0 top-0 h-full w-32 z-20"
               style="background: linear-gradient(to right, rgba(16,16,20,0.8) 90%, transparent 100%);"></div>
          <!-- Gradient Fade Right (solid black, 80% opacity) -->
          <div class="pointer-events-none absolute right-0 top-0 h-full w-32 z-20"
               style="background: linear-gradient(to left, rgba(16,16,20,0.8) 90%, transparent 100%);"></div>
          <div class="overflow-hidden w-full">
            <div class="slide-animation flex gap-16 items-center py-2">
              <img src="<?php echo e(url('img/poli.png')); ?>" alt="Logo 1" class="h-15 w-auto grayscale hover:grayscale-0 transition duration-300 drop-shadow-lg hover:scale-110" />
              <img src="<?php echo e(url('img/ti.png')); ?>" alt="Logo 2" class="h-15 w-auto grayscale hover:grayscale-0 transition duration-300 drop-shadow-lg hover:scale-110" />
              <img src="<?php echo e(url('img/raft.png')); ?>" alt="Logo 3" class="h-15 w-auto grayscale hover:grayscale-0 transition duration-300 drop-shadow-lg hover:scale-110" />
              <img src="<?php echo e(url('img/gp.png')); ?>" alt="Logo 4" class="h-15 w-auto grayscale hover:grayscale-0 transition duration-300 drop-shadow-lg hover:scale-110" />
              <!-- Tambahkan lebih banyak logo jika perlu -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class="mx-25 my-15 flex flex-cols justify-center items-center">
      <div class="Title left w-[40%]">
        <h1 class="text-white text-5xl">manage your <span class="font-semibold text-pink-500">materials</span> so they are <span class="bg-gradient-to-r from-pink-500 to-orange-500 p-2 w-15 text-sm rounded-full text-white rotate-30">organized</span> correctly.</h1>
        <p class="text-gray-400 text-sm mt-4">by dividing the 3 materials neatly into 3 categories so that the categories of each material do not mix with each other.</p>
        <button class="text-pink-500 border-2 rounded-full border-pink-500 w-35 h-10 mt-6 hover:bg-gradient-to-r from-pink-500 to-orange-500 animate duration-300 hover:text-white">Try now!</button>
      </div>
      <div class="content right">
          <div>
            <img src="<?php echo e(url('img/tablet.png')); ?>" alt="englicious" class="h-auto w-[800px] -rotate-10">
          </div>
      </div>
    </div>

    <div class="section4 pt-5 relative overflow-visible">

<!-- Pembungkus -->
<div class="pembungkus mx-48 my-10 bg-gradient-to-b from-[#3F0023] via-[#5B0032] to-[#3F0023] 
            flex items-center relative py-10 rounded-lg h-96 overflow-visible">

  <!-- Gambar kiri (keluar dari pembungkus sedikit) -->
  <div class="kiri w-1/2 relative min-h-[400px] overflow-visible">

    <!-- Lingkaran glow blur di tengah -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
                w-[350px] h-[350px] bg-pink-500 rounded-full 
                blur-3xl opacity-40 z-0">
    </div>

    <!-- Gambar laptop besar dan keluar ke kiri -->
    <img src="<?php echo e(url('img/laptop.png')); ?>" 
         alt="pc" 
         class="w-[800px] max-w-none h-auto absolute -left-40 -top-30 z-10" style="animation: floatSmooth 3s ease-in-out infinite;" />
  </div>

  <!-- Teks kanan -->
  <div class="kanan w-1/2 z-10 px-10 pr-25">
    <h1 class="text-pink-500 text-4xl font-semibold leading-relaxed leading-tight mb-0">
      Manage Exercise
      
      <span class="text-4xl text-white">Make things easier for teachers</span>
      
    </h1>
    <p class="text-gray-400">It becomes easier to find exercises and students know what they are doing.</p>
    <h1 class="mt-2 flex justify-center items-center text-center bg-gradient-to-r from-pink-500 to-orange-500 w-35 h-10 rounded-full text-white">Englicious.</h1>
  </div>

</div>
</div>

<!-- Section 5: Features -->
<div class="section5 relative py-20 bg-transparent flex flex-col items-center overflow-visible">
  <h2 class="text-4xl font-bold text-white mb-12 tracking-wide">Features</h2>
  <div class="w-full max-w-5xl flex flex-col md:grid md:grid-cols-2 lg:grid-cols-3 gap-10 items-center justify-center px-4">
    <!-- Classroom Card -->
    <div class="feature-card group bg-gradient-to-b from-[#3F0023] via-[#5B0032] to-[#3F0023] relative rounded-2xl p-1 w-full h-96 flex flex-col items-center shadow-xl transition-transform duration-300 border border-transparent hover:border-pink-500 overflow-visible z-10" data-tilt data-tilt-perspective="1200">
      <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#18182a] rounded-full p-4 shadow-lg border-4 border-[#a21caf] z-20">
        <i class="fa-solid fa-chalkboard-user text-5xl text-pink-400 drop-shadow-lg"></i>
      </div>
      <div class="flex-1"></div>
      <h3 class="text-2xl font-semibold text-white mt-16 mb-2 text-center" data-tilt-layer data-tilt-depth="2.5">Classroom</h3>
      <p class="text-gray-300 text-center px-4 mb-4" data-tilt-layer data-tilt-depth="1.5">Create, manage, and join classrooms easily for collaborative learning.</p>
      <div class="flex-1"></div>
      <span class="text-pink-400 text-xs tracking-widest mb-4" data-tilt-layer data-tilt-depth="3.5">Main Feature</span>
    </div>
    <!-- Materials Card -->
    <div class="feature-card group bg-gradient-to-b from-[#3F0023] via-[#5B0032] to-[#3F0023] relative rounded-2xl p-1 w-full h-96 flex flex-col items-center shadow-xl transition-transform duration-300 border border-transparent hover:border-orange-400 overflow-visible z-10" data-tilt data-tilt-perspective="1200">
      <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#18182a] rounded-full p-4 shadow-lg border-4 border-orange-400 z-20">
        <i class="fa-solid fa-book-open text-5xl text-orange-400 drop-shadow-lg"></i>
      </div>
      <div class="flex-1"></div>
      <h3 class="text-2xl font-semibold text-white mt-16 mb-2 text-center" data-tilt-layer data-tilt-depth="2.5">Materials</h3>
      <p class="text-gray-300 text-center px-4 mb-4" data-tilt-layer data-tilt-depth="1.5">Access and organize learning materials in one place.</p>
      <div class="flex-1"></div>
      <span class="text-orange-300 text-xs tracking-widest mb-4" data-tilt-layer data-tilt-depth="3.5">Main Feature</span>
    </div>
    <!-- Exercise Card -->
    <div class="feature-card group bg-gradient-to-b from-[#3F0023] via-[#5B0032] to-[#3F0023] relative rounded-2xl p-1 w-full h-96 flex flex-col items-center shadow-xl transition-transform duration-300 border border-transparent hover:border-fuchsia-500 overflow-visible z-10" data-tilt data-tilt-perspective="1200">
      <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#18182a] rounded-full p-4 shadow-lg border-4 border-fuchsia-500 z-20">
        <i class="fa-solid fa-pen-ruler text-5xl text-fuchsia-400 drop-shadow-lg"></i>
      </div>
      <div class="flex-1"></div>
      <h3 class="text-2xl font-semibold text-white mt-16 mb-2 text-center" data-tilt-layer data-tilt-depth="2.5">Exercise</h3>
      <p class="text-gray-300 text-center px-4 mb-4" data-tilt-layer data-tilt-depth="1.5">Practice and test your knowledge with interactive exercises.</p>
      <div class="flex-1"></div>
      <span class="text-fuchsia-300 text-xs tracking-widest mb-4" data-tilt-layer data-tilt-depth="3.5">Main Feature</span>
    </div>
    <!-- Games Card -->
    <div class="feature-card group bg-gradient-to-b from-[#3F0023] via-[#5B0032] to-[#3F0023] relative rounded-2xl p-1 w-full h-96 flex flex-col items-center shadow-xl transition-transform duration-300 border border-transparent hover:border-blue-400 overflow-visible z-10" data-tilt data-tilt-perspective="1200">
      <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#18182a] rounded-full p-4 shadow-lg border-4 border-blue-400 z-20">
        <i class="fa-solid fa-gamepad text-5xl text-blue-400 drop-shadow-lg"></i>
      </div>
      <div class="flex-1"></div>
      <h3 class="text-2xl font-semibold text-white mt-16 mb-2 text-center" data-tilt-layer data-tilt-depth="2.5">Games</h3>
      <p class="text-gray-300 text-center px-4 mb-4" data-tilt-layer data-tilt-depth="1.5">Learn with fun and engaging educational games.</p>
      <div class="flex-1"></div>
      <span class="text-blue-300 text-xs tracking-widest mb-4" data-tilt-layer data-tilt-depth="3.5">Main Feature</span>
    </div>
    <!-- Presence Card -->
    <div class="feature-card group bg-gradient-to-b from-[#3F0023] via-[#5B0032] to-[#3F0023] relative rounded-2xl p-1 w-full h-96 flex flex-col items-center shadow-xl transition-transform duration-300 border border-transparent hover:border-green-400 overflow-visible z-10" data-tilt data-tilt-perspective="1200">
      <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#18182a] rounded-full p-4 shadow-lg border-4 border-green-400 z-20">
        <i class="fa-solid fa-user-check text-5xl text-green-400 drop-shadow-lg"></i>
      </div>
      <div class="flex-1"></div>
      <h3 class="text-2xl font-semibold text-white mt-16 mb-2 text-center" data-tilt-layer data-tilt-depth="2.5">Presence</h3>
      <p class="text-gray-300 text-center px-4 mb-4" data-tilt-layer data-tilt-depth="1.5">Track and manage attendance with ease and accuracy.</p>
      <div class="flex-1"></div>
      <span class="text-green-300 text-xs tracking-widest mb-4" data-tilt-layer data-tilt-depth="3.5">Main Feature</span>
    </div>
    <!-- Grade Card -->
    <div class="feature-card group bg-gradient-to-b from-[#3F0023] via-[#5B0032] to-[#3F0023] relative rounded-2xl p-1 w-full h-96 flex flex-col items-center shadow-xl transition-transform duration-300 border border-transparent hover:border-yellow-400 overflow-visible z-10" data-tilt data-tilt-perspective="1200">
      <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#18182a] rounded-full p-4 shadow-lg border-4 border-yellow-400 z-20">
        <i class="fa-solid fa-ranking-star text-5xl text-yellow-300 drop-shadow-lg"></i>
      </div>
      <div class="flex-1"></div>
      <h3 class="text-2xl font-semibold text-white mt-16 mb-2 text-center" data-tilt-layer data-tilt-depth="2.5">Grade</h3>
      <p class="text-gray-300 text-center px-4 mb-4" data-tilt-layer data-tilt-depth="1.5">View, track, and analyze your grades and progress in real time.</p>
      <div class="flex-1"></div>
      <span class="text-yellow-300 text-xs tracking-widest mb-4" data-tilt-layer data-tilt-depth="3.5">Main Feature</span>
    </div>
  </div>
</div>
<!-- End Section 5: Features -->

<!-- Footer -->
<footer class="w-full bg-gradient-to-b from-[#211021] via-[#18182a] to-[#101014] py-10 px-4 mt-20 border-t border-[#2a2a32]">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-8">
    <!-- Logo & Brand -->
    <div class="flex items-center space-x-4 mb-6 md:mb-0">
      <img src="<?php echo e(url('img/EngliciousLogo.png')); ?>" alt="Englicious Logo" class="w-16 h-16 rounded-full shadow-lg">
      <div>
        <h1 class="text-3xl font-bold text-white">Englicious</h1>
        <span class="text-pink-400 font-semibold tracking-widest text-sm">Easy English Learning</span>
      </div>
    </div>
    <!-- Info -->
    <div class="flex-1 flex flex-col md:flex-row justify-between gap-8 text-gray-300">
      <div>
        <h2 class="text-lg font-semibold text-white mb-2">Contact</h2>
        <p>Email: <a href="mailto:info@englicious.com" class="text-pink-400 hover:underline">info@englicious.com</a></p>
        <p>Phone: <a href="tel:+6281234567890" class="hover:underline">+62 812-3456-7890</a></p>
      </div>
      <div>
        <h2 class="text-lg font-semibold text-white mb-2">Address</h2>
        <p>Jl. Pendidikan No. 123<br>Jakarta, Indonesia 12345</p>
      </div>
      <div>
        <h2 class="text-lg font-semibold text-white mb-2">About Englicious</h2>
        <p class="max-w-xs">Englicious is a modern platform for learning English interactively, designed for students and teachers to collaborate, practice, and grow together.</p>
      </div>
    </div>
  </div>
  <div class="mt-10 text-center text-gray-500 text-xs">
    &copy; <?php echo e(date('Y')); ?> Englicious. All rights reserved.
  </div>
</footer>

<!-- Vanilla Tilt.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.8.1/dist/vanilla-tilt.min.js"></script>
<script>
  // Horizontal scroll for features section
  document.addEventListener('DOMContentLoaded', function() {
    const scrollContainer = document.getElementById('features-scroll');
    const btnLeft = document.getElementById('features-scroll-left');
    const btnRight = document.getElementById('features-scroll-right');
    if (scrollContainer && btnLeft && btnRight) {
      btnLeft.addEventListener('click', () => {
        scrollContainer.scrollBy({ left: -350, behavior: 'smooth' });
      });
      btnRight.addEventListener('click', () => {
        scrollContainer.scrollBy({ left: 350, behavior: 'smooth' });
      });
    }
    // VanillaTilt for 3D card effect
    if (window.VanillaTilt) {
      VanillaTilt.init(document.querySelectorAll('.feature-card'), {
        max: 18,
        speed: 400,
        glare: true,
        'max-glare': 0.18,
        scale: 1.08,
        perspective: 900,
        gyroscope: true,
      });
    }
  });
</script>

</body>
</html>
<script>
  // Animasi encrypt/scramble text smooth, per huruf berurutan
  const texts = ["ANYWHERE", "EVERYWHERE", "ANYTIME", "EVERYTIME"];
  const scrambleChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('animated-text');
    let current = 0;
    function smoothScrambleTo(target, scrambleTime = 800, perCharDelay = 40) {
      const len = target.length;
      let revealed = Array(len).fill(false);
      let display = Array(len).fill(' ');
      let charIdx = 0;
      function revealNextChar() {
        if (charIdx >= len) return;
        let scrambleFrame = 0;
        const maxScramble = Math.floor((scrambleTime / len) / perCharDelay);
        const scrambleInterval = setInterval(() => {
          // Acak hanya huruf ke-charIdx, sisanya tetap
          for (let i = 0; i < len; i++) {
            if (revealed[i]) {
              display[i] = target[i];
            } else if (i === charIdx) {
              display[i] = scrambleChars[Math.floor(Math.random() * scrambleChars.length)];
            }
          }
          el.textContent = display.join('');
          scrambleFrame++;
          if (scrambleFrame >= maxScramble) {
            revealed[charIdx] = true;
            display[charIdx] = target[charIdx];
            el.textContent = display.join('');
            clearInterval(scrambleInterval);
            charIdx++;
            setTimeout(revealNextChar, perCharDelay * 2); // jeda antar huruf
          }
        }, perCharDelay);
      }
      revealNextChar();
    }
    setInterval(() => {
      const next = (current + 1) % texts.length;
      smoothScrambleTo(texts[next], 800, 30);
      current = next;
    }, 2000);
  });
</script><?php /**PATH D:\Englicious\Englicious\resources\views/landing.blade.php ENDPATH**/ ?>
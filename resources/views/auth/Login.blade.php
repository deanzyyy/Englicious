<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" /> --}}
    @vite('resources/css/app.css')
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}
</head>
<body>
    <div class="hero bg-base-200 min-h-screen">
        <div class="hero-content flex-col">
          <div class="text-center lg:text-left p-5">
            <h1 class="text-5xl font-bold text-center"></h1>
            @if(session('error'))
            <p style="color:red;">{{ session('error') }}</p>
            @endif
           <form method="POST" action="{{ route('login') }}">
            @csrf
          </div>
          <div class="flex items-center justify-center min-h-screen bg-base-200">
            <div class="card bg-base-100 w-full max-w-md p-6 shadow-2xl">
              <div class="card-body">
                <h2 class="text-center text-lg font-semibold mb-2">Login</h2>
                <p class="text-center text-sm text-gray-400 mb-4">
                  "Live as if you were to die tomorrow. Learn as if you were to live forever."
                </p>
                <fieldset class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" class="input input-bordered w-full" placeholder="Email" required />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" class="input input-bordered w-full" placeholder="Password" required />
                  </div>
                  <div class="flex justify-between text-sm text-gray-500 mt-1">
                    <a class="link link-hover">Forgot password?</a>
                    <a href="{{ route('register') }}" class="link link-hover">Registrasi</a>
                  </div>
                  <button class="btn btn-primary w-full mt-4">Login</button>
                </fieldset>
              </div>
            </div>
          </div>
          
        </div>
      </div>    
</body>
</html>
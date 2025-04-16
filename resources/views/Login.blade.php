<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    {{-- <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" /> --}}
    @vite('resources/css/app.css')
</head>
<body>
    <div class="hero bg-base-200 min-h-screen">
        <div class="hero-content flex-col">
          <div class="text-center lg:text-left p-5">
            <h1 class="text-5xl font-bold text-center">Login now!</h1>
            <p class="py-6 text-center max-w-[500px]">
              Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
              quasi. In deleniti eaque aut repudiandae et a id nisi.
            </p>
          </div>
          <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
            <div class="card-body">
              <fieldset class="fieldset">
                <label class="fieldset-label">Email</label>
                <input type="email" class="input" placeholder="Email" />
                <label class="fieldset-label">Password</label>
                <input type="password" class="input" placeholder="Password" />
                <div><a class="link link-hover">Forgot password?</a></div>
                <button class="btn btn-neutral mt-4">Login</button>
              </fieldset>
            </div>
          </div>
        </div>
      </div>    
</body>
</html>
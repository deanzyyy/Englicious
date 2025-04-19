<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
  <title>Form Registrasi</title>
  @vite('resources/css/app.css')
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center">
  <div class="card w-full max-w-md shadow-xl bg-base-100">
    <div class="card-body">
      <h2 class="text-3xl font-bold text-center mb-6">Form Registrasi</h2>

      <form action="/submit-registrasi" method="POST" class="space-y-4">
        @csrf

        <div>
          <label for="nama" class="block text-sm font-medium mb-1">Nama</label>
          <input type="text" id="nama" name="nama" class="input input-bordered w-full" required />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium mb-1">Email</label>
          <input type="email" id="email" name="email" class="input input-bordered w-full" required />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium mb-1">Password</label>
          <input type="password" id="password" name="password" class="input input-bordered w-full" required />
        </div>

        <div>
          <label for="konfirmasi_password" class="block text-sm font-medium mb-1">Konfirmasi Password</label>
          <input type="password" id="konfirmasi_password" name="konfirmasi_password" class="input input-bordered w-full" required />
        </div>

        <button type="submit" class="btn btn-primary w-full mt-2">Daftar</button>
      </form>

      <p class="mt-4 text-sm text-center">
        Sudah punya akun? <a href="/login" class="link link-primary">Login di sini</a>
      </p>
    </div>
  </div>
</body>
</html>
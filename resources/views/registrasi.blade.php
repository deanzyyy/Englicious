<!-- resources/views/auth/registrasi.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Registrasi</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@3.8.1/dist/full.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-base-200 min-h-screen flex items-center justify-center">
  <div class="container mx-auto px-4">
    <div class="flex justify-center">
      <div class="w-full max-w-md">
        <div class="card bg-base-100 shadow-xl">
          <div class="card-body text-start">
            <h2 class="text-2xl font-semibold text-center mb-2">Form Registrasi</h2>
            <p class="text-sm text-center text-gray-400 mb-6">
              Silakan isi formulir berikut untuk registrasi aplikasi
            </p>

            <!-- Tampilkan Error -->
            @if ($errors->any())
              <div class="mb-4 text-red-500 text-sm">
                <ul class="list-disc pl-5">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('registrasi.submit') }}" method="POST" class="space-y-4 text-sm">
              @csrf
              
              <div class="form-control">
                <label class="label">
                  <span class="label-text">Nama Lengkap</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap" class="input input-bordered h-10 @error('name') input-error @enderror" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text">Email Address</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Alamat email" class="input input-bordered h-10 @error('email') input-error @enderror" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text">Password</span>
                </label>
                <input type="password" name="password" placeholder="Kata sandi" class="input input-bordered h-10 @error('password') input-error @enderror" required />
              </div>

              <div class="form-control">
                <label class="label">
                  <span class="label-text">Konfirmasi Password</span>
                </label>
                <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" class="input input-bordered h-10" required />
              </div>

              <button type="submit" class="btn btn-primary w-full h-10 text-sm mt-2">Submit Registrasi</button>
            </form>

            <div class="mt-4 text-sm text-center">
              Sudah punya akun?
              <a href="{{ route('login') }}" class="link link-primary">Login di sini</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelector("form").addEventListener("submit", function (e) {
      const password = document.querySelector('input[name="password"]').value;
      const confirm = document.querySelector('input[name="password_confirmation"]').value;

      if (password !== confirm) {
        e.preventDefault();
        alert("Kata sandi tidak valid. Harap pastikan kedua kata sandi cocok.");
      }
    });
  </script>  
</body>
</html>

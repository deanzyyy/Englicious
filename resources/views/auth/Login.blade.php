<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.0/dist/full.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center">
    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="text-5xl mb-2">🎓</div>
                <h2 class="text-2xl font-bold">Login</h2>
                <p class="text-sm text-gray-500">Silahkan Masukkan email dan password anda</p>
            </div>

            @if(session('error'))
                <p class="text-red-500 text-sm text-center mb-4">{{ session('error') }}</p>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Tabs Role -->
                <div class="tabs flex justify-center mb-4">
                    <input type="radio" name="role" value="siswa" id="siswa" class="tab" checked />
                    <label for="siswa" class="tab tab-bordered">Siswa</label>

                    <input type="radio" name="role" value="guru" id="guru" class="tab" />
                    <label for="guru" class="tab tab-bordered">Guru</label>

                    <input type="radio" name="role" value="admin" id="admin" class="tab" />
                    <label for="admin" class="tab tab-bordered">Admin</label>
                </div>

                <!-- Email input -->
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email" placeholder="Masukkan Email Anda" class="input input-bordered" required />
                </div>

                <!-- Password input -->
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password" placeholder="********" class="input input-bordered" required />
                </div>

                <div class="form-control flex flex-row justify-between items-center mb-4">
                    <label class="cursor-pointer label">
                        <input type="checkbox" name="remember" class="checkbox checkbox-sm mr-2" />
                        <span class="label-text text-sm">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-blue-500 hover:underline">Lupa Password?</a>
                </div>

                <button type="submit" id="login-button" class="btn btn-block btn-neutral">Login sebagai Siswa</button>

            </form>

            <p class="text-center text-sm mt-4">Tidak memiliki akun? Hubungi administrator Anda</p>
            <div class="text-center mt-2">
                <a href="{{ route('register') }}" class="btn btn-link text-sm">Registrasi</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
            const role = document.querySelector('input[name="role"]:checked').value;
            if (role !== "siswa") {
                e.preventDefault();
                alert("Role yang dipilih tidak valid. Hanya siswa yang dapat login.");
            }
        });

        const roleRadios = document.querySelectorAll('input[name="role"]');
    const loginBtn = document.getElementById('login-button');

    function updateLoginText() {
        const selected = document.querySelector('input[name="role"]:checked').value;
        loginBtn.textContent = `Login sebagai ${selected.charAt(0).toUpperCase() + selected.slice(1)}`;
    }

    roleRadios.forEach(radio => {
        radio.addEventListener('change', updateLoginText);
    });

    // Set teks awal saat halaman dimuat
    updateLoginText();
    </script>
</body>
</html>

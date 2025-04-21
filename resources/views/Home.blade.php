
<x-layout>
    <div class="ml-64 pt-10 ">
    <div class="Container-judul w-full bg-gray-700 p-10 flex items-center justify-between rounded-lg">
        <div>
            <h1 class="text-gray-400 text-xl">Welcome,</h1>
            <h1 class="text-white text-3xl">Ms Vahradilla Azzahra</h1>
                <p id="current-day" class="text-lg text-gray-400"></p>
                <p id="current-time" class="text-gray-500 text-sm"></p>
        </div>
        <div>
            <button class="text-white text-md hover:bg-blue-500 bg-blue-600 p-3 rounded-md">Add New Classroom</button>
        </div>
    </div>

    <div class="mt-10 garis border-1 border-gray-500"></div>

    {{-- Classroom list --}}
    <div class="container-Room p-5">
        <h1 class="text-gray-400 text-xl ">Your Classroom</h1>
        <div class="cardlist p-5 space-y-6">

            {{-- CARD --}}
            <div class="card sm:max-w-sm lg:max-w-full h-40 text-white bg-gray-900 p-10 rounded-lg hover:border-2 hover:border-blue-500">
                <div class="card-body flex items-center justify-between">
                    <div class="w-auto">
                        <h1 class="card-title mb-2.5 text-3xl font-bold">1A Class</h1>
                        <p class="text-gray-500 mb-2.5">Kelas untuk Siswa 3A, Grammar</p>
                        {{-- <h5 class="p-2 border-1 border-gray-300 text-gray-300 rounded-full text-sm">Vahradilla</h5> --}}
                    </div>
                   
                    <div>
                        <button class="btn bg-blue-600 text-white p-2 w-40  h-15 rounded-lg hover:bg-blue-500">Open Class</button>
                    </div>
                </div>
              </div>

              <div class="card sm:max-w-sm lg:max-w-full h-40 text-white bg-gray-900 p-10 rounded-lg hover:border-2 hover:border-blue-500">
                <div class="card-body flex items-center justify-between">
                    <div class="w-auto">
                        <h1 class="card-title mb-2.5 text-3xl font-bold">3A Class</h1>
                        <p class="text-gray-500 mb-2.5">Kelas untuk Siswa 3A, Grammar</p>
                        {{-- <h5 class="p-2 border-1 border-gray-300 text-gray-300 rounded-full text-sm">Vahradilla</h5> --}}
                    </div>
                   
                    <div>
                        <button class="btn bg-blue-600 text-white p-2 w-40  h-15 rounded-lg hover:bg-blue-500">Open Class</button>
                    </div>
                </div>
              </div>

              <div class="card sm:max-w-sm lg:max-w-full h-40 text-white bg-gray-900 p-10 rounded-lg hover:border-2 hover:border-blue-500">
                <div class="card-body flex items-center justify-between">
                    <div class="w-auto">
                        <h1 class="card-title mb-2.5 text-3xl font-bold">2B Class</h1>
                        <p class="text-gray-500 mb-2.5">Kelas untuk Siswa 3A, Grammar</p>
                        {{-- <h5 class="p-2 border-1 border-gray-300 text-gray-300 rounded-full text-sm">Vahradilla</h5> --}}
                    </div>
                   
                    <div>
                        <button class="btn bg-blue-600 text-white p-2 w-40  h-15 rounded-lg hover:bg-blue-500">Open Class</button>
                    </div>
                </div>
              </div>

              <div class="card sm:max-w-sm lg:max-w-full h-40 text-white bg-gray-900 p-10 rounded-lg hover:border-2 hover:border-blue-500">
                <div class="card-body flex items-center justify-between">
                    <div class="w-auto">
                        <h1 class="card-title mb-2.5 text-3xl font-bold">3C Class</h1>
                        <p class="text-gray-500 mb-2.5">Kelas untuk Siswa 3A, Grammar</p>
                        {{-- <h5 class="p-2 border-1 border-gray-300 text-gray-300 rounded-full text-sm">Vahradilla</h5> --}}
                    </div>
                   
                    <div>
                        <button class="btn bg-blue-600 text-white p-2 w-40  h-15 rounded-lg hover:bg-blue-500">Open Class</button>
                    </div>
                </div>    
              </div>
        </div>
    </div>
</div>
</x-layout>
<script>
    function updateTime() {
        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        const now = new Date();

        // Format Hari & Tanggal
        const day = days[now.getDay()];
        const date = now.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });

        // Format Waktu (HH:MM:SS)
        const time = now.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
        });

        // Update DOM
        document.getElementById("current-day").innerText = `${day}, ${date}`;
        document.getElementById("current-time").innerText = time;
    }

    // Jalankan update waktu setiap detik
    setInterval(updateTime, 1000);
    updateTime();
</script>

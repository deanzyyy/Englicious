<div class="fixed w-64 h-screen bg-gray-900 text-white p-4">
    <!-- Judul Aplikasi -->
    <div class="p-2 border-b border-gray-500">
    <h1 class="text-2xl font-bold pb-2">Englicious</h1>
    </div>

    <!-- Menu Navigasi -->
    <ul class="mt-4 space-y-2">
        <li>
            <p class="text-sm text-gray-500">Menu</p>
        </li> 
        <li>
            <a href="/home" class="{{ request()->is('/home') ? 'bg-gray-700 text-black' : 'hover:bg-gray-700' }} block px-4 py-2 rounded">Home</a>
        </li>
        <li>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Classroom</a>
        </li>

        <!-- Dropdown Materials -->
        <li>
            <details class="group">
                <summary class="flex items-center justify-between px-4 py-2 cursor-pointer rounded hover:bg-gray-700">
                    Materials
                    <span class="transform transition-transform group-open:rotate-180">▼</span>
                </summary>
                <ul class="ml-6 mt-2 space-y-2 hidden group-open:block"> 
                    <li>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">GENERAL</a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">IELTS</a>
                    </li>
                    <li>
                        <details class="group">
                            <summary class="flex items-center justify-between px-4 py-2 cursor-pointer rounded hover:bg-gray-700">
                                TOEFL
                                <span class="transform transition-transform group-open:rotate-180">▼</span>
                            </summary>
                            <ul class="ml-6 mt-2 space-y-2 hidden group-open:block">
                                <li>
                                    <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Reading</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Listening</a>
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </details>
        </li>

        <li>
            <a href="/exercise" class="block px-4 py-2 rounded hover:bg-gray-700">Exercise</a>
        </li>
        <li>
            <a href="/presence" class="block px-4 py-2 rounded hover:bg-gray-700">Presence</a>
        </li>
        <li>
            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Gemini AI</a>
        </li>
    </ul>
</div>

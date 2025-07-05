<div class="sidebar fixed h-screen bg-[#211F27] text-white border-r border-pink-500/20 transition-all duration-300 flex flex-col overflow-visible" 
    x-data="{ 
        isOpen: (localStorage.getItem('sidebarIsOpen') === null ? true : localStorage.getItem('sidebarIsOpen') === 'true'),
        init() {
            this.$watch('isOpen', value => {
                window.handleSidebarToggle(value);
                localStorage.setItem('sidebarIsOpen', value);
            });
        },
        toggle() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('sidebarIsOpen', this.isOpen);
        }
    }" 
    :class="{ 'w-64': isOpen, 'w-16': !isOpen }">
    
    <!-- Toggle Button -->
    <button @click="toggle()" 
        class="absolute -right-3 top-6 bg-pink-500 text-white p-1 rounded-full shadow-lg hover:bg-pink-600 focus:outline-none z-50">
        <svg xmlns="http://www.w3.org/2000/svg" 
            class="h-4 w-4 transition-transform duration-300"
            :class="{ 'rotate-180': !isOpen }"
            viewBox="0 0 24 24" 
            fill="none" 
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <!-- Judul Aplikasi -->
    <div class="p-4 border-b border-gray-500/30">
        <div class="flex items-center" :class="{ 'justify-center': !isOpen }">
            <img src="{{ asset('img/EngliciousLogo.png') }}" alt="Logo" class="w-8 h-8">
            <h1 class="text-xl font-bold ml-3 transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }" style="font-family: 'IntegralCF demo', sans-serif;">Englicious</h1>
        </div>
    </div>

    <!-- Menu Navigasi -->
    <div class="flex-1 py-4 px-3 overflow-y-auto no-scrollbar">
        <p class="text-xs font-medium text-gray-400 mb-2 px-2" x-show="isOpen">Menu</p>
        <ul class="space-y-1">
            <li class="relative">
                <a href="{{ route('home') }}" class="flex items-center px-2 py-2.5 rounded-lg transition-colors {{ request()->is('/') ? 'bg-pink-500/10 text-pink-500' : 'hover:bg-pink-500/10 hover:text-pink-500' }} group">
                    <i class="fi fi-rs-home text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Home</span>
                </a>
            </li>
            @if (Auth::check() && Auth::user()->role === 'student')
            <li class="relative">
                <div x-data="{ open: {{ request()->is('classroom/*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-lg hover:bg-pink-500/10 hover:text-pink-500 {{ request()->is('classroom*') ? 'bg-pink-500/10 text-pink-500' : '' }} relative group">
                        <i class="fi fi-rs-chalkboard text-lg"></i>
                        <span class="flex-1 ml-3 text-left transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Classroom</span>
                        <i class="fi fi-rr-angle-small-down text-lg transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="pl-4 mt-1 space-y-1">
                        @if(Auth::user()->classrooms->isEmpty())
                            <a href="{{ route('classroom.list') }}"
                               class="flex items-center px-2 py-2 text-sm text-gray-400 rounded-lg hover:bg-pink-500/10 hover:text-pink-500">
                                <i class="fi fi-rr-apps text-lg"></i>
                                <span class="ml-3">Join a Classroom</span>
                            </a>
                        @else
                            @foreach(Auth::user()->classrooms()->orderBy('name')->get() as $classroom)
                                <a href="{{ route('classroom.show', $classroom->name) }}"
                                   class="flex items-center px-2 py-2 text-sm text-white rounded-lg hover:bg-pink-500/10 hover:text-pink-500 {{ request()->is('classroom/'.$classroom->name) ? 'bg-pink-500/10 text-pink-500' : '' }}">
                                    <i class="fi fi-rr-apps text-lg"></i>
                                    <span class="ml-3">{{ $classroom->name }}</span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </li>
            @else
            <li class="relative">
                <a href="/classroom" title="Classroom" class="flex items-center px-2 py-2.5 rounded-lg transition-colors {{ request()->is('classroom*') ? 'bg-pink-500/10 text-pink-500' : 'hover:bg-pink-500/10 hover:text-pink-500' }} group">
                    <i class="fi fi-rs-chalkboard text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Classroom</span>
                </a>
            </li>
            @endif
            <li class="relative">
                <a href="{{ route('materials.index') }}" title="Materials" class="flex items-center px-2 py-2.5 rounded-lg transition-colors {{ request()->is('materials*') ? 'bg-pink-500/10 text-pink-500' : 'hover:bg-pink-500/10 hover:text-pink-500' }} group">
                    <i class="fi fi-rr-book-open-reader text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Materials</span>
                </a>
            </li>
            <li class="relative">
                <a href="/exercises" title="Exercise" class="flex items-center px-2 py-2.5 rounded-lg transition-colors {{ request()->is('exercises*') ? 'bg-pink-500/10 text-pink-500' : 'hover:bg-pink-500/10 hover:text-pink-500' }} group">
                    <i class="fi fi-rr-pencil text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Exercise</span>
                </a>
            </li>
            @if(auth()->user()->role !== 'student')
            <li class="relative">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-lg hover:bg-pink-500/10 hover:text-pink-500 {{ request()->routeIs('attendance.*') ? 'bg-pink-500/10 text-pink-500' : '' }} relative group">
                        <i class="fi fi-rr-calendar-check text-lg"></i>
                        <span class="flex-1 ml-3 text-left transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Attendance</span>
                        <i class="fi fi-rr-angle-small-down text-lg transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="pl-4 mt-1 space-y-1">
                        @foreach(\App\Models\Classroom::orderBy('name')->get() as $classroom)
                            <a href="{{ route('attendance.show', $classroom) }}"
                               class="flex items-center px-2 py-2 text-sm text-white rounded-lg hover:bg-pink-500/10 hover:text-pink-500 {{ request()->is('attendance/'.$classroom->id) ? 'bg-pink-500/10 text-pink-500' : '' }}">
                                <i class="fi fi-rr-apps text-lg"></i>
                                <span class="ml-3">{{ $classroom->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
            <li class="relative">
                <a href="{{ route('games.index') }}" title="Games" class="flex items-center px-2 py-2.5 rounded-lg transition-colors {{ request()->is('games*') ? 'bg-pink-500/10 text-pink-500' : 'hover:bg-pink-500/10 hover:text-pink-500' }} group">
                    <i class="fi fi-rr-gamepad text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Games</span>
                </a>
            </li>
            <li class="relative">
                <a href="{{ route('voca.chat') }}" title="Voca AI" class="flex items-center px-2 py-2.5 rounded-lg transition-colors {{ request()->is('voca*') ? 'bg-pink-500/10 text-pink-500' : 'hover:bg-pink-500/10 hover:text-pink-500' }} group">
                    <span class="mr-2" style="min-width:20px;display:flex;align-items:center;">
                        <img src="{{ asset('img/VocaLogoOnly.png') }}" alt="Voca AI" width="20" height="20" class="w-5 h-5">
                    </span>
                    <span class="ml-1 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Voca AI</span>
                </a>
            </li>
            @endif
            <li class="relative">
                <a href="{{ route('dictionary.index') }}" title="Dictionary" class="flex items-center px-2 py-2.5 rounded-lg transition-colors {{ request()->is('dictionary*') ? 'bg-pink-500/10 text-pink-500' : 'hover:bg-pink-500/10 hover:text-pink-500' }} group">
                    <i class="fi fi-rr-book-open-reader text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Dictionary</span>
                </a>
            </li>
            <!-- Admin Only: Teacher Registration -->
            @if(auth()->user()->role === 'admin')
            <li class="relative">
                <a href="{{ route('teacher.registration.simple.form') }}" title="Teacher Registration" class="flex items-center px-2 py-2.5 rounded-lg transition-colors hover:bg-pink-500/10 hover:text-pink-500 {{ request()->routeIs('teacher.registration.simple.form') ? 'bg-pink-500/10 text-pink-500' : 'text-white' }} group">
                    <i class="fi fi-rr-user-add text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Teacher Registration</span>
                </a>
            </li>
            <li class="relative">
                <a href="{{ route('admin.users.index') }}" title="User Management" class="flex items-center px-2 py-2.5 rounded-lg transition-colors hover:bg-pink-500/10 hover:text-pink-500 {{ request()->routeIs('admin.users.index') ? 'bg-pink-500/10 text-pink-500' : 'text-white' }} group">
                    <i class="fi fi-rr-users text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">User Management</span>
                </a>
            </li>
            <li class="relative">
                <a href="{{ route('admin.news.index') }}" title="News Management" class="flex items-center px-2 py-2.5 rounded-lg transition-colors hover:bg-pink-500/10 hover:text-pink-500 {{ request()->routeIs('admin.news.*') ? 'bg-pink-500/10 text-pink-500' : 'text-white' }} group">
                    <i class="fi fi-rr-bullhorn text-lg"></i>
                    <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">News Management</span>
                </a>
            </li>
            @endif
        </ul>
    </div>

    <!-- User Info & Logout -->
    <div class="p-4 border-t border-gray-500/30">
        <div class="flex items-center mb-3" x-show="isOpen">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-2 py-2.5 rounded-lg transition-colors hover:bg-pink-500/10 hover:text-pink-500">
                <i class="fi fi-rr-sign-out-alt text-lg"></i>
                <span class="ml-3 text-sm transition-opacity duration-300" :class="{ 'opacity-0': !isOpen }">Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
  html.light .sidebar,
  html.light .bg-[#101014] {
    background-color: #fff !important;
  }
  html.light .bg-[#2A2A32],
  html.light .bg-[#1E1E24],
  html.light .bg-[#2A2A2A],
  html.light .bg-[#211F27]:not(.sidebar) {
    background-color: #f9a8d4 !important;
  }
  html.light .bg-pink-500\/10,
  html.light .bg-pink-500\/20,
  html.light .bg-pink-500,
  html.light .hover\:bg-pink-500\/10:hover {
    background-color: #f9a8d4 !important;
  }
  html.light .text-white,
  html.light .text-gray-400,
  html.light .text-pink-500,
  html.light .text-gray-600,
  html.light .text-gray-700 {
    color: #111 !important;
  }
  html.light .border-white\/20,
  html.light .border-pink-500\/20,
  html.light .border-gray-700 {
    border-color: #f9a8d4 !important;
  }
  html.light .shadow-lg,
  html.light .shadow {
    box-shadow: 0 1px 3px 0 #0000000d, 0 1px 2px 0 #0000001a !important;
  }
  /* Table header and row backgrounds */
  html.light th,
  html.light thead,
  html.light .table-header,
  html.light .table-row-secondary {
    background-color: #f9a8d4 !important;
    color: #111 !important;
  }
  html.light .rounded-xl,
  html.light .rounded-lg {
    background-color: #fff !important;
  }
</style>

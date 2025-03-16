<header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">{{ $pagina ?? 'Administración' }} ({{Auth::user()->roles->pluck('name')->first()}})</h1>
    <div class="relative">
        <button class="flex items-center space-x-2 focus:outline-none" id="user-menu-button">
            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?s=80&d=mp"
                 class="w-10 h-10 rounded-full"
                 alt="User">
            <span>{{ Auth::user()->name }}</span>
        </button>
        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg hidden">
            <a href="#" class="block px-4 py-2 hover:bg-gray-200">Cerrar sesión</a>
        </div>
    </div>
</header>

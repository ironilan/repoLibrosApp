<nav class="mt-4 space-y-2" id="sidebar" onmouseleave="hideAllSubmenus()">
    <div>
        <a href="/dashboard" class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-home"></i>
            <span class="hidden group-hover:block">Dashboard</span>
        </a>
    </div>
    <div>
        <a href="#" onclick="toggleSubmenu(this)"
            class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-folder"></i>
            <span class="hidden group-hover:block">Gestión</span>
        </a>
        <div class="submenu hidden pl-6 space-y-1">

            <a href="/dashboard/users" class="block p-1">- Usuarios</a>



            <a href="/dashboard/books" class="block p-1">- Libros</a>

            <a href="/dashboard/folders" class="block p-1">- Carpetas</a>

            <a href="/dashboard/packs" class="block p-1">- Packs</a>

            <a href="/dashboard/tipos" class="block p-1">- Tipos</a>

            <a href="/dashboard/resources" class="block p-1">- Recursos</a>
        </div>
    </div>
    <div>
        <a href="#" onclick="toggleSubmenu(this)"
            class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fa-solid fa-chalkboard-user"></i>
            <span class="hidden group-hover:block">Profesores</span>
        </a>
        <div class="submenu hidden pl-6 space-y-1">
            <a href="/dashboard/libros/profesores" class="block p-1">- Libros</a>
            <a href="/dashboard/packs/profesores" class="block p-1">- Packs</a>
        </div>
    </div>
    <div>
        <a href="#" onclick="toggleSubmenu(this)"
            class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fa-solid fa-book"></i>
            <span class="hidden group-hover:block">Libros</span>
        </a>
        <div class="submenu hidden pl-6 space-y-1">
            <a href="/dashboard/carpetas/libros" class="block p-1">- Recursos</a>
        </div>
    </div>
    {{-- <div>
        <a href="/dashboard/reportes"
            class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-file"></i>
            <span class="hidden group-hover:block">Reportes</span>
        </a>
    </div> --}}
    <div>
        <a href="/dashboard/configuracion"
            class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-cog"></i>
            <span class="hidden group-hover:block">Configuración</span>
        </a>
    </div>
</nav>

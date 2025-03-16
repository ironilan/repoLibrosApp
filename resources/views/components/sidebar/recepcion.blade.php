<nav class="mt-4 space-y-2">
    <div>
        <a href="/dashboard" class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-home"></i>
            <span class="hidden group-hover:block">Dashboard</span>
        </a>
    </div>

    <div>
        <a href="#"  class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-dollar"></i>
            <span class="hidden group-hover:block">Ventas</span>
        </a>
    </div>
    <div>
        <a href="/dashboard/pacientes"  class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-user"></i>
            <span class="hidden group-hover:block">Pacientes</span>
        </a>
    </div>
    <div>
        <a href="#" onclick="toggleSubmenu(this)"  class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-calendar"></i>
            <span class="hidden group-hover:block">Citas</span>
        </a>
        <div class="hidden pl-6 space-y-1">
            <a href="/dashboard/citas" class="block p-1">- Lista</a>
            <a href="/dashboard/citas/reservar" class="block p-1">- Nueva</a>
        </div>
    </div>
    <div>
        <a href="#" onclick="toggleSubmenu(this)" class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-file"></i>
            <span class="hidden group-hover:block">Reportes</span>
        </a>
        <div class="hidden pl-6 space-y-1">
            <a href="#" class="block p-1">- Ventas</a>
            <a href="#" class="block p-1">- Citas</a>
        </div>
    </div>

</nav>

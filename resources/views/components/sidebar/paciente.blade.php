<nav class="mt-4 space-y-2">
    <div>
        <a href="/dashboard" class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-home"></i>
            <span class="hidden group-hover:block">Dashboard</span>
        </a>
    </div>

    <div>
        <a href="#"  class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-box"></i>
            <span class="hidden group-hover:block">Productos</span>
        </a>
    </div>
    <div>
        <a href="#" onclick="toggleSubmenu(this)"  class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-calendar"></i>
            <span class="hidden group-hover:block">Citas</span>
        </a>
        <div class="hidden pl-6 space-y-1">
            <a href="/dashboard/paciente/citas" class="block p-1">- Lista</a>
            <a href="/dashboard/paciente/citas/nueva" class="block p-1">- Nueva</a>
        </div>
    </div>

</nav>

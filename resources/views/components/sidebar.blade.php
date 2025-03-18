@php
    $config = App\Models\Config::get()->first();
@endphp

<aside
    class="group relative w-16 hover:w-64 transition-all duration-300 bg-gray-900 text-white flex flex-col p-4 shadow-lg">
    <!-- Contenedor del logo con fondo blanco y borde redondeado -->
    <div class="flex items-center justify-center py-4  rounded-xl p-2">
        <img src="/storage/{{$config->logo}}"
            class="w-10 h-10 transition-opacity duration-300 group-hover:opacity-0 absolute rounded-[20px] bg-white"
            alt="Logo">
        <img src="/storage/{{$config->logo_horizontal}}"
            class="w-40 h-20 opacity-0 transition-opacity duration-300 group-hover:opacity-100 rounded-[20px] bg-white"
            alt="Logotipo">
    </div>

    <!-- Menú de navegación -->

    @role('Administrador')
        @include('components.sidebar.admin')
    @endrole

    @role('Profesor')
        @include('components.sidebar.profesor')
    @endrole


    <!-- Botón de logout -->
    <div class="mt-auto">
        <a href="/logout"
            class="flex items-center space-x-4 p-2 rounded-lg hover:bg-red-600 text-red-600 hover:text-white">
            <i class="fas fa-power-off"></i>
            <span class="hidden group-hover:block">Logout</span>
        </a>
    </div>
</aside>

<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <span class="font-semibold">Reportes</span>
    </nav>
    <div class="mb-4 flex justify-between">


    </div>
    <div class="p-6">
        <!-- Rango de Fechas -->
        <div class="flex justify-center mb-4">
            <div class="flex flex-wrap md:flex-nowrap items-center justify-center   w-full max-w-3xl">
                <div class="w-1/3 md:w-1/2 mr-2">
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                    <input type="date" id="fecha_inicio" class="mt-1 p-3 border rounded-md w-full">
                </div>
                <div class="w-1/3 md:w-1/2">
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                    <input type="date" id="fecha_fin" class="mt-1 p-3 border rounded-md w-full">
                </div>
            </div>
        </div>



        <!-- Cards de Reportes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card de Reporte de Libros -->
            <a href="/dashboard/reportes/libros"
                class="block bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-book text-blue-500 text-3xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Reporte de Libros</h3>
                        <p class="text-sm text-gray-500">Ver reportes detallados de libros.</p>
                    </div>
                </div>
            </a>

            <!-- Card de Reporte de Profesores -->
            <a href="/dashboard/reportes/profesores"
                class="block bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-chalkboard-teacher text-green-500 text-3xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Reporte de Profesores</h3>
                        <p class="text-sm text-gray-500">Ver informes de profesores y asignaciones.</p>
                    </div>
                </div>
            </a>

            <!-- Card de Reporte de Recursos -->
            <a href="/dashboard/reportes/recursos"
                class="block bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition transform hover:scale-105">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-folder-open text-red-500 text-3xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Reporte de Recursos</h3>
                        <p class="text-sm text-gray-500">Consultar reportes de materiales y recursos.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>


</x-app-layout>

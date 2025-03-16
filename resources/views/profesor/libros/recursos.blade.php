<x-app-layout>

    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> /
        <a href="/dashboard/profesor/libros" class="hover:underline">Libros</a> /
        <span class="font-semibold">Recursos</span>
    </nav>

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200 text-center">
        <!-- Portada del libro -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-6">
            <img src="/storage/{{ $book->cover_image }}" alt="{{ $book->title }}"
                class="w-48 h-64 object-cover rounded-md shadow">

            <div class="text-center md:text-left">
                <h1 class="text-2xl font-semibold text-gray-800">{{ $book->title }}</h1>
                <p class="text-gray-600">Autor: {{ $book->autor }}</p>
            </div>
        </div>

        <!-- Listado de Carpetas y Recursos -->
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Recursos Disponibles</h2>

            @if (count($folders) > 0)

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($folders as $folder)

                        <div class="bg-gray-100 p-4 rounded-lg shadow">
                            <!-- Nombre de la Carpeta -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $folder->name }}</h3>

                            <!-- Listado de Recursos dentro de la Carpeta -->
                            @if (count($folder->resources) > 0)
                                <ul class="space-y-2">
                                    @foreach ($folder->resources as $resource)
                                        <li class="flex justify-between items-center bg-white p-2 rounded-lg shadow">
                                            <span class="text-gray-700 text-sm truncate">{{ $resource->name }}</span>
                                            <a href="/dashboard/profesor/descargar-archivo?drive_link={{ $resource->drive_link }}" target="_blank"
                                                class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold hover:bg-blue-500 transition">
                                                Descargar
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 text-sm">No hay recursos en esta carpeta.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No hay carpetas disponibles para este libro.</p>
            @endif
        </div>
    </div>

</x-app-layout>

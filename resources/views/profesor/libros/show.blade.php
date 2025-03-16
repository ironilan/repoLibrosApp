<x-app-layout>

    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> /
        <a href="/dashboard/profesor/libros" class="hover:underline">Libros</a> /
        <span class="font-semibold">{{ $libro->title }}</span>
    </nav>

    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-[var(--primary-color)] mb-6 text-center">{{ $libro->title }}</h1>

        <!-- Botones para seleccionar versión -->
        <div class="flex justify-center gap-4 mb-6">
            <button id="btn-profesor" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Versión
                Profesor</button>
            <button id="btn-alumno" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Versión
                Alumno</button>
        </div>

        <!-- Contenedor del Iframe -->
        <div class="w-full h-[600px] border rounded-lg shadow-lg bg-gray-100">
            <iframe id="book-iframe" src="{{ $libro->libro_profe }}" class="w-full h-full rounded-lg border-none"
                allowfullscreen></iframe>
        </div>

    </div>

    <!-- jQuery (Debe ir antes de FlipBook) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            let currentVersion = "profesor"; // Inicialmente en versión profesor

            // Al hacer clic en el botón "Versión Profesor"
            $("#btn-profesor").click(function() {
                if (currentVersion !== "profesor") {
                    $("#book-iframe").attr("src", "{{ $libro->libro_profe }}");
                    currentVersion = "profesor"; // Actualizamos la versión actual
                }
            });

            // Al hacer clic en el botón "Versión Alumno"
            $("#btn-alumno").click(function() {
                if (currentVersion !== "alumno") {
                    $("#book-iframe").attr("src", "{{ $libro->libro_alumno }}");
                    currentVersion = "alumno"; // Actualizamos la versión actual
                }
            });
        });
    </script>

</x-app-layout>

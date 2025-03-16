<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        function toggleSubmenu(element) {
            element.nextElementSibling.classList.toggle('hidden');
        }

        function hideAllSubmenus() {
            document.querySelectorAll('.submenu').forEach(submenu => {
                submenu.classList.add("hidden");
            });
        }
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="flex-1  overflow-auto">
            <!-- Header -->
            @include('components.header')

            <!-- Table Section -->
            <div class="p-6">
                {{ $slot }}
            </div>

        </main>
    </div>
    <footer class="bg-gray-900 text-white text-center p-4 mt-auto">
        CEIL {{ date('Y') }} Todos los derechos reservados
    </footer>
</body>

</html>

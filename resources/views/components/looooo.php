<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{Storage::url('images/favicon.png')}}">
    <title>Admin Panel</title>
    <script>
        function toggleSubmenu(element) {
            element.nextElementSibling.classList.toggle('hidden');
        }
    </script>
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
                {{$slot}}
            </div>

        </main>
    </div>
    <footer class="bg-gray-900 text-white text-center p-4 mt-auto">
        CEIL {{date('Y')}} Todos los derechos reservados
    </footer>
</body>
</html>

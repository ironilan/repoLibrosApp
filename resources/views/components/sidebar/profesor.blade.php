<nav class="mt-4 space-y-2">
    @php
        if (Session::has('idBookShow')) {
            $idBook = session('idBookShow');
            $tipos = DB::table('folder_tipo_books')
    ->join('tipos', 'folder_tipo_books.tipo_id', '=', 'tipos.id') // Unimos con la tabla tipos
    ->select('tipos.id as id', 'tipos.name') // Seleccionamos ID y nombre del tipo
    ->where('folder_tipo_books.book_id', $idBook) // Filtramos por el libro
    ->groupBy('tipos.id', 'tipos.name') // Agrupamos por ID y nombre del tipo para evitar duplicados
    ->get();
        }
    @endphp
    <div>
        <a href="/dashboard" class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-home"></i>
            <span class="hidden group-hover:block">Dashboard</span>
        </a>
    </div>

    <div>
        <a href="/dashboard/profesor/libros" onclick="toggleSubmenu(this)"
            class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-folder"></i>
            <span class="hidden group-hover:block">Libros</span>
        </a>
    </div>

    <div>
        <a href="/dashboard/profesor/mis-libros" class="flex items-center space-x-4 p-2 rounded-lg hover:bg-gray-700">
            <i class="fas fa-book"></i>
            <span class="hidden group-hover:block">Mis libros</span>
        </a>
    </div>

    @if (session('idBookShow'))
        <div class="bg-gray-300">
            @foreach ($tipos as $tipo)
                <a href="/dashboard/profesor/tipo/{{ $tipo->id }}/book/{{ $idBook }}"
                    class="flex items-center space-x-4 p-2 rounded-lg">
                    <i class="fas fa-book text-gray-900"></i>
                    <span class="hidden group-hover:block text-gray-900">{{ $tipo->name }}</span>
                </a>
            @endforeach
        </div>
    @endif

</nav>

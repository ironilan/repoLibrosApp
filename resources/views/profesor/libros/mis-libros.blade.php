<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <a href="/dashboard/profesor/libros"
            class="hover:underline">Libros</a> / <span class="font-semibold">Mis Libros</span>
    </nav>
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-[var(--primary-color)] mb-6 text-center">Mis libros</h1>

        <!-- Barra de búsqueda -->
        <div class="mb-6">
            <input type="text" id="search" placeholder="Buscar libros..."
                class="w-full p-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--secondary-color)]">
        </div>

        <!-- Grid de libros con skeleton loading -->
        <div id="book-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Skeleton para carga inicial -->
            <template id="skeleton-template">
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 animate-pulse">
                    <div class="w-full h-48 bg-gray-300 rounded-md mb-4"></div>
                    <div class="h-4 bg-gray-300 rounded w-3/4 mb-3"></div>
                    <div class="h-3 bg-gray-300 rounded w-1/2 mb-3"></div>
                    <div class="h-10 bg-gray-300 rounded w-full"></div>
                </div>
            </template>
        </div>

        <!-- Botón para cargar más libros -->
        <div class="text-center mt-6">
            <button id="load-more"
                class="px-6 py-2 bg-gray-800 text-white font-semibold rounded-lg shadow-md hover:bg-opacity-90 hidden">
                Cargar más libros
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let page = 1;
            let searchQuery = "";
            let lastPage = false;

            const bookContainer = document.getElementById("book-container");
            const searchInput = document.getElementById("search");
            const loadMoreBtn = document.getElementById("load-more");
            const skeletonTemplate = document.getElementById("skeleton-template").content;

            // Mostrar skeletons antes de cargar datos
            const showSkeletons = (count = 6) => {
                for (let i = 0; i < count; i++) {
                    bookContainer.appendChild(skeletonTemplate.cloneNode(true));
                }
            };

            // Eliminar skeletons después de cargar datos
            const removeSkeletons = () => {
                document.querySelectorAll(".animate-pulse").forEach(el => el.remove());
            };

            // Función para renderizar libros
            const renderBooks = (books) => {
                books.forEach(book => {
                    const bookCard = document.createElement("a");
                    bookCard.href = `/dashboard/profesor/libros/${book.id}`;
                    bookCard.className =
                        "relative block bg-white p-4 rounded-lg shadow-md border border-gray-200 overflow-hidden group";
                    bookCard.innerHTML = `
                        <div class="relative w-full h-[330px] rounded-md overflow-hidden">
                            <img src="/storage/${book.cover_image}" alt="${book.title}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <h2 class="text-white text-xl font-semibold text-center">${book.title}</h2>
                                <p class="text-white text-sm text-center">${book.autor}</p>
                            </div>
                        </div>
                    `;
                    bookContainer.appendChild(bookCard);
                });
            };


            // Función para obtener libros desde la API con paginación y búsqueda
            const fetchBooks = async () => {
                if (lastPage) return; // Evitar hacer más peticiones si estamos en la última página

                showSkeletons(); // Mostrar skeletons antes de la carga

                try {
                    const apiUrl = `/dashboard/profesor/mis-libros/list?page=${page}&search=${searchQuery}`;
                    const response = await axios.get(apiUrl);
                    const books = response.data.data;
                    lastPage = page >= response.data.last_page; // Verificar si es la última página

                    removeSkeletons(); // Quitar skeletons después de cargar los datos

                    if (books.length === 0 && page === 1) {
                        bookContainer.innerHTML =
                            `<p class="text-center text-gray-600">No se encontraron libros.</p>`;
                        loadMoreBtn.classList.add("hidden");
                        return;
                    }

                    renderBooks(books);
                    page++;

                    // Mostrar el botón "Cargar más" solo si hay más páginas disponibles
                    if (!lastPage) {
                        loadMoreBtn.classList.remove("hidden");
                    } else {
                        loadMoreBtn.classList.add("hidden");
                    }
                } catch (error) {
                    console.error("Error cargando libros:", error);
                    removeSkeletons();
                }
            };

            // Evento para la búsqueda en vivo
            searchInput.addEventListener("input", function() {
                page = 1;
                searchQuery = this.value.trim();
                lastPage = false;
                bookContainer.innerHTML = ""; // Limpiar libros previos
                fetchBooks();
            });

            // Evento para cargar más libros
            loadMoreBtn.addEventListener("click", fetchBooks);

            // Primera carga de libros
            fetchBooks();
        });
    </script>
</x-app-layout>

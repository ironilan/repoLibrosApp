<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <span class="font-semibold">Recursos</span>
    </nav>
    <div class="mb-4 flex justify-between">
        <input type="text" id="search" placeholder="Buscar..." class="border p-2 rounded-lg w-1/3">

        <button id="addUserBtn" class="bg-green-500 text-white p-2 rounded-lg flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Agregar</span>
        </button>

    </div>
    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-900 text-white">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Carpeta</th>
                <th class="p-3 text-left">Recurso</th>
                <th class="p-3 text-left">Link</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody id="recursoTable">
            <!-- Contenido cargado dinámicamente -->
        </tbody>
    </table>
    <div class="mt-4 flex justify-between">
        <button id="prevPage" class="bg-gray-300 p-2 rounded-lg" disabled><i class="fa-solid fa-arrow-left"></i>
            Anterior</button>
        <button id="nextPage" class="bg-gray-300 p-2 rounded-lg" disabled>Siguiente <i
                class="fa-solid fa-arrow-right"></i></button>
    </div>


    <div id="recursoModal"
        class="hidden fixed inset-0 flex items-center justify-center rounded-lg bg-opacity-50 bg-gray-900">
        <div class="bg-white w-1/2 rounded-lg opacity-90">
            <div class="bg-white rounded-lg shadow">
                <div class="flex bg-black mb-4 text-white rounded-t-lg p-4">
                    <div class="flex-1 py-2 pl-5 overflow-hidden">
                        <i class="fas fa-user text-xl"></i>
                        <h1 class="inline text-2xl font-semibold leading-none ml-2" id="modalTitle">Formulario de recursos
                        </h1>
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <form method="POST" class="space-y-4" id="recursoForm">
                        <input type="hidden" id="recursoId">

                        <select id="tipo_id" name="tipo"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            <option value="">Seleccione un tipo </option>
                            @foreach ($tipos as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <select id="libro_id" name="libro"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            <option value="">Seleccione un libro</option>
                            @foreach ($libros as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>

                        <select id="folder_id" name="folder"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            <option value="">Seleccione una carpeta</option>
                            @foreach ($folders as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <div>
                            <label for="drive_link" class="block font-medium text-gray-700">Url recurso </label>
                            <textarea name="drive_link" id="drive_link" cols="30" rows="5"
                                class="w-full px-4 py-2 mt-2 text-base border border-gray-300 rounded-lg bg-gray-100 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 ring-blue-400"></textarea>
                        </div>

                        <input type="text" id="name" name="name" placeholder="Nombre"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">




                        <hr class="mt-4">

                        {{-- Botones --}}
                        <div class="flex flex-row-reverse p-3">
                            {{-- Botón Guardar --}}
                            <div class="flex-initial pl-3">
                                <button type="submit"
                                    class="flex items-center px-5 py-2.5 font-medium tracking-wide text-white capitalize bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:bg-gray-900 transition duration-300 transform active:scale-95 ease-in-out">
                                    <i class="fas fa-save pr-2"></i> Guardar
                                </button>
                            </div>

                            {{-- Botón Cancelar --}}
                            <div class="flex-initial">
                                <button type="button" id="closeModal"
                                    class="flex items-center px-5 py-2.5 font-medium tracking-wide text-black capitalize rounded-md hover:bg-red-200 hover:text-red-600 focus:outline-none transition duration-300 transform active:scale-95 ease-in-out">
                                    <i class="fas fa-times pr-2"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentPage = 1;
            let lastPage = 1;
            const searchInput = document.getElementById("search");
            const recursoTable = document.getElementById("recursoTable");
            const prevPageBtn = document.getElementById("prevPage");
            const nextPageBtn = document.getElementById("nextPage");
            const addUserBtn = document.getElementById("addUserBtn");





            function obtenerDatos(page = 1, query = '') {
                fetch(`/dashboard/carpetas/libros/list?page=${page}&search=${query}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Error en la respuesta del servidor");
                        return response.json();
                    })
                    .then(data => {
                        recursoTable.innerHTML = "";

                        if (data.data.length === 0) {
                            recursoTable.innerHTML =
                                `<tr><td colspan="7" class="p-3 text-center">No se encontraron usuarios</td></tr>`;
                            return;
                        }

                        let rows = data.data.map(row => `
                        <tr class="border-b">
                            <td class="p-3">${row.id}</td>

                            <td class="p-3">${row.book} - ${row.tipo} - ${row.folder}</td>
                            <td class="p-3">${row.name}</td>
                            <td class="p-3"><a href="${row.drive_link}">Link</a></td>
                            <td class="p-3 flex space-x-2">

                                <button class="editUser bg-yellow-500 text-white p-2 rounded-lg" data-user='${JSON.stringify(row)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="deleterow bg-red-500 text-white p-2 rounded-lg" data-id="${row.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`).join('');

                        recursoTable.innerHTML = rows;

                        // Actualizar datos de paginación
                        currentPage = data.current_page;
                        lastPage = data.last_page;

                        // Actualizar botones de paginación
                        prevPageBtn.disabled = (currentPage === 1);
                        nextPageBtn.disabled = (currentPage === lastPage);
                    })
                    .catch(error => {
                        console.error("Error al buscar usuarios:", error);
                    });
            }

            // Evento de búsqueda
            if (searchInput) {
                searchInput.addEventListener("keyup", function(event) {
                    event.preventDefault(); // Evita cualquier comportamiento extraño
                    obtenerDatos(1, searchInput.value
                        .trim()); // Reiniciar la paginación en la primera página
                });
            }

            // Eventos de paginación con búsqueda persistente
            prevPageBtn.addEventListener("click", () => {
                if (currentPage > 1) {
                    obtenerDatos(--currentPage, searchInput.value.trim());
                }
            });

            nextPageBtn.addEventListener("click", () => {
                if (currentPage < lastPage) {
                    obtenerDatos(++currentPage, searchInput.value.trim());
                }
            });

            // Cargar la primera página al inicio
            obtenerDatos();





            $("#recursoForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    drive_link: {
                        required: true,
                        minlength: 3
                    },
                    folder_id: {
                        required: true
                    },
                    tipo_id: {
                        required: true
                    },
                    book_id: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "El nombre es obligatorio",
                        minlength: "Debe tener al menos 3 caracteres"
                    },
                    drive_link: {
                        required: "El link es obligatorio",
                        minlength: "Debe tener al menos 3 caracteres"
                    },
                    folder_id: {
                        required: "El folder es obligatorio",
                    },
                    tipo_id: {
                        required: "El tipo es obligatorio",
                    },
                    book_id: {
                        required: "El libro es obligatorio",
                    },

                },
                submitHandler: function(form) {
                    saveUserData();
                }
            });





            // Event listener para abrir el modal de agregar usuario

            if (addUserBtn) {
                addUserBtn.addEventListener("click", function() {
                    showModal("Agregar Usuario");
                });
            }



            // Evento delegado para editar/eliminar usuarios
            document.addEventListener("click", function(e) {
                let target = e.target;

                if (target.closest(".editUser")) {
                    let button = target.closest(".editUser");
                    let user = button.getAttribute("data-user");
                    showModal("Editar Usuario", user);
                }

                if (target.closest(".deleteUser")) {
                    let button = target.closest(".deleteUser");
                    let user = button.getAttribute("data-id");
                    eliminarUser(user);
                }
            });

            document.getElementById("closeModal").addEventListener("click", function() {
                document.getElementById("recursoModal").classList.add("hidden");
            });


            function saveUserData() {
                let recursoId = $("#recursoId").val();
                let userData = {
                    name: $("#name").val(),
                    tipo_id: $("#tipo_id").val(),
                    folder_id: $("#folder_id").val(),
                    book_id: $("#libro_id").val(),
                    drive_link: $("#drive_link").val(),
                };

                let method = recursoId ? 'PUT' : 'POST';
                let url = recursoId ? `/dashboard/carpetas/libros/update/${recursoId}` : '/dashboard/carpetas/libros/crear';

                fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify(userData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) { // Laravel devuelve errores en "errors"
                            let errorMessages = Object.values(data.errors).map(error => error.join('<br>'))
                                .join('<br>');

                            Swal.fire({
                                icon: 'info',
                                title: 'Error de Validación',
                                html: errorMessages,
                                confirmButtonText: 'Entendido'
                            });

                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Usuario guardado correctamente',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                $("#recursoModal").addClass("hidden");
                                obtenerDatos(currentPage);
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un problema al guardar los datos.',
                            confirmButtonText: 'Cerrar'
                        });
                    });
            }

            function eliminarUser(recursoId) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "El usuario será desactivado.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/dashboard/users/${recursoId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: data.error || "No se pudo eliminar el usuario.",
                                        confirmButtonText: "Entendido"
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Eliminado",
                                        text: "El usuario ha sido desactivado.",
                                        confirmButtonText: "OK"
                                    }).then(() => {
                                        obtenerDatos(currentPage);
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Ocurrió un problema al eliminar el usuario.",
                                    confirmButtonText: "Cerrar"
                                });
                            });
                    }
                });
            }



            function escapeHtml(json) {
                return json.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
            }



            function showModal(title, user = null) {
                user = JSON.parse(user);

                document.getElementById("modalTitle").innerText = title;
                document.getElementById("recursoId").value = user ? user.id : "";
                document.getElementById("name").value = user ? user.name : "";
                document.getElementById("libro_id").value = user ? user.book_id : "";
                document.getElementById("tipo_id").value = user ? user.tipo_id : "";
                document.getElementById("folder_id").value = user ? user.folder_id : "";
                document.getElementById("drive_link").value = user ? user.drive_link : "";



                document.getElementById("recursoModal").classList.remove("hidden");
            }


        });
    </script>
</x-app-layout>

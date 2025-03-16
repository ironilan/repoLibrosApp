<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <span class="font-semibold">Usuarios</span>
    </nav>
    <div class="mb-4 flex justify-between">
        <input type="text" id="searchUser" placeholder="Buscar..." class="border p-2 rounded-lg w-1/3">

        <button id="addUserBtn" class="bg-green-500 text-white p-2 rounded-lg flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Agregar</span>
        </button>

    </div>
    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-900 text-white">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">DNI</th>
                <th class="p-3 text-left">Celular</th>
                <th class="p-3 text-left">Rol</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <!-- Contenido cargado dinámicamente -->
        </tbody>
    </table>
    <div class="mt-4 flex justify-between">
        <button id="prevPage" class="bg-gray-300 p-2 rounded-lg" disabled><i class="fa-solid fa-arrow-left"></i>
            Anterior</button>
        <button id="nextPage" class="bg-gray-300 p-2 rounded-lg" disabled>Siguiente <i
                class="fa-solid fa-arrow-right"></i></button>
    </div>


    <div id="userModal"
        class="hidden fixed inset-0 flex items-center justify-center rounded-lg bg-opacity-50 bg-gray-900">
        <div class="bg-white w-1/2 rounded-lg opacity-90">
            <div class="bg-white rounded-lg shadow">
                <div class="flex bg-black mb-4 text-white rounded-t-lg p-4">
                    <div class="flex-1 py-2 pl-5 overflow-hidden">
                        <i class="fas fa-user text-xl"></i>
                        <h1 class="inline text-2xl font-semibold leading-none ml-2" id="modalTitle">Formulario de Usuario
                        </h1>
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <form method="POST" class="space-y-4">
                        <input type="hidden" id="userId">

                        <select id="rol" name="rol" class="border p-3 w-full mb-3 rounded-lg">
                            <option value="">Rol</option>
                            @foreach ($roles as $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>


                        <input type="text" id="name" name="name" placeholder="Nombre"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">



                        <input type="email" id="email" name="email" placeholder="Correo Electrónico"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">



                        <select id="tipo_doc" name="tipo_doc"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            <option value="">Seleccione un tipo de documento</option>
                            <option value="dni">DNI</option>
                            <option value="ce">Carnet de Extranjería</option>
                            <option value="pasaporte">Pasaporte</option>
                        </select>

                        <input type="text" id="num_doc" name="num_doc" placeholder="Número de Documento"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">

                        <input type="text" id="celular" name="celular" placeholder="Celular"
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
            const searchInput = document.getElementById("searchUser");
            const userTable = document.getElementById("userTable");
            const prevPageBtn = document.getElementById("prevPage");
            const nextPageBtn = document.getElementById("nextPage");
            const addUserBtn = document.getElementById("addUserBtn");





            function obtenerDatos(page = 1, query = '') {
                fetch(`/dashboard/users/list?page=${page}&search=${query}`, {
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
                        userTable.innerHTML = "";

                        if (data.data.length === 0) {
                            userTable.innerHTML =
                                `<tr><td colspan="7" class="p-3 text-center">No se encontraron usuarios</td></tr>`;
                            return;
                        }

                        let rows = data.data.map(user => `
                        <tr class="border-b">
                            <td class="p-3">${user.id}</td>
                            <td class="p-3">${user.name}</td>
                            <td class="p-3">${user.email}</td>
                            <td class="p-3">${user.num_doc}</td>
                            <td class="p-3">${user.celular}</td>
                            <td class="p-3">${user.roles}</td>
                            <td class="p-3 flex space-x-2">

                                <button class="editUser bg-yellow-500 text-white p-2 rounded-lg" data-user='${JSON.stringify(user)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="deleteUser bg-red-500 text-white p-2 rounded-lg" data-id="${user.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`).join('');

                        userTable.innerHTML = rows;

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





            $("#userForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    num_doc: {
                        required: true,
                        digits: true,
                        minlength: 8,
                        maxlength: 8
                    },
                    celular: {
                        required: true,
                        digits: true,
                        minlength: 9,
                        maxlength: 9
                    },
                },
                messages: {
                    name: {
                        required: "El nombre es obligatorio",
                        minlength: "Debe tener al menos 3 caracteres"
                    },
                    email: {
                        required: "El correo es obligatorio",
                        email: "Debe ingresar un correo válido"
                    },
                    num_doc: {
                        required: "El DNI es obligatorio",
                        digits: "Debe contener solo números",
                        minlength: "El DNI debe tener 8 dígitos",
                        maxlength: "El DNI debe tener 8 dígitos"
                    },
                    celular: {
                        required: "El teléfono es obligatorio",
                        digits: "Debe contener solo números",
                        minlength: "El teléfono debe tener 9 dígitos",
                        maxlength: "El teléfono debe tener 9 dígitos"
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
                document.getElementById("userModal").classList.add("hidden");
            });


            function saveUserData() {
                let userId = $("#userId").val();
                let userData = {
                    nombre: $("#name").val(),
                    rol: $("#rol").val(),
                    email: $("#email").val(),
                    dni: $("#num_doc").val(),
                    tipo: $("#tipo_doc").val(),
                    celular: $("#celular").val(),
                };

                let method = userId ? 'PUT' : 'POST';
                let url = userId ? `/dashboard/users/${userId}` : '/dashboard/users';

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
                                $("#userModal").addClass("hidden");
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

            function eliminarUser(userId) {
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
                        fetch(`/dashboard/users/${userId}`, {
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
                document.getElementById("userId").value = user ? user.id : "";
                document.getElementById("name").value = user ? user.name : "";
                document.getElementById("email").value = user ? user.email : "";
                document.getElementById("num_doc").value = user ? user.num_doc : "";
                document.getElementById("tipo_doc").value = user ? user.tipo_doc : "";
                document.getElementById("celular").value = user ? user.celular : "";



                document.getElementById("rol").value = user && user.roles && user.roles.length > 0 ? user.roles[0] : "";

                // Agrega las sedes al select2
                if (user && user.sedes) {
                    // Extraemos los IDs de las sedes para asignarlos al select2
                    let sedeIds = user.sedes.map(sede => sede.id);
                    $("select[name='sedes[]']").val(sedeIds).trigger('change');
                } else {
                    // Si no hay sedes, limpiamos el select2
                    $("select[name='sedes[]']").val([]).trigger('change');
                }
                document.getElementById("userModal").classList.remove("hidden");
            }


        });
    </script>
</x-app-layout>

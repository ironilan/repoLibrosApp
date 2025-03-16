<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <span class="font-semibold">Carpetas</span>
    </nav>
    <div class="mb-4 flex justify-between">
        <input type="text" id="searchRow" placeholder="Buscar..." class="border p-2 rounded-lg w-1/3">

        <button id="addRow" class="bg-green-500 text-white p-2 rounded-lg flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Agregar</span>
        </button>

    </div>
    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-900 text-white">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Imagen</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody id="rowTable">
            <!-- Contenido cargado dinámicamente -->
        </tbody>
    </table>
    <div class="mt-4 flex justify-between">
        <button id="prevPage" class="bg-gray-300 p-2 rounded-lg" disabled><i class="fa-solid fa-arrow-left"></i>
            Anterior</button>
        <button id="nextPage" class="bg-gray-300 p-2 rounded-lg" disabled>Siguiente <i
                class="fa-solid fa-arrow-right"></i></button>
    </div>


    <div id="dataModal"
        class="hidden fixed inset-0 flex items-center justify-center rounded-lg bg-opacity-50 bg-gray-900">
        <div class="bg-white w-1/2 rounded-lg opacity-90">
            <div class="bg-white rounded-lg shadow">
                <div class="flex bg-black mb-4 text-white rounded-t-lg p-4">
                    <div class="flex-1 py-2 pl-5 overflow-hidden">
                        <i class="fas fa-user text-xl"></i>
                        <h1 class="inline text-2xl font-semibold leading-none ml-2" id="modalTitle">Formulario de
                            Usuario
                        </h1>
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <form method="POST" class="space-y-4" id="formData" enctype="multipart/form-data">
                        <input type="hidden" id="rowId">

                        <input type="text" id="name" name="name" placeholder="Nombre"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">

                        <div class="grid grid-cols-1 gap-4">
                            <!-- Imagen de Portada -->
                            <div>
                                <label for="imagen">Imagen de Portada (PNG, JPG, WEBP - máx. 1MB)</label>
                                <input type="file" id="imagen" name="imagen"
                                    accept="image/png, image/webp, image/jpg, image/jpeg"
                                    class="w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                                <a id="imagen_link" href="#" target="_blank"
                                    class="text-blue-500 mt-2 block hidden">Ver Imagen</a>
                            </div>
                        </div>



                        <hr class="mt-4">

                        <div class="flex flex-row-reverse p-3">
                            <div class="flex-initial pl-3">
                                <button type="submit"
                                    class="flex items-center px-5 py-2.5 font-medium tracking-wide text-white capitalize bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:bg-gray-900 transition duration-300 transform active:scale-95 ease-in-out">
                                    <i class="fas fa-save pr-2"></i> Guardar
                                </button>
                            </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentPage = 1;
            let lastPage = 1;
            const searchInput = document.getElementById("searchRow");
            const rowTable = document.getElementById("rowTable");
            const prevPageBtn = document.getElementById("prevPage");
            const nextPageBtn = document.getElementById("nextPage");
            const addRow = document.getElementById("addRow");





            function obtenerDatos(page = 1, query = '') {
                fetch(`/dashboard/folders/list?page=${page}&search=${query}`, {
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
                        rowTable.innerHTML = "";

                        if (data.data.length === 0) {
                            rowTable.innerHTML =
                                `<tr><td colspan="7" class="p-3 text-center">No se encontraron usuarios</td></tr>`;
                            return;
                        }

                        let rows = data.data.map(row => `
                        <tr class="border-b">
                            <td class="p-3">${row.id}</td>
                            <td class="p-3">${row.name}</td>
                            <td class="p-3"><img class="h-10" src="${row.imagen}" /></td>
                            <td class="p-3 flex space-x-2">

                                <button class="editRow bg-yellow-500 text-white p-2 rounded-lg" data-user='${JSON.stringify(row)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="deleteRow bg-red-500 text-white p-2 rounded-lg" data-id="${row.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`).join('');

                        rowTable.innerHTML = rows;

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
                    obtenerDatos(1, searchInput.value.trim());
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




            $("#formData").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },

                    imagen: {
                        required: function() {
                            return $("#rowId").val() === "";
                        }
                    },

                },
                messages: {
                    name: {
                        required: "El nombre es obligatorio",
                        minlength: "Debe tener al menos 3 caracteres"
                    },

                    imagen: {
                        required: "Debe subir una imagen de portada si está creando un nuevo libro"
                    },

                },
                submitHandler: function(form) {
                    if (!validateFiles()) {
                        return false; // No envía el formulario si los archivos no son válidos
                    }
                    saveData(); // Llama a la función para guardar los datos
                }
            });


            function validateFiles() {
                let coverImage = document.getElementById("imagen").files[0];

                let allowedImageTypes = ["image/png", "image/webp", "image/jpg", "image/jpeg"];

                let maxImageSize = 1 * 1024 * 1024; // 1MB en bytes

                // Validar imagen de portada si se ha seleccionado
                if (coverImage) {
                    if (!allowedImageTypes.includes(coverImage.type)) {
                        alert("La imagen de portada debe ser PNG, WEBP, JPG o JPEG.");
                        return false;
                    }
                    if (coverImage.size > maxImageSize) {
                        alert("La imagen de portada no debe pesar más de 1MB.");
                        return false;
                    }
                }

                return true;
            }






            // Event listener para abrir el modal de agregar usuario

            if (addRow) {
                addRow.addEventListener("click", function() {
                    showModal("Agregar carpeta");
                });
            }



            // Evento delegado para editar/eliminar carpetas
            document.addEventListener("click", function(e) {
                let target = e.target;

                if (target.closest(".editRow")) {
                    let button = target.closest(".editRow");
                    let user = button.getAttribute("data-user");
                    showModal("Editar carpeta", user);
                }

                if (target.closest(".deleteRow")) {
                    let button = target.closest(".deleteRow");
                    let user = button.getAttribute("data-id");
                    eliminarUser(user);
                }
            });

            document.getElementById("closeModal").addEventListener("click", function() {
                document.getElementById("dataModal").classList.add("hidden");
            });


            function saveData() {
                let rowId = $("#rowId").val();
                let formData = new FormData();

                // Agregar el nombre del pack
                formData.append("name", $("#name").val());



                // Agregar la imagen si el usuario seleccionó una nueva
                let imageFile = $("#imagen")[0].files[0];
                if (imageFile) {
                    formData.append("imagen", imageFile);
                }

                // Definir si es un POST (nuevo) o PUT (editar)
                let method = "POST";
                let url = rowId ? `/dashboard/folders/${rowId}` : "/dashboard/folders";

                if (rowId) {
                    formData.append("_method", "PUT"); // Necesario para actualizar con Laravel
                }

                // Enviar los datos al backend
                fetch(url, {
                        method: method,
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: "success",
                            title: "Éxito",
                            text: "carpeta guardado correctamente",
                            confirmButtonText: "OK"
                        }).then(() => {
                            $("#rowModal").addClass("hidden");
                            obtenerDatos();
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Ocurrió un problema al guardar los datos.",
                            confirmButtonText: "Cerrar"
                        });
                    });
            }

            function eliminarUser(rowId) {
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
                        fetch(`/dashboard/folders/${rowId}`, {
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



            function showModal(title, row = null) {
                let data = row ? JSON.parse(row) : null;

                $("#modalTitle").text(title);
                $("#rowId").val(data ? data.id : "");
                $("#imagen").val("");
                $("#name").val(data ? data.name : "");

                $("#dataModal").removeClass("hidden");
            }



            // Función para actualizar la vista previa del archivo
            function updateFilePreview(previewId, linkId, fileUrl) {
                let previewDiv = document.getElementById(previewId);
                let fileLink = document.getElementById(linkId);

                if (fileUrl) {
                    fileLink.href = fileUrl;
                    fileLink.style.display = "block";
                } else {
                    fileLink.style.display = "none";
                }
            }



        });
    </script>
</x-app-layout>

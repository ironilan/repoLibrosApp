<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <span class="font-semibold">Pack</span>
    </nav>
    <div class="mb-4 flex justify-between">
        <input type="text" id="searchData" placeholder="Buscar..." class="border p-2 rounded-lg w-1/3">

        <button id="addButton" class="bg-green-500 text-white p-2 rounded-lg flex items-center space-x-2">
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


    <div id="rowModal"
        class="hidden fixed inset-0 flex items-center justify-center rounded-lg bg-opacity-50 bg-gray-900">
        <div class="bg-white w-1/2 rounded-lg opacity-90">
            <div class="bg-white rounded-lg shadow">
                <div class="flex bg-black mb-4 text-white rounded-t-lg p-4">
                    <div class="flex-1 py-2 pl-5 overflow-hidden">
                        <i class="fas fa-user text-xl"></i>
                        <h1 class="inline text-2xl font-semibold leading-none ml-2" id="modalTitle">Formulario de libros
                        </h1>
                    </div>
                </div>

                <div class="px-5 pb-5">

                    <form method="POST" class="space-y-4" id="formDatos" enctype="multipart/form-data">
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

                        <!-- Select2 para agregar libros -->
                        <div>
                            <label for="books">Seleccionar Libros</label>
                            <select id="books" name="books[]" multiple="multiple"
                                class="w-full px-4 py-2 mt-2 border rounded-lg bg-gray-200">
                            </select>
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


    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 45px !important;
            /* Ajusta la altura del select */
            font-size: 16px !important;
            /* Tamaño del texto */
            padding: 8px !important;
        }

        .select2-container .select2-selection--single {
            height: 45px !important;
            /* Ajusta la altura del select */
            font-size: 16px !important;
            /* Tamaño del texto */
            padding: 8px !important;
        }

        .select2-dropdown {
            font-size: 16px !important;
            /* Aumenta el tamaño del texto en la lista */
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentPage = 1;
            let lastPage = 1;
            const searchInput = document.getElementById("searchData");
            const rowTable = document.getElementById("rowTable");
            const prevPageBtn = document.getElementById("prevPage");
            const nextPageBtn = document.getElementById("nextPage");
            const addButton = document.getElementById("addButton");


            $("#books").select2({
                width: '100%',
                placeholder: "Selecciona los libros",
                allowClear: true,
                ajax: {
                    url: "/dashboard/books/list",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term, // Cambia 'term' por 'search'
                            page: params.page || 1 // Pagina opcional si la API lo soporta
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.map(book => ({
                                id: book.id,
                                text: book.title
                            }))
                        };
                    }
                }
            });

            function obtenerDatos(page = 1, query = '') {
                fetch(`/dashboard/packs/list?page=${page}&search=${query}`, {
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
                        console.error("Error al buscar packs:", error);
                    });
            }

            // Evento de búsqueda
            if (searchInput) {
                searchInput.addEventListener("keyup", function(event) {
                    event.preventDefault();

                    let searchTerm = searchInput.value.trim();

                    if (searchTerm.length >= 3) {
                        obtenerDatos(1, searchTerm);
                    } else if (searchTerm.length === 0) {
                        obtenerDatos(1, "");
                    }
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


            $("#formDatos").validate({
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

            if (addButton) {
                addButton.addEventListener("click", function() {
                    showModal("Agregar Pack");
                });
            }



            // Evento delegado para editar/eliminar usuarios
            document.addEventListener("click", function(e) {
                let target = e.target;

                if (target.closest(".editRow")) {
                    let button = target.closest(".editRow");
                    let user = button.getAttribute("data-user");
                    showModal("Editar pack", user);
                }

                if (target.closest(".deleteRow")) {
                    let button = target.closest(".deleteRow");
                    let user = button.getAttribute("data-id");
                    eliminarRow(user);
                }
            });

            document.getElementById("closeModal").addEventListener("click", function() {
                document.getElementById("rowModal").classList.add("hidden");
            });

            function saveData() {
                let rowId = $("#rowId").val();
                let formData = new FormData();

                // Agregar el nombre del pack
                formData.append("name", $("#name").val());

                // Obtener los libros seleccionados
                let selectedBooks = $("#books").val();
                if (selectedBooks) {
                    selectedBooks.forEach(bookId => formData.append("books[]", bookId));
                }

                // Agregar la imagen si el usuario seleccionó una nueva
                let imageFile = $("#imagen")[0].files[0];
                if (imageFile) {
                    formData.append("imagen", imageFile);
                }

                // Definir si es un POST (nuevo) o PUT (editar)
                let method = "POST";
                let url = rowId ? `/dashboard/packs/${rowId}` : "/dashboard/packs";

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
                            text: "Pack guardado correctamente",
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


            function eliminarRow(rowId) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "El registro será desactivado.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/dashboard/packs/${rowId}`, {
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

                // Resetear Select2 antes de asignar valores
                $("#books").val(null).trigger("change");

                if (data && data.books && Array.isArray(data.books)) {
                    let selectedBooks = data.books.map(book => book.id);

                    // Esperar a que Select2 cargue los libros y luego seleccionar los que corresponden
                    $("#books").select2({
                        width: '100%',
                        placeholder: "Selecciona los libros",
                        allowClear: true,
                        ajax: {
                            url: "/dashboard/books/list",
                            dataType: "json",
                            delay: 250,
                            data: function(params) {
                                return {
                                    search: params.term
                                };
                            },
                            processResults: function(response) {
                                return {
                                    results: response.data.map(book => ({
                                        id: book.id,
                                        text: book.title
                                    }))
                                };
                            },
                            cache: true
                        }
                    });

                    // Esperamos a que Select2 termine de cargar y luego asignamos los libros seleccionados
                    setTimeout(() => {
                        selectedBooks.forEach(bookId => {
                            let book = data.books.find(b => b.id === bookId);
                            if (book) {
                                let newOption = new Option(book.title, book.id, true, true);
                                $("#books").append(newOption);
                            }
                        });
                        $("#books").trigger("change");
                    }, 1000); // Esperar 1 segundo para asegurarnos de que Select2 ya cargó los datos
                }

                $("#rowModal").removeClass("hidden");
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

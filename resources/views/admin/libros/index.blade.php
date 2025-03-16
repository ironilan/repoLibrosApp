<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <span class="font-semibold">Libros</span>
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
                <th class="p-3 text-left">Título</th>
                <th class="p-3 text-left">Autor</th>
                <th class="p-3 text-left">Código</th>
                <th class="p-3 text-left">Fecha de pub</th>
                <th class="p-3 text-left">Portada</th>
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

                        <input type="text" id="title" name="title" placeholder="Título"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">

                        <input type="text" id="author" name="author" placeholder="Autor"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">

                        <input type="text" id="code" name="code" placeholder="Código"
                            class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">

                        <input type="date" id="fecha_publicacion" name="fecha_publicacion"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">


                        <div class="grid grid-cols-1 gap-4">
                            <!-- Imagen de Portada -->
                            <div>
                                <label for="cover_image">Imagen de Portada (PNG, JPG, WEBP - máx. 1MB)</label>
                                <input type="file" id="cover_image" name="cover_image"
                                    accept="image/png, image/webp, image/jpg, image/jpeg"
                                    class="w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                                <a id="cover_image_link" href="#" target="_blank"
                                    class="text-blue-500 mt-2 block hidden">Ver Imagen</a>
                            </div>

                            <!-- Archivo Profesor -->
                            {{-- <div>
                                <label for="file_profe">Archivo Profesor (PDF - máx. 2MB)</label>
                                <input type="file" id="file_profe" name="file_profe" accept="application/pdf"
                                    class="w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                                <a id="file_profe_link" href="#" target="_blank"
                                    class="text-blue-500 mt-2 block hidden">Ver Archivo</a>
                            </div>

                            <!-- Archivo Alumno -->
                            <div>
                                <label for="file_alumno">Archivo Alumno (PDF - máx. 2MB)</label>
                                <input type="file" id="file_alumno" name="file_alumno" accept="application/pdf"
                                    class="w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                                <a id="file_alumno_link" href="#" target="_blank"
                                    class="text-blue-500 mt-2 block hidden">Ver Archivo</a>
                            </div> --}}


                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="libro_alumno" class="block font-medium text-gray-700">Libro Alumno </label>
                                <textarea name="libro_alumno" id="libro_alumno" cols="30" rows="5"
                                    class="w-full px-4 py-2 mt-2 text-base border border-gray-300 rounded-lg bg-gray-100 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 ring-blue-400"></textarea>
                            </div>

                            <!-- Libro Profesor -->
                            <div>
                                <label for="libro_profe" class="block font-medium text-gray-700">Libro Profesor</label>
                                <textarea name="libro_profe" id="libro_profe" cols="30" rows="5"
                                    class="w-full px-4 py-2 mt-2 text-base border border-gray-300 rounded-lg bg-gray-100 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 ring-blue-400"></textarea>
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentPage = 1;
            let lastPage = 1;
            const searchInput = document.getElementById("searchData");
            const rowTable = document.getElementById("rowTable");
            const prevPageBtn = document.getElementById("prevPage");
            const nextPageBtn = document.getElementById("nextPage");
            const addButton = document.getElementById("addButton");




            function obtenerDatos(page = 1, query = '') {
                fetch(`/dashboard/books/list?page=${page}&search=${query}`, {
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
                            <td class="p-3">${row.title}</td>
                            <td class="p-3">${row.autor}</td>
                            <td class="p-3">${row.code}</td>
                            <td class="p-3">${row.fecha_publicacion}</td>
                            <td class="p-3"><img class="h-10" src="/storage/${row.cover_image}" /></td>
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
                    title: {
                        required: true,
                        minlength: 3
                    },
                    author: {
                        required: true,
                        minlength: 3
                    },
                    code: {
                        required: true,
                        minlength: 3
                    },
                    fecha_publicacion: {
                        required: true
                    },
                    cover_image: {
                        required: function() {
                            return $("#rowId").val() === "";
                        }
                    },
                    libro_profe: {
                        required: true,
                        minlength: 10
                    },
                    libro_alumno: {
                        required: true,
                        minlength: 10
                    },
                    // file_profe: {
                    //     required: function() {
                    //         return $("#rowId").val() === "";
                    //     }
                    // },
                    // file_alumno: {
                    //     required: function() {
                    //         return $("#rowId").val() === "";
                    //     }
                    // }
                },
                messages: {
                    title: {
                        required: "El título es obligatorio",
                        minlength: "Debe tener al menos 3 caracteres"
                    },
                    author: {
                        required: "El autor es obligatorio",
                        minlength: "Debe tener al menos 3 caracteres"
                    },
                    code: {
                        required: "El código es obligatorio",
                        minlength: "Debe tener al menos 3 caracteres"
                    },
                    fecha_publicacion: {
                        required: "La fecha de publicación es obligatoria"
                    },
                    cover_image: {
                        required: "Debe subir una imagen de portada si está creando un nuevo libro"
                    },
                    libro_profe: {
                        required: "El autor es obligatorio",
                        minlength: "Debe tener al menos 10 caracteres"
                    },
                    libro_alumno: {
                        required: "El autor es obligatorio",
                        minlength: "Debe tener al menos 10 caracteres"
                    },
                    // file_profe: {
                    //     required: "Debe subir el archivo del profesor si está creando un nuevo libro"
                    // },
                    // file_alumno: {
                    //     required: "Debe subir el archivo del alumno si está creando un nuevo libro"
                    // }
                },
                submitHandler: function(form) {
                    if (!validateFiles()) {
                        return false; // No envía el formulario si los archivos no son válidos
                    }
                    saveData(); // Llama a la función para guardar los datos
                    return false;
                }
            });

            function validateFiles() {
                let coverImage = document.getElementById("cover_image").files[0];
                // let fileProfe = document.getElementById("file_profe").files[0];
                // let fileAlumno = document.getElementById("file_alumno").files[0];

                let allowedImageTypes = ["image/png", "image/webp", "image/jpg", "image/jpeg"];
                let allowedPdfType = "application/pdf";

                let maxImageSize = 1 * 1024 * 1024; // 1MB en bytes
                let maxPdfSize = 2 * 1024 * 1024; // 2MB en bytes

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

                // Validar archivo del profesor si se ha seleccionado
                // if (fileProfe) {
                //     if (fileProfe.type !== allowedPdfType) {
                //         alert("El archivo del profesor debe ser un PDF.");
                //         return false;
                //     }
                //     if (fileProfe.size > maxPdfSize) {
                //         alert("El archivo del profesor no debe pesar más de 2MB.");
                //         return false;
                //     }
                // }

                // Validar archivo del alumno si se ha seleccionado
                // if (fileAlumno) {
                //     if (fileAlumno.type !== allowedPdfType) {
                //         alert("El archivo del alumno debe ser un PDF.");
                //         return false;
                //     }
                //     if (fileAlumno.size > maxPdfSize) {
                //         alert("El archivo del alumno no debe pesar más de 2MB.");
                //         return false;
                //     }
                // }

                return true;
            }


            function saveData() {
                let rowId = $("#rowId").val();
                let formData = new FormData();

                // Agregar el token CSRF al FormData
                formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute(
                "content"));

                // Agregar otros valores del formulario
                formData.append("title", $("#title").val());
                formData.append("author", $("#author").val());
                formData.append("code", $("#code").val());
                formData.append("libro_profe", $("#libro_profe").val());
                formData.append("libro_alumno", $("#libro_alumno").val());
                formData.append("fecha_publicacion", $("#fecha_publicacion").val());

                let coverImage = $("#cover_image")[0].files[0];

                if (coverImage) {
                    formData.append("cover_image", coverImage);
                }

                let method = rowId ? "POST" : "POST";
                let url = rowId ? `/dashboard/books/update/${rowId}` : "/dashboard/books";

                if (rowId) {
                    formData.append("_method", "PUT");
                }

                fetch(url, {
                        method: method,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            let errorMessages = Object.values(data.errors).map(error => error.join("<br>"))
                                .join("<br>");
                            Swal.fire({
                                icon: "info",
                                title: "Error de Validación",
                                html: errorMessages,
                                confirmButtonText: "Entendido"
                            });
                        } else {
                            Swal.fire({
                                icon: "success",
                                title: "Éxito",
                                text: "Libro guardado correctamente",
                                confirmButtonText: "OK"
                            }).then(() => {
                                $("#rowModal").addClass("hidden");
                                obtenerDatos(currentPage);
                            });
                        }
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





            // Event listener para abrir el modal de agregar usuario

            if (addButton) {
                addButton.addEventListener("click", function() {
                    showModal("Agregar Libro");
                });
            }



            // Evento delegado para editar/eliminar usuarios
            document.addEventListener("click", function(e) {
                let target = e.target;

                if (target.closest(".editRow")) {
                    let button = target.closest(".editRow");
                    let user = button.getAttribute("data-user");
                    showModal("Editar Usuario", user);
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
                        fetch(`/dashboard/books/${rowId}`, {
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

                // Configurar el título del modal
                document.getElementById("modalTitle").innerText = title;
                document.getElementById("rowId").value = data ? data.id : "";
                document.getElementById("title").value = data ? data.title : "";
                document.getElementById("author").value = data ? data.author : "";
                document.getElementById("code").value = data ? data.code : "";
                document.getElementById("fecha_publicacion").value = data ? data.fecha_publicacion : "";
                document.getElementById("libro_profe").value = data ? data.libro_profe : "";
                document.getElementById("libro_alumno").value = data ? data.libro_alumno : "";

                // Limpiar los inputs de archivo
                document.getElementById("cover_image").value = "";
                // document.getElementById("file_profe").value = "";
                // document.getElementById("file_alumno").value = "";

                // // Mostrar enlaces de archivos si existen
                // updateFilePreview("cover_image_preview", "cover_image_link", data ? '/storage/' + data.cover_image :
                //     null);
                // updateFilePreview("file_profe_preview", "file_profe_link", data ? '/storage/' + data.versions[0]
                //     .file_path : null);
                // updateFilePreview("file_alumno_preview", "file_alumno_link", data ? '/storage/' + data.versions[1]
                //     .file_path : null);



                // Mostrar el modal
                document.getElementById("rowModal").classList.remove("hidden");
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

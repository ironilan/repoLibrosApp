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
                <th class="p-3 text-left">Libro</th>
                <th class="p-3 text-left">Recurso</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody id="rowTable">

        </tbody>
    </table>
    <div class="mt-4 flex justify-between">
        <button id="prevPage" class="bg-gray-300 p-2 rounded-lg" disabled><i class="fa-solid fa-arrow-left"></i>
            Anterior</button>
        <button id="nextPage" class="bg-gray-300 p-2 rounded-lg" disabled>Siguiente <i
                class="fa-solid fa-arrow-right"></i></button>
    </div>



    @include('admin.carpetas.components.modalAdd')
    @include('admin.carpetas.components.modalEdit')




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">


    <script>
        Dropzone.autoDiscover = false;

        document.addEventListener("DOMContentLoaded", function() {
            let currentPage = 1;
            let lastPage = 1;
            const searchInput = document.getElementById("searchRow");
            const rowTable = document.getElementById("rowTable");
            const prevPageBtn = document.getElementById("prevPage");
            const nextPageBtn = document.getElementById("nextPage");
            const addRow = document.getElementById("addRow");


            /*******/

            let myDropzone = new Dropzone("#dropzone", {
                url: "/dashboard/carpetas/libros/crear",
                paramName: "files", // Asegura que los archivos se envían como un array
                maxFilesize: 80, // Máximo 10MB por archivo
                acceptedFiles: "image/*,application/pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt",
                uploadMultiple: true, // Permite subir múltiples archivos en una sola solicitud
                parallelUploads: 20, // Número de archivos a subir en paralelo (ajústalo si tienes muchos)
                autoProcessQueue: false, // Espera a que se presione el botón para enviar
                dictDefaultMessage: "Arrastra tus archivos aquí o haz clic para subir",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        "content")
                }
            });

            // Escuchar el evento de envío y agregar datos adicionales
            myDropzone.on("sending", function(file, xhr, formData) {
                formData.append("tipo_id", document.getElementById("tipo").value);
                formData.append("folder_id", document.getElementById("folder").value);
                formData.append("book_id", document.getElementById("libro").value);
            });

            // Cuando se haga clic en el botón de enviar
            document.getElementById("submitBtn").addEventListener("click", function(e) {
                e.preventDefault();

                // Verificar si hay archivos para subir
                if (myDropzone.files.length > 0) {
                    myDropzone.processQueue(); // Procesar los archivos
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Atención",
                        text: "No hay archivos para subir.",
                        confirmButtonText: "OK"
                    });
                }
            });




            function sendFormData(formData) {
                fetch("/dashboard/carpetas/libros/crear", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                "content")
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Éxito",
                                text: "Datos guardados correctamente",
                                confirmButtonText: "OK"
                            }).then(() => {

                            });
                        } else {
                            throw new Error(data.message);
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




            /*****/

            if (addRow) {
                addRow.addEventListener("click", function() {
                    showModal("Agregar carpeta");
                });
            }



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
                        rowTable.innerHTML = "";

                        if (data.data.length === 0) {
                            rowTable.innerHTML =
                                `<tr><td colspan="7" class="p-3 text-center">No se encontraron usuarios</td></tr>`;
                            return;
                        }

                        let rows = data.data.map(row => `
                        <tr class="border-b">
                            <td class="p-3">${row.id}</td>
                            <td class="p-3">${row.book} - ${row.tipo} - ${row.folder}</td>
                            <td class="p-3">${row.recurso}</td>
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

            document.getElementById("closeModalEdit").addEventListener("click", function() {
                document.getElementById("dataModalEdit").classList.add("hidden");
            });



            function eliminarUser(rowId) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "La combinación será desactivado.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/dashboard/carpetas/libros/${rowId}`, {
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

                $("#name").val(data ? data.recurso : "");
                $("#recurso").val('');
                $('#linkRecurso').attr('href', data ? data.path : '');

                if (data) {
                    $("#dataModalEdit").removeClass("hidden");
                } else {
                    $("#dataModal").removeClass("hidden");
                }
            }






            $("#formDataEdit").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },

                    file: {
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

                    file: {
                        required: "Debe subir un archivo"
                    },

                },
                submitHandler: function(form) {
                    if (!validateFiles()) {
                        return false; // No envía el formulario si los archivos no son válidos
                    }
                    saveDataEdit(); // Llama a la función para guardar los datos
                }
            });


            function saveDataEdit() {
                let rowId = $("#rowId").val();
                let formData = new FormData();

                // Agregar el nombre del pack
                formData.append("name", $("#name").val());



                // Agregar la imagen si el usuario seleccionó una nueva
                let file = $("#file")[0].files[0];
                if (file) {
                    formData.append("file", file);
                }

                // Definir si es un POST (nuevo) o PUT (editar)
                let method = "POST";
                let url = `/dashboard/carpetas/libros/update/${rowId}`;

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
                            $("#dataModalEdit").addClass("hidden");
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




            function validateFiles() {
                let fileAlumno = document.getElementById("file").files[0];

                if (fileAlumno) {
                    // Extensiones y tipos MIME permitidos
                    let allowedTypes = [
                        "application/pdf", // PDF
                        "application/msword", // DOC
                        "application/vnd.openxmlformats-officedocument.wordprocessingml.document", // DOCX
                        "application/vnd.ms-excel", // XLS
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", // XLSX
                        "text/plain" // TXT
                    ];

                    // Tamaño máximo: 20MB (20 * 1024 * 1024 bytes)
                    let maxFileSize = 80 * 1024 * 1024;

                    // Validar tipo de archivo
                    if (!allowedTypes.includes(fileAlumno.type)) {
                        alert(
                            "Formato no permitido. Solo se aceptan archivos PDF, Word (DOC/DOCX), Excel (XLS/XLSX) y TXT."
                            );
                        return false;
                    }

                    // Validar tamaño del archivo
                    if (fileAlumno.size > maxFileSize) {
                        alert("El archivo no debe pesar más de 20MB.");
                        return false;
                    }

                    return true;
                }

                return true;


            }







        });
    </script>
</x-app-layout>

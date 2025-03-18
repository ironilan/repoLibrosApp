<x-app-layout>
    <nav class="text-gray-600 text-sm mb-4">
        <a href="#" class="hover:underline">Inicio</a> / <span class="font-semibold">Configuración</span>
    </nav>

    <div class=" inset-0 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg  w-full max-w-2xl opacity-90">
            <div class="bg-white rounded-lg shadow">
                <div class="flex bg-black mb-4 text-white rounded-t-lg p-4">
                    <div class="flex-1 py-2 pl-5 overflow-hidden">
                        <i class="fas fa-cogs text-xl"></i>
                        <h1 class="inline text-2xl font-semibold leading-none ml-2" id="modalTitle">Configuración</h1>
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <form id="formData" enctype="multipart/form-data">
                        <input type="hidden" id="rowId" name="rowId">

                        <!-- Título -->
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700">Título</label>
                            <input type="text" id="name" name="name" placeholder="Nombre"
                                class="text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                        </div>

                        <!-- Contenedor de imágenes -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Logo Horizontal -->
                            <div>
                                <label for="logo_horizontal">Logo Horizontal (PNG, JPG, WEBP - máx. 1MB)</label>
                                <div class="border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <img id="preview_logo_horizontal" class="hidden w-full h-full object-cover" style="width: 14rem;">
                                </div>
                                <input type="file" id="logo_horizontal" name="logo_horizontal"
                                    accept="image/png, image/webp, image/jpg, image/jpeg"
                                    class="w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            </div>

                            <!-- Logo -->
                            <div>
                                <label for="logo">Logo de Portada (PNG, JPG, WEBP - máx. 1MB)</label>
                                <div class="border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    <img id="preview_logo" class="hidden w-full h-full object-cover" style="width: 7rem; height: 7rem;">
                                </div>
                                <input type="file" id="logo" name="logo"
                                    accept="image/png, image/webp, image/jpg, image/jpeg"
                                    class="w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="mt-4">
                            <label for="favicon">Favicon (PNG, ICO - máx. 512KB)</label>
                            <div class="border-2 border-dashed border-gray-300 w-16 h-16 flex items-center justify-center">
                                <img id="preview_favicon" class="hidden w-full h-full object-cover">
                            </div>
                            <input type="file" id="favicon" name="favicon" accept="image/png, image/x-icon"
                                class="w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                        </div>

                        <hr class="mt-4">

                        <!-- Botón Guardar -->
                        <div class="flex flex-row-reverse p-3">
                            <div class="flex-initial pl-3">
                                <button type="submit"
                                    class="flex items-center px-5 py-2.5 font-medium tracking-wide text-white capitalize bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:bg-gray-900 transition duration-300 transform active:scale-95 ease-in-out">
                                    <i class="fas fa-save pr-2"></i> Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery, Validation y SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // 🔄 Cargar datos de la configuración al abrir el formulario
            $.get("/dashboard/configuracion/get/1", function(data) {
                $("#name").val(data.name);

                function setPreview(imageId, linkId, path) {
                    if (path) {
                        $(imageId).attr("src", path).removeClass("hidden");
                        $(linkId).attr("href", path).removeClass("hidden");
                    }
                }

                setPreview("#preview_logo", "#logo_link", data.logo ?  data
                    .logo : "");
                setPreview("#preview_logo_horizontal", "#logo_horizontal_link", data.logo_horizontal ?
                     data.logo_horizontal : "");
                setPreview("#preview_favicon", "#favicon_link", data.favicon ?
                    data.favicon : "");
            });

            // ✅ PREVIEW de imágenes antes de subir
            function previewImage(input, previewId, linkId) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewId).attr('src', e.target.result).removeClass('hidden');
                        $(linkId).attr('href', e.target.result).removeClass('hidden');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#logo").change(function() {
                previewImage(this, "#preview_logo", "#logo_link");
            });

            $("#logo_horizontal").change(function() {
                previewImage(this, "#preview_logo_horizontal", "#logo_horizontal_link");
            });

            $("#favicon").change(function() {
                previewImage(this, "#preview_favicon", "#favicon_link");
            });

            // ✅ ENVÍO AJAX
            $("#formData").submit(function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                // Obtener el CSRF token del meta tag en <head> o de un input hidden en el formulario
                let csrfToken = $('meta[name="csrf-token"]').attr("content") || $('input[name="_token"]')
                    .val();

                // Agregar el CSRF token a la petición
                formData.append('_token', csrfToken);

                $.ajax({
                    url: "/dashboard/configuracion/update/1",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken // También se puede enviar en los headers
                    },
                    success: function() {
                        Swal.fire("¡Guardado!", "Los cambios se han guardado correctamente.",
                            "success");
                    },
                    error: function() {
                        Swal.fire("Error", "Ocurrió un problema al guardar los cambios.",
                            "error");
                    }
                });
            });
        });
    </script>
</x-app-layout>

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

                        <select id="tipo" name="tipo"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            <option value="">Seleccione un tipo </option>
                            @foreach ($tipos as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <select id="libro" name="libro"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            <option value="">Seleccione un libro</option>
                            @foreach ($libros as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>

                        <select id="folder" name="folder"
                            class="text-black w-full px-4 py-2.5 mt-2 text-base border-transparent rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:shadow-outline focus:ring-2 ring-gray-400">
                            <option value="">Seleccione una carpeta</option>
                            @foreach ($folders as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                        <!-- Dropzone para subir archivos -->
                        <div class="mt-4">
                            <label class="block text-gray-700">Subir Archivos:</label>
                            <div id="dropzone"
                                class="dropzone border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-100">
                            </div>
                        </div>

                        <hr class="mt-4">

                        <div class="flex flex-row-reverse p-3">
                            <div class="flex-initial pl-3">
                                <button type="button" id="submitBtn"
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

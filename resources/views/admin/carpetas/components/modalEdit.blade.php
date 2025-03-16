<div id="dataModalEdit"
    class="hidden fixed inset-0 flex items-center justify-center rounded-lg bg-opacity-50 bg-gray-900">
    <div class="bg-white w-1/2 rounded-lg opacity-90">
        <div class="bg-white rounded-lg shadow">
            <div class="flex bg-black mb-4 text-white rounded-t-lg p-4">
                <div class="flex-1 py-2 pl-5 overflow-hidden">
                    <i class="fas fa-user text-xl"></i>
                    <h1 class="inline text-2xl font-semibold leading-none ml-2">Editar recurso</h1>
                </div>
            </div>

            <div class="px-5 pb-5">
                <form method="POST" class="space-y-4" id="formDataEdit" enctype="multipart/form-data">
                    <input type="hidden" id="rowId" name="rowId">

                    <!-- Input para el nombre del recurso -->
                    <div class="mt-4">
                        <label for="name" class="block text-gray-700 font-semibold">Nombre del recurso:</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2.5 mt-2 text-base border rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 ring-gray-400">
                    </div>

                    <!-- Input para seleccionar un archivo -->
                    <div class="mt-4">
                        <label for="file" class="block text-gray-700 font-semibold">Seleccionar Archivo:</label>
                        <input type="file" id="file" name="file"
                            accept=".pdf, .txt, .doc, .docx, .xls, .xlsx"
                            class="w-full px-4 py-2.5 mt-2 text-base border rounded-lg bg-gray-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-2 ring-gray-400">

                    </div>
                    <a href="" id="linkRecurso">Ver recurso</a>

                    <hr class="mt-4">

                    <!-- Botones de acción -->
                    <div class="flex flex-row-reverse p-3">
                        <div class="flex-initial pl-3">
                            <button type="submit"
                                class="flex items-center px-5 py-2.5 font-medium tracking-wide text-white capitalize bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:bg-gray-900 transition duration-300 transform active:scale-95 ease-in-out">
                                <i class="fas fa-save pr-2"></i> Guardar
                            </button>
                        </div>
                        <div class="flex-initial">
                            <button type="button" id="closeModalEdit"
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

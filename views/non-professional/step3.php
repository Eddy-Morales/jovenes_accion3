<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step3'] ?? [];

$areas = [
    'direccion_general' => 'Dirección General Administrativa',
    'inspeccion' => 'Inspección',
    'adm_secretaria_academica' => 'Secretaría Académica',
    'adm_contabilidad_tesoreria' => 'Contabilidad',
    'adm_educacion_seguridad_vial' => 'Asesoría Técnica en Educación y Seguridad Vial',
    'adm_sala_espera_recepcion' => 'Sala de espera',
    'adm_recepcion' => 'Recepción'
];
?>

<div class="max-w-5xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Área Administrativa</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 3 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Infraestructura Básica</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 33%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="3">
        <input type="hidden" name="next_url" value="../views/non-professional/step4.php">

        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">5.1</span>
                Áreas Administrativas
            </h3>

            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($areas as $key => $label): ?>
                <div class="group bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all hover:bg-white/80 backdrop-blur-sm">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                        <?php echo $label; ?>
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Cuenta con el área -->
                        <div>
                            <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">¿Cuenta con el área?</span>
                            <div class="flex items-center gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="<?php echo $key; ?>_existe" value="Si" 
                                        <?php echo ($data[$key . '_existe'] ?? '') == 'Si' ? 'checked' : ''; ?>
                                        class="peer sr-only">
                                    <div class="px-5 py-2 rounded-lg border border-gray-200 bg-white text-gray-600 peer-checked:bg-green-100 peer-checked:text-green-700 peer-checked:border-green-200 transition-all font-medium flex items-center gap-2 hover:bg-gray-50">
                                        <div class="w-4 h-4 rounded-full border border-gray-300 peer-checked:bg-green-500 peer-checked:border-green-500"></div>
                                        Si
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="<?php echo $key; ?>_existe" value="No" 
                                        <?php echo ($data[$key . '_existe'] ?? '') == 'No' ? 'checked' : ''; ?>
                                        class="peer sr-only">
                                    <div class="px-5 py-2 rounded-lg border border-gray-200 bg-white text-gray-600 peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-200 transition-all font-medium flex items-center gap-2 hover:bg-gray-50">
                                        <div class="w-4 h-4 rounded-full border border-gray-300 peer-checked:bg-red-500 peer-checked:border-red-500"></div>
                                        No
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Acceso discapacidad -->
                        <div>
                             <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Acceso Discapacitados</span>
                             <div class="flex items-center gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="<?php echo $key; ?>_acceso" value="Si" 
                                        <?php echo ($data[$key . '_acceso'] ?? '') == 'Si' ? 'checked' : ''; ?>
                                        class="peer sr-only">
                                    <div class="px-5 py-2 rounded-lg border border-gray-200 bg-white text-gray-600 peer-checked:bg-blue-100 peer-checked:text-blue-700 peer-checked:border-blue-200 transition-all font-medium flex items-center gap-2 hover:bg-gray-50">
                                        <div class="w-4 h-4 rounded-full border border-gray-300 peer-checked:bg-blue-500 peer-checked:border-blue-500"></div>
                                        Si
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="<?php echo $key; ?>_acceso" value="No" 
                                        <?php echo ($data[$key . '_acceso'] ?? '') == 'No' ? 'checked' : ''; ?>
                                        class="peer sr-only">
                                    <div class="px-5 py-2 rounded-lg border border-gray-200 bg-white text-gray-600 peer-checked:bg-gray-100 peer-checked:text-gray-700 peer-checked:border-gray-200 transition-all font-medium flex items-center gap-2 hover:bg-gray-50">
                                        <div class="w-4 h-4 rounded-full border border-gray-300 peer-checked:bg-gray-400 peer-checked:border-gray-400"></div>
                                        No
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step2.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Anterior
            </a>
            <button type="submit" class="w-full md:w-auto btn-premium text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-purple-500/30 flex items-center justify-center gap-2 group">
                Siguiente Paso
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>

<?php include '../../includes/form_footer.php'; ?>

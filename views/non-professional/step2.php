<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

// Retrieve existing data
$step1 = $_SESSION['form_data']['step1'] ?? [];
$data = $_SESSION['form_data']['step2'] ?? [];

// Pre-fill from Step 1 if not set in Step 2
$ruc = $data['ruc'] ?? ($step1['ruc'] ?? '');
$gerente_nombre = $data['gerente_nombre'] ?? ($step1['gerente_nombre'] ?? '');
?>

<div class="max-w-4xl mx-auto">
     <!-- Progress Header -->
     <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Documentos Habilitantes</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 2 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Legal y Administrativo</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 22%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
         <!-- Decoration Gradient -->
         <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="2">
        <input type="hidden" name="next_url" value="../views/non-professional/step3.php">

        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">4.1</span>
                Resoluciones para el funcionamiento
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Resolución de funcionamiento -->
                <div class="md:col-span-2 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Resolución de funcionamiento</label>
                    <input type="text" name="resolucion_nro" value="<?php echo htmlspecialchars($data['resolucion_nro'] ?? ''); ?>"
                        class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm"
                        placeholder="Número de resolución">
                </div>

                <!-- Tipo de cursos autorizados -->
                <div class="md:col-span-2 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Tipo de cursos autorizados</label>
                    <div class="relative">
                        <select name="cursos" class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all appearance-none form-input-premium backdrop-blur-sm">
                            <option value="" disabled <?php echo empty($data['cursos']) ? 'selected' : ''; ?>>Seleccione una opción</option>
                            <option value="Tipo A" <?php echo ($data['cursos'] ?? '') == 'Tipo A' ? 'selected' : ''; ?>>Tipo A</option>
                            <option value="Tipo B" <?php echo ($data['cursos'] ?? '') == 'Tipo B' ? 'selected' : ''; ?>>Tipo B</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Resoluciones de aumento/disminución -->
                <div class="md:col-span-2 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Resoluciones de aumento/disminución; etc.</label>
                    <textarea name="resoluciones_extra" rows="2"
                        class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm resize-none"><?php echo htmlspecialchars($data['resoluciones_extra'] ?? ''); ?></textarea>
                </div>

                <!-- Número de RUC -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Número de RUC</label>
                    <input type="text" name="ruc" value="<?php echo htmlspecialchars($ruc); ?>"
                        class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed outline-none backdrop-blur-sm">
                </div>

                <!-- Estado SRI -->
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Estado SRI</label>
                    <div class="relative">
                        <select name="estado_sri" class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all appearance-none form-input-premium backdrop-blur-sm">
                            <option value="Activo" <?php echo ($data['estado_sri'] ?? '') == 'Activo' ? 'selected' : ''; ?>>Activo</option>
                            <option value="Suspendido" <?php echo ($data['estado_sri'] ?? '') == 'Suspendido' ? 'selected' : ''; ?>>Suspendido</option>
                            <option value="Cancelado" <?php echo ($data['estado_sri'] ?? '') == 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Representante Legal -->
                <div class="md:col-span-2 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Representante legal</label>
                    <input type="text" name="gerente_nombre" value="<?php echo htmlspecialchars($gerente_nombre); ?>"
                        class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed outline-none backdrop-blur-sm">
                </div>

                 <!-- Tenencia Logic -->
                <div class="md:col-span-2 border-t border-gray-100 pt-6 mt-2">
                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="bg-emerald-100 text-emerald-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">4.2</span>
                        Información del Predio
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Tipo de Tenencia</label>
                            <div class="relative">
                                <select name="tipo_tenencia" id="tipo_tenencia" onchange="toggleTenencia()" class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all appearance-none form-input-premium backdrop-blur-sm">
                                    <option value="">Seleccione una opción</option>
                                    <option value="Propia" <?php echo ($data['tipo_tenencia'] ?? '') == 'Propia' ? 'selected' : ''; ?>>Propia</option>
                                    <option value="Arrendada" <?php echo ($data['tipo_tenencia'] ?? '') == 'Arrendada' ? 'selected' : ''; ?>>Arrendada</option>
                                    <option value="Comodato" <?php echo ($data['tipo_tenencia'] ?? '') == 'Comodato' ? 'selected' : ''; ?>>Comodato</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Fields conditional on Tenancy -->
                        <div id="field_impuesto" class="hidden group animate-fade-in-up">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Predio urbano/ impuesto predial</label>
                            <input type="text" name="impuesto_predial" value="<?php echo htmlspecialchars($data['impuesto_predial'] ?? ''); ?>"
                                class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                        </div>

                        <div id="field_patente" class="hidden group animate-fade-in-up">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 ml-1">Patente municipal</label>
                            <input type="text" name="patente_municipal" value="<?php echo htmlspecialchars($data['patente_municipal'] ?? ''); ?>"
                                class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function toggleTenencia() {
            const tenencia = document.getElementById('tipo_tenencia').value;
            const imp = document.getElementById('field_impuesto');
            const pat = document.getElementById('field_patente');

            // Reset
            imp.classList.add('hidden');
            pat.classList.add('hidden');

            if (tenencia === 'Propia') {
                imp.classList.remove('hidden');
            } else if (tenencia === 'Comodato') {
                pat.classList.remove('hidden');
            }
        }
        // Run on load
        document.addEventListener('DOMContentLoaded', toggleTenencia);
        </script>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step1.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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

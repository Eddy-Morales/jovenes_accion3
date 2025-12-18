<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step7'] ?? [];
?>

<div class="max-w-4xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Prácticas de Conducción</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 7 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Infraestructura Física</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 77%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="7">
        <input type="hidden" name="next_url" value="../views/non-professional/step8.php">

        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">7.1</span>
            Prácticas de conducción Tipo A (Pista de motos)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Estado: propia/arrendada -->
            <div class="bg-white/50 p-4 border border-gray-200 rounded-xl hover:shadow-md transition-shadow">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Estado</label>
                <select name="pista_propiedad" id="pista_propiedad" onchange="toggleFechaContrato()" 
                    class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                    <option value="">- Seleccione -</option>
                    <option value="Propia" <?php echo ($data['pista_propiedad'] ?? '') == 'Propia' ? 'selected' : ''; ?>>Propia</option>
                    <option value="Arrendada" <?php echo ($data['pista_propiedad'] ?? '') == 'Arrendada' ? 'selected' : ''; ?>>Arrendada</option>
                </select>
            </div>

            <!-- Fecha vigencia contrato (Conditional) -->
            <div id="fecha_contrato_div" class="hidden bg-white/50 p-4 border border-gray-200 rounded-xl hover:shadow-md transition-shadow">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Fecha de vigencia del contrato</label>
                <input type="date" name="fechaarrendada" value="<?php echo htmlspecialchars($data['fechaarrendada'] ?? ''); ?>"
                    class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer text-gray-700">
            </div>

            <!-- Domicilio -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Domicilio</label>
                <input type="text" name="pista_direccion" value="<?php echo htmlspecialchars($data['pista_direccion'] ?? ''); ?>"
                    class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm" placeholder="Dirección completa...">
            </div>

            <!-- Dentro de la escuela -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Dentro de la escuela</label>
                <div class="relative">
                    <select name="pista_predio" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer appearance-none">
                        <option value="">- Seleccione -</option>
                        <option value="Si" <?php echo ($data['pista_predio'] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                        <option value="No" <?php echo ($data['pista_predio'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Más de 15 km de la escuela -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Más de 15 km de la escuela</label>
                <div class="relative">
                    <select name="pista_ubikilome" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer appearance-none">
                        <option value="">- Seleccione -</option>
                        <option value="Si" <?php echo ($data['pista_ubikilome'] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                        <option value="No" <?php echo ($data['pista_ubikilome'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                    </select>
                     <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Detalles Grid -->
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                 <!-- Superficie de la pista en m2 -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Superficie de la pista en m²</label>
                    <input type="number" name="metros_pista" value="<?php echo htmlspecialchars($data['metros_pista'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>

                <!-- Material del cerramiento perimetral -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Material cerramiento perimetral</label>
                    <input type="text" name="tipo_cerramiento" value="<?php echo htmlspecialchars($data['tipo_cerramiento'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>

                <!-- Cuenta con postes perimetrales -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Cuenta con postes perimetrales</label>
                    <select name="p_perimetrales" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all cursor-pointer">
                        <option value="">- Seleccione -</option>
                        <option value="Si" <?php echo ($data['p_perimetrales'] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                        <option value="No" <?php echo ($data['p_perimetrales'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <!-- Postes delineadores (mts) -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Postes delineadores (mts)</label>
                    <input type="text" name="postes_altura" value="<?php echo htmlspecialchars($data['postes_altura'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>

                <!-- Rampa elevada -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Rampa elevada de 6000mm X 150mm X 50 mm</label>
                    <input type="text" name="rampa_elevada" value="<?php echo htmlspecialchars($data['rampa_elevada'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>

                <!-- Tipo de material de la rampa -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tipo de material de la rampa</label>
                    <input type="text" name="rampa_material" value="<?php echo htmlspecialchars($data['rampa_material'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>

                <!-- Dispositivo en forma de cono truncado -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Dispositivo en forma de cono truncado</label>
                    <input type="text" name="dispositivos_cono" value="<?php echo htmlspecialchars($data['dispositivos_cono'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>

                <!-- Número de travesaños y medidas -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Número de travesaños y medidas</label>
                    <input type="text" name="trave_num" value="<?php echo htmlspecialchars($data['trave_num'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>

                <!-- Número de indicadores de cambio de maniobras -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Número de indicadores de cambio de maniobras</label>
                    <input type="text" name="num_indi" value="<?php echo htmlspecialchars($data['num_indi'] ?? ''); ?>"
                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step6.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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

<script>
function toggleFechaContrato() {
    const estado = document.getElementById('pista_propiedad').value;
    const fechaDiv = document.getElementById('fecha_contrato_div');

    if (estado === 'Arrendada') {
        fechaDiv.classList.remove('hidden');
    } else {
        fechaDiv.classList.add('hidden');
    }
}
// Run on load
document.addEventListener('DOMContentLoaded', toggleFechaContrato);
</script>

<?php include '../../includes/form_footer.php'; ?>

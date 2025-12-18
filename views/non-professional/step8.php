<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step8'] ?? [];
?>

<div class="max-w-4xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Baterías Sanitarias</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 8 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Infraestructura Física</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 88%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="8">
        <input type="hidden" name="next_url" value="../views/non-professional/step9.php">

        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-8 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">6</span>
            Baterías Sanitarias
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 1. Número -->
             <div class="md:col-span-2 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100 shadow-sm hover:shadow-md transition-all">
                <h4 class="text-sm font-bold text-indigo-800 mb-4 border-b border-indigo-200 pb-2 flex items-center gap-2">
                    <span class="bg-indigo-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shrink-0">1</span>
                    <span>Número de Baterías</span>
                </h4>
                <div class="grid grid-cols-2 gap-8">
                    <div class="text-center">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">👨 Hombres</label>
                        <input type="number" name="bs_num_hombres" value="<?php echo htmlspecialchars($data['bs_num_hombres'] ?? ''); ?>" 
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-center text-lg font-bold text-gray-700 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all" placeholder="0" min="0">
                    </div>
                    <div class="text-center">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">👩 Mujeres</label>
                        <input type="number" name="bs_num_mujeres" value="<?php echo htmlspecialchars($data['bs_num_mujeres'] ?? ''); ?>" 
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-center text-lg font-bold text-gray-700 focus:ring-4 focus:ring-pink-100 focus:border-pink-500 outline-none transition-all" placeholder="0" min="0">
                    </div>
                </div>
            </div>

            <?php 
            $items = [
                ['label' => '2. Inodoros (con asiento y tapa)', 'key_h' => 'inodoro_h', 'key_m' => 'inodoro', 'icon' => '🚽'],
                ['label' => '3. Urinario', 'key_h' => 'urinario', 'key_m' => null, 'icon' => '🚹'], // Special case for women
                ['label' => '4. Lavamanos', 'key_h' => 'Lava_h', 'key_m' => 'Lava_m', 'icon' => '🚰'],
                ['label' => '5. Espejo', 'key_h' => 'E_Lava_h', 'key_m' => 'E_Lava_m', 'icon' => '🪞'],
                ['label' => '6. Dispensador de Jabón', 'key_h' => 'imp_jabon_h', 'key_m' => 'imp_jabon_m', 'icon' => '🧼'],
                ['label' => '7. Secado de Manos', 'key_h' => 'manos_h', 'key_m' => 'manos_m', 'icon' => '💨'],
                ['label' => '8. Papel Higiénico', 'key_h' => 'imp_papel_higienico_h', 'key_m' => 'imp_papel_higienico_m', 'icon' => '🧻'],
                ['label' => '9. Basurero con tapa', 'key_h' => 'imp_basurero_tapa_h', 'key_m' => 'imp_basurero_tapa_m', 'icon' => '🗑️'],
                ['label' => '10. Desinfectante', 'key_h' => 'imp_alcohol_h', 'key_m' => 'imp_alcohol_m', 'icon' => '✨'],
                ['label' => '11. Iluminación Central', 'key_h' => 'ilum_central_h', 'key_m' => 'ilum_central_m', 'icon' => '💡'],
                ['label' => '12. Acceso Discapacitados', 'key_h' => 'bs_banos_discapacidad_h', 'key_m' => 'bs_banos_discapacidad_m', 'icon' => '♿']
            ];

            foreach ($items as $item): 
            ?>
            <div class="bg-white/60 rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all group backdrop-blur-sm">
                <h4 class="text-sm font-bold text-gray-700 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2 group-hover:text-purple-600 transition-colors">
                    <span class="text-xl"><?php echo $item['icon']; ?></span>
                    <?php echo $item['label']; ?>
                </h4>
                <div class="grid grid-cols-2 gap-4">
                     <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1 text-center">Hombres</label>
                        <select name="<?php echo $item['key_h']; ?>" class="w-full px-3 py-2 bg-white/80 border border-gray-200 rounded-lg text-sm text-center focus:ring-2 focus:ring-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">-</option>
                            <option value="Si" <?php echo ($data[$item['key_h']] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data[$item['key_h']] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1 text-center">Mujeres</label>
                        <?php if ($item['key_m']): ?>
                        <select name="<?php echo $item['key_m']; ?>" class="w-full px-3 py-2 bg-white/80 border border-gray-200 rounded-lg text-sm text-center focus:ring-2 focus:ring-pink-500 outline-none transition-all cursor-pointer">
                            <option value="">-</option>
                            <option value="Si" <?php echo ($data[$item['key_m']] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data[$item['key_m']] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                        <?php else: ?>
                        <div class="w-full px-3 py-2 border border-gray-100 rounded-lg bg-gray-50 text-center text-gray-400 text-sm font-medium">N/A</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step7.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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

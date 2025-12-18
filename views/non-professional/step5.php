<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step5'] ?? [];
// Default to 1 empty row if no data
$rows = isset($data['aul_num']) ? count($data['aul_num']) : 1;
?>

<div class="max-w-6xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Aulas</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 5 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Infraestructura Física</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 55%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="5">
        <input type="hidden" name="next_url" value="../views/non-professional/step6.php">

        <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-8">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">5.2</span>
                Aulas
            </h3>
            <button type="button" onclick="addCard()" class="hidden md:flex bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 font-medium px-4 py-2 rounded-xl items-center gap-2 transition-colors border border-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Aula
            </button>
        </div>

        <div id="aula-container" class="space-y-6 mb-8">
            <?php for ($i = 0; $i < $rows; $i++): ?>
            <div class="aula-card group bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative animate-fade-in-down transition-all hover:bg-white/80 backdrop-blur-sm hover:shadow-md">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h4 class="text-sm font-bold text-purple-600 uppercase tracking-wider flex items-center gap-2">
                         <span class="bg-purple-100 w-8 h-8 rounded-full flex items-center justify-center text-xs">#<span class="row-index"><?php echo $i + 1; ?></span></span>
                        Aula
                    </h4>
                    <button type="button" onclick="removeCard(this)" class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100" title="Eliminar este aula">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <!-- General Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Número</label>
                        <input type="text" name="aul_num[]" value="<?php echo htmlspecialchars($data['aul_num'][$i] ?? ''); ?>" 
                            class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm" placeholder="#">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tipo Curso</label>
                        <select name="aul_tipo[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Tipo A" <?php echo ($data['aul_tipo'][$i] ?? '') == 'Tipo A' ? 'selected' : ''; ?>>Tipo A</option>
                            <option value="Tipo B" <?php echo ($data['aul_tipo'][$i] ?? '') == 'Tipo B' ? 'selected' : ''; ?>>Tipo B</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Res. Funcionamiento #</label>
                        <input type="text" name="aul_res_func[]" value="<?php echo htmlspecialchars($data['aul_res_func'][$i] ?? ''); ?>" 
                             class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Capacidad</label>
                        <input type="number" name="aul_cap[]" value="<?php echo htmlspecialchars($data['aul_cap'][$i] ?? ''); ?>" 
                             class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm">
                    </div>
                </div>

                <!-- Conditions Grid -->
                <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100">
                    <h5 class="text-xs font-bold text-indigo-800 mb-3 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Condiciones y Equipamiento
                    </h5>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Ventilación</span>
                            <select name="aul_ventilacion[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['aul_ventilacion'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['aul_ventilacion'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Iluminación</span>
                            <select name="aul_iluminacion[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['aul_iluminacion'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['aul_iluminacion'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Proyector/TV</span>
                            <select name="aul_tec[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['aul_tec'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['aul_tec'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Pizarra</span>
                            <select name="aul_pizarra[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['aul_pizarra'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['aul_pizarra'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Estación Docente</span>
                            <select name="aul_estacion[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['aul_estacion'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['aul_estacion'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Otro material</span>
                            <input type="text" name="aul_otro_mat[]" value="<?php echo htmlspecialchars($data['aul_otro_mat'][$i] ?? ''); ?>" class="w-full text-xs bg-transparent border-b border-gray-300 p-0 focus:ring-0 focus:border-purple-500 text-gray-700 placeholder-gray-300" placeholder="Especifique...">
                        </div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <div class="flex justify-center md:hidden mb-8">
            <button type="button" onclick="addCard()" class="bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 font-medium px-4 py-2 rounded-lg flex items-center gap-2 transition-colors border border-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Aula
            </button>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step4.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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
function addCard() {
    const container = document.getElementById('aula-container');
    let templateCard = container.children[0];
    
    if(!templateCard && container.children.length === 0) {
        alert("Error de integridad: Recargue la página.");
        return;
    }

    const newCard = templateCard.cloneNode(true);
    
    // Clear inputs in the new row
    newCard.querySelectorAll('input').forEach(input => input.value = '');
    newCard.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    
    // Update index
    const indexSpan = newCard.querySelector('.row-index');
    if(indexSpan) indexSpan.innerText = container.children.length + 1;

    container.appendChild(newCard);
}

function removeCard(button) {
    const container = document.getElementById('aula-container');
    if (container.children.length > 1) {
        button.closest('.aula-card').remove();
         // Re-number
        Array.from(container.children).forEach((card, idx) => {
            const span = card.querySelector('.row-index');
            // If the span contains text inside, we update it. Includes simple numbering logic.
            if(span) span.innerText = idx + 1; 
        });
    } else {
        alert("Debe existir al menos un aula.");
    }
}
</script>

<?php include '../../includes/form_footer.php'; ?>

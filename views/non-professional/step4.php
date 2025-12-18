<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step4'] ?? [];
// Determine number of rows. If 'area_detalle' exists, count it. Otherwise start with 1.
$rows = isset($data['area_detalle']) ? count($data['area_detalle']) : 1;
?>

<div class="max-w-5xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Seguridad</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 4 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Seguridad y Emergencia</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 44%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="4">
        <input type="hidden" name="next_url" value="../views/non-professional/step5.php">

        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">5.1.1</span>
            Seguros contra incendios / salidas de emergencia
        </h3>

        <div id="area-container" class="space-y-6 mb-8">
            <?php for ($i = 0; $i < $rows; $i++): ?>
            <div class="area-card group bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative animate-fade-in-down transition-all hover:bg-white/80 backdrop-blur-sm">
                <div class="absolute top-4 right-4">
                    <button type="button" onclick="removeCard(this)" class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100" title="Eliminar este registro">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <h4 class="text-sm font-bold text-purple-600 uppercase mb-4 tracking-wider flex items-center gap-2">
                    <span class="bg-purple-100 w-6 h-6 rounded-full flex items-center justify-center text-xs">#<span class="row-index"><?php echo $i + 1; ?></span></span>
                    Área
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Detalle del Área</label>
                        <input type="text" name="area_detalle[]" value="<?php echo htmlspecialchars($data['area_detalle'][$i] ?? ''); ?>" 
                            class="w-full px-5 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm" placeholder="Ej. Aulas Teóricas">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Salida de emergencia</label>
                        <div class="bg-white/50 p-3 border border-gray-200 rounded-xl flex items-center justify-between hover:border-purple-300 transition-colors">
                            <span class="text-xs font-medium text-gray-500">¿Tiene señales?</span>
                            <select name="rotulos_salida[]" class="bg-transparent border-none text-sm font-semibold text-gray-700 focus:ring-0 cursor-pointer">
                                <option value="">- Selec -</option>
                                <option value="Si" <?php echo ($data['rotulos_salida'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['rotulos_salida'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Contra Incendios</label>
                        <div class="bg-white/50 p-3 border border-gray-200 rounded-xl flex items-center justify-between hover:border-purple-300 transition-colors">
                            <span class="text-xs font-medium text-gray-500">¿Tiene señales?</span>
                            <select name="senales_incendios[]" class="bg-transparent border-none text-sm font-semibold text-gray-700 focus:ring-0 cursor-pointer">
                                <option value="">- Selec -</option>
                                <option value="Si" <?php echo ($data['senales_incendios'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['senales_incendios'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <div class="flex justify-end mb-8">
            <button type="button" onclick="addCard()" class="group bg-purple-50 text-purple-600 hover:bg-purple-100 hover:text-purple-700 font-medium px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all border border-purple-200 hover:border-purple-300 shadow-sm">
                <div class="bg-purple-200 rounded-full p-1 text-purple-600 group-hover:bg-purple-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                Agregar Área
            </button>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step3.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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
    const container = document.getElementById('area-container');
    let templateCard = container.children[0];
    
    // Safety check
    if(!templateCard && container.children.length === 0) {
        // Fallback or restart
        alert("Error de integridad: Recargue la página.");
        return;
    }

    const newCard = templateCard.cloneNode(true);
    
    // Clear inputs in the new row
    newCard.querySelectorAll('input').forEach(input => input.value = '');
    newCard.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    
    // Update index (cosmetic)
    const indexSpan = newCard.querySelector('.row-index');
    if(indexSpan) indexSpan.innerText = container.children.length + 1;

    container.appendChild(newCard);
}

function removeCard(button) {
    const container = document.getElementById('area-container');
    if (container.children.length > 1) {
        button.closest('.area-card').remove();
        // Re-number
        Array.from(container.children).forEach((card, idx) => {
            const span = card.querySelector('.row-index');
            if(span) span.innerText = idx + 1;
        });
    } else {
        alert("Debe existir al menos un área de seguridad.");
    }
}
</script>

<?php include '../../includes/form_footer.php'; ?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header
include '../../includes/form_header.php';

$data = $_SESSION['form_data']['step6'] ?? [];
// Rows for Workshop (Taller)
$rowsTaller = isset($data['taller_curso']) ? count($data['taller_curso']) : 1;
// Rows for Platform (Plataforma)
$rowsPlat = isset($data['plat_curso']) ? count($data['plat_curso']) : 1;
?>

<div class="max-w-6xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Aulas Taller y Plataforma</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-purple-600 mb-4">
            <span class="bg-purple-100 px-3 py-1 rounded-full">Paso 6 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Infraestructura Física</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 66%"></div>
        </div>
    </div>

    <!-- Form Card -->
    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-8 md:p-10 shadow-2xl border border-white/50 relative overflow-hidden">
        <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500"></div>

        <input type="hidden" name="step" value="6">
        <input type="hidden" name="next_url" value="../views/non-professional/step7.php">

        <!-- TABLE 2: AULA TALLER -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-gray-200 pb-3 mb-8 gap-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">6.1</span>
                Aula Taller
            </h3>
            <button type="button" onclick="addTallerCard()" class="bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 font-medium px-4 py-2 rounded-xl flex items-center gap-2 transition-colors border border-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> 
                Agregar Taller
            </button>
        </div>

        <div id="taller-container" class="space-y-6 mb-12">
            <?php for ($i = 0; $i < $rowsTaller; $i++): ?>
            <div class="taller-card group bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative animate-fade-in-down transition-all hover:bg-white/80 backdrop-blur-sm hover:shadow-md">
                <div class="absolute top-4 right-4">
                    <button type="button" onclick="removeTallerCard(this)" class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100" title="Eliminar este taller">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                <h4 class="text-xs font-bold text-gray-400 uppercase mb-4 tracking-wider">Taller #<span class="row-index"><?php echo $i + 1; ?></span></h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tipo de Curso</label>
                        <select name="taller_curso[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Tipo A" <?php echo ($data['taller_curso'][$i] ?? '') == 'Tipo A' ? 'selected' : ''; ?>>Tipo A</option>
                            <option value="Tipo B" <?php echo ($data['taller_curso'][$i] ?? '') == 'Tipo B' ? 'selected' : ''; ?>>Tipo B</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Cuenta con aula taller?</label>
                        <select name="taller_existe[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['taller_existe'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['taller_existe'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
        
        <!-- TABLE 3: PLATAFORMA TECNOLOGICA -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between border-b border-gray-200 pb-3 mb-8 gap-4">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm">6.2</span>
                Plataforma Tecnológica
            </h3>
            <button type="button" onclick="addPlatCard()" class="bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-800 font-medium px-4 py-2 rounded-lg flex items-center gap-2 transition-colors border border-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> 
                Agregar Plataforma
            </button>
        </div>

        <div id="plat-container" class="space-y-6 mb-8">
            <?php for ($i = 0; $i < $rowsPlat; $i++): ?>
            <div class="plat-card group bg-white/60 rounded-2xl p-6 border border-gray-100 shadow-sm relative animate-fade-in-down transition-all hover:bg-white/80 backdrop-blur-sm hover:shadow-md">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h4 class="text-sm font-bold text-purple-600 uppercase tracking-wider flex items-center gap-2">
                        <span class="bg-purple-100 w-8 h-8 rounded-full flex items-center justify-center text-xs">#<span class="row-index"><?php echo $i + 1; ?></span></span>
                        Plataforma
                    </h4>
                    <button type="button" onclick="removePlatCard(this)" class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-xl transition-all opacity-0 group-hover:opacity-100" title="Eliminar esta plataforma">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>

                <!-- Main Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tipo de Curso</label>
                         <select name="plat_curso[]" class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer">
                            <option value="">- Seleccione -</option>
                            <option value="Tipo A" <?php echo ($data['plat_curso'][$i] ?? '') == 'Tipo A' ? 'selected' : ''; ?>>Tipo A</option>
                            <option value="Tipo B" <?php echo ($data['plat_curso'][$i] ?? '') == 'Tipo B' ? 'selected' : ''; ?>>Tipo B</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Cuenta con plataforma?</label>
                         <select name="plat_existe[]" class="plat-existe-select w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all cursor-pointer" onchange="togglePlatformFields(this)">
                            <option value="">- Seleccione -</option>
                            <option value="Si" <?php echo ($data['plat_existe'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                            <option value="No" <?php echo ($data['plat_existe'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </div>
                    <div class="plat-conditional-fields">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Número validación plataforma</label>
                        <input type="text" name="plat_doc[]" value="<?php echo htmlspecialchars($data['plat_doc'][$i] ?? ''); ?>" 
                        class="w-full px-4 py-2 bg-white/50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all form-input-premium backdrop-blur-sm" placeholder="Ej. 12345">
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="plat-conditional-fields bg-indigo-50/50 rounded-xl p-4 border border-indigo-100">
                    <h5 class="text-xs font-bold text-indigo-800 mb-3 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Características que realiza la plataforma Tecnológica
                    </h5>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Matrícula online?</span>
                             <select name="plat_matricula[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['plat_matricula'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['plat_matricula'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                         <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Hora ingreso?</span>
                             <select name="plat_asistencia_in[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['plat_asistencia_in'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['plat_asistencia_in'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                         <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Hora salida?</span>
                             <select name="plat_asistencia_out[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['plat_asistencia_out'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['plat_asistencia_out'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Calificaciones?</span>
                             <select name="plat_calif[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['plat_calif'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['plat_calif'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Interacción?</span>
                             <select name="plat_interaccion[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['plat_interaccion'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['plat_interaccion'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                        <div class="bg-white/80 p-2.5 border border-indigo-100 rounded-lg hover:border-indigo-300 transition-colors">
                            <span class="block text-[10px] font-semibold text-gray-400 uppercase mb-1">Evaluación final?</span>
                             <select name="plat_eval[]" class="w-full text-xs bg-transparent border-none p-0 focus:ring-0 font-bold text-gray-700 cursor-pointer">
                                <option value="Si" <?php echo ($data['plat_eval'][$i] ?? '') == 'Si' ? 'selected' : ''; ?>>Si</option>
                                <option value="No" <?php echo ($data['plat_eval'][$i] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step5.php" class="w-full md:w-auto text-gray-500 hover:text-purple-600 font-medium px-6 py-3 rounded-xl hover:bg-purple-50 transition-all flex items-center justify-center gap-2 group">
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
// Toggle platform fields based on "Cuenta con plataforma" selection
function togglePlatformFields(selectElement) {
    const platCard = selectElement.closest('.plat-card');
    const conditionalFields = platCard.querySelectorAll('.plat-conditional-fields');
    
    if (selectElement.value === 'Si') {
        conditionalFields.forEach(field => {
            field.style.display = 'block';
        });
    } else {
        conditionalFields.forEach(field => {
            field.style.display = 'none';
        });
    }
}

// Initialize all platform cards on page load
document.addEventListener('DOMContentLoaded', function() {
    const allPlatSelects = document.querySelectorAll('.plat-existe-select');
    allPlatSelects.forEach(select => {
        togglePlatformFields(select);
    });
});

function addTallerCard() {
    const container = document.getElementById('taller-container');
    let templateCard = container.children[0];
    if(!templateCard) {
        alert("Error de integridad. Recargue.");
        return;
    }

    const newCard = templateCard.cloneNode(true);
    newCard.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    
    // Update index
    const indexSpan = newCard.querySelector('.row-index');
    if(indexSpan) indexSpan.innerText = container.children.length + 1;

    container.appendChild(newCard);
}

function removeTallerCard(button) {
    const container = document.getElementById('taller-container');
    if (container.children.length > 1) {
        button.closest('.taller-card').remove();
        Array.from(container.children).forEach((card, idx) => {
            const span = card.querySelector('.row-index');
            if(span) span.innerText = idx + 1;
        });
    } else {
        alert("Debe existir al menos un taller.");
    }
}

function addPlatCard() {
    const container = document.getElementById('plat-container');
    let templateCard = container.children[0];
    if(!templateCard) {
        alert("Error de integridad. Recargue.");
        return;
    }

    const newCard = templateCard.cloneNode(true);
    newCard.querySelectorAll('input').forEach(input => input.value = '');
    newCard.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    
    // Update index
    const indexSpan = newCard.querySelector('.row-index');
    if(indexSpan) indexSpan.innerText = container.children.length + 1;

    container.appendChild(newCard);
    
    // Initialize toggle for the new card
    const newSelect = newCard.querySelector('.plat-existe-select');
    if(newSelect) {
        togglePlatformFields(newSelect);
    }
}

function removePlatCard(button) {
    const container = document.getElementById('plat-container');
    if (container.children.length > 1) {
        button.closest('.plat-card').remove();
        Array.from(container.children).forEach((card, idx) => {
            const span = card.querySelector('.row-index');
            if(span) span.innerText = idx + 1;
        });
    } else {
        alert("Debe existir al menos una plataforma.");
    }
}
</script>

<?php include '../../includes/form_footer.php'; ?>

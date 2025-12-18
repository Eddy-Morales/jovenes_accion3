<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/form_header.php';

// Retrieve existing data
$data = $_SESSION['form_data']['step3'] ?? [];
?>

<div class="max-w-4xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-lg md:text-3xl text-gray-800 mb-2">3. LABORATORIOS</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 3 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Laboratorio de Computación</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 33%"></div>
        </div>
    </div>

    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-4 md:p-8 shadow-2xl border border-white/50 relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>
        <input type="hidden" name="step" value="3">
        <input type="hidden" name="next_url" value="../views/professional/step4.php">
        <input type="hidden" name="school_type" value="professional">

        <!-- 3.1 Laboratorio de Computación -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">3.1</span>
                <span>Laboratorio de Computación</span>
            </h3>
            
            <div id="labsContainer" class="space-y-6">
                <!-- Cards will be added here via JS -->
            </div>

            <button type="button" onclick="addLabCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Laboratorio
            </button>
        </div>

        <!-- 3.2 Laboratorio Psicosensométrico -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">3.2</span>
                <span>Laboratorio Psicosensométrico</span>
            </h3>
            
            <div id="psicoLabsContainer" class="space-y-6">
                <!-- Cards will be added here via JS -->
            </div>

            <button type="button" onclick="addPsicoLabCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Lab. Psicosensométrico
            </button>
        </div>

        <!-- 3.3 Equipo Psicosensométrico -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">3.3</span>
                <span>Equipo Psicosensométrico</span>
            </h3>
            
            <div id="psicoEquipContainer" class="space-y-6">
                <!-- Cards will be added here via JS -->
            </div>

            <button type="button" onclick="addPsicoEquipCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Equipo
            </button>
        </div>

        <!-- 3.4 Área de instrucción práctica (Parque Vial) -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">3.4</span>
                <span>Área de instrucción práctica (Parque Vial)</span>
            </h3>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Horizontal -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <label class="font-semibold text-gray-700 text-sm">Señalización Horizontal</label>
                        <select name="parque_horizontal" class="w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                            <option value="">Seleccione</option>
                            <option value="SI" <?php echo ($data['parque_horizontal'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                            <option value="NO" <?php echo ($data['parque_horizontal'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                        </select>
                    </div>

                    <!-- Vertical -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <label class="font-semibold text-gray-700 text-sm">Señalización Vertical</label>
                        <select name="parque_vertical" class="w-32 bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                            <option value="">Seleccione</option>
                            <option value="SI" <?php echo ($data['parque_vertical'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                            <option value="NO" <?php echo ($data['parque_vertical'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3.5 Circuito Autorizado y Emitido por el GAD -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">3.5</span>
                <span>Circuito Autorizado y Emitido por el GAD</span>
            </h3>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Número de autorización</label>
                        <input type="text" name="gad_numero" value="<?php echo htmlspecialchars($data['gad_numero'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: AUT-GAD-2024-001">
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Vigencia hasta</label>
                        <input type="date" name="gad_vigencia" value="<?php echo htmlspecialchars($data['gad_vigencia'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombre de la institución que emite la autorización</label>
                        <input type="text" name="gad_institucion" value="<?php echo htmlspecialchars($data['gad_institucion'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: GAD Municipal de Quito">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step2.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Anterior
            </a>
            <button type="submit" class="w-full md:w-auto btn-premium bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-blue-500/30 flex items-center justify-center gap-2 group">
                Siguiente Paso
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>

<!-- Template for Lab Card -->
<template id="labCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <h4 class="text-blue-600 font-bold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Laboratorio #<span class="lab-number">1</span>
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Laboratorio Nro -->
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Laboratorio Nro.</label>
                <input type="text" name="labs[index][nro]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Lab 1">
            </div>
            <!-- 2. Número de Computadores -->
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Número de computadores</label>
                <input type="number" name="labs[index][computadores]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: 15">
            </div>
            <!-- 3. Proyector -->
            <div>
                 <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Proyector</label>
                 <select name="labs[index][proyector]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm border focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                </select>
            </div>
            <!-- 4. Material Didáctico -->
            <div>
                 <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Material Didáctico</label>
                 <select name="labs[index][material]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm border focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                </select>
            </div>
             <!-- 5. Fijo -->
            <div>
                 <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Fijo</label>
                 <select name="labs[index][fijo]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm border focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                </select>
            </div>
        </div>
    </div>
</template>

<!-- Template for Psicosensometric Lab Card -->
<template id="psicoLabCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <h4 class="text-blue-600 font-bold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Lab. Psicosensométrico #<span class="psico-lab-number">1</span>
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Laboratorio Nro.</label>
                <input type="text" name="psicolabs[index][nro]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: P-101">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Evita filtraciones sonoras</label>
                <select name="psicolabs[index][sonido]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm border focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                </select>
            </div>
        </div>
    </div>
</template>

<!-- Template for Psicosensometric Equipment Card -->
<template id="psicoEquipCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <h4 class="text-blue-600 font-bold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Equipo #<span class="equip-number">1</span>
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Equipo</label>
                <input type="text" name="psicoequip[index][nro]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: EQ-001">
            </div>
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Modelo</label>
                <input type="text" name="psicoequip[index][modelo]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Modelo X">
            </div>
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Certificado Homolog.</label>
                <input type="text" name="psicoequip[index][certificado]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: CER-12345">
            </div>
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Propiedad Escuela</label>
                <input type="text" name="psicoequip[index][propiedad]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Propia, Arrendada">
            </div>
        </div>

        <div class="mt-4 bg-blue-50/50 p-3 rounded-lg border border-blue-100">
            <label class="block text-xs font-bold text-blue-700 uppercase mb-2 text-center">Evaluación</label>
            <div class="grid grid-cols-3 gap-2">
                <div>
                     <label class="block text-[10px] font-semibold text-gray-600 block mb-1">Auditiva</label>
                     <select name="psicoequip[index][eval_auditiva]" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs">
                        <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                    </select>
                </div>
                <div>
                     <label class="block text-[10px] font-semibold text-gray-600 block mb-1">Psicomotriz</label>
                     <select name="psicoequip[index][eval_psicomotriz]" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs">
                        <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                    </select>
                </div>
                <div>
                     <label class="block text-[10px] font-semibold text-gray-600 block mb-1">Visual</label>
                     <select name="psicoequip[index][eval_visual]" class="w-full bg-white border border-gray-200 rounded px-2 py-1.5 text-xs">
                        <option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
let labIndex = 0;
let psicoLabIndex = 0;
let psicoEquipIndex = 0;

const labsContainer = document.getElementById('labsContainer');
const psicoLabsContainer = document.getElementById('psicoLabsContainer');
const psicoEquipContainer = document.getElementById('psicoEquipContainer');

const existingLabs = <?php echo json_encode($data['labs'] ?? []); ?>;
const existingPsicoLabs = <?php echo json_encode($data['psicolabs'] ?? []); ?>;
const existingPsicoEquip = <?php echo json_encode($data['psicoequip'] ?? []); ?>;

function addCardGeneric(type, data = null) {
    let templateId, container, prefix, itemIndex;
    
    if (type === 'lab') {
        templateId = 'labCardTemplate';
        container = labsContainer;
        prefix = 'labs';
        itemIndex = labIndex;
    } else if (type === 'psicolab') {
        templateId = 'psicoLabCardTemplate';
        container = psicoLabsContainer;
        prefix = 'psicolabs';
        itemIndex = psicoLabIndex;
    } else if (type === 'psicoequip') {
        templateId = 'psicoEquipCardTemplate';
        container = psicoEquipContainer;
        prefix = 'psicoequip';
        itemIndex = psicoEquipIndex;
    }

    const template = document.getElementById(templateId);
    const clone = template.content.cloneNode(true);
    const card = clone.querySelector('div');
    
    // Update numbering
    const numberSpan = card.querySelector(`.${type === 'lab' ? 'lab' : (type === 'psicolab' ? 'psico-lab' : 'equip')}-number`);
    if(numberSpan) numberSpan.textContent = itemIndex + 1;

    // Replace index placeholder
    const inputs = card.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.name = input.name.replace('index', itemIndex);
        
        // Populate if data exists
        if (data) {
            const keys = input.name.match(/\[(\w+)\]$/);
            if (keys && keys[1]) {
                const key = keys[1];
                input.value = data[key] || '';
            }
        }
    });

    container.appendChild(card);
    
    // Increment index
    if (type === 'lab') labIndex++;
    else if (type === 'psicolab') psicoLabIndex++;
    else if (type === 'psicoequip') psicoEquipIndex++;
}

// Global wrappers for button onclicks
function addLabCard() { addCardGeneric('lab'); }
function addPsicoLabCard() { addCardGeneric('psicolab'); }
function addPsicoEquipCard() { addCardGeneric('psicoequip'); }

// Initialize
if (existingLabs.length > 0) existingLabs.forEach(item => addCardGeneric('lab', item));
else addCardGeneric('lab');

if (existingPsicoLabs.length > 0) existingPsicoLabs.forEach(item => addCardGeneric('psicolab', item));
else addCardGeneric('psicolab');

if (existingPsicoEquip.length > 0) existingPsicoEquip.forEach(item => addCardGeneric('psicoequip', item));
else addCardGeneric('psicoequip');
</script>

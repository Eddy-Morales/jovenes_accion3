<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/form_header.php';

// Retrieve existing data
$data = $_SESSION['form_data']['step5'] ?? [];
?>

<div class="max-w-6xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-lg md:text-3xl text-gray-800 mb-2">5. EQUIPAMIENTO TECNOLÓGICO Y VEHICULAR</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 5 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Biometría, Simuladores y Vehículos</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 55%"></div>
        </div>
    </div>

    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-4 md:p-8 shadow-2xl border border-white/50 relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>
        <input type="hidden" name="step" value="5">
        <input type="hidden" name="next_url" value="../views/professional/step6.php">
        <input type="hidden" name="school_type" value="professional">

        <!-- 5.1 Equipo Biométrico -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">5.1</span>
                <span>Equipo Biométrico</span>
            </h3>
            
            <div id="biometricContainer" class="space-y-6">
                <!-- Cards added via JS -->
            </div>

            <button type="button" onclick="addBiometricCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Equipo Biométrico
            </button>
        </div>

        <!-- 5.2 Equipo Simulador -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">5.2</span>
                <span>Equipo Simulador</span>
            </h3>
            
            <div id="simulatorContainer" class="space-y-6">
                <!-- Cards added via JS -->
            </div>

            <button type="button" onclick="addSimulatorCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Simulador
            </button>
        </div>

        <!-- 5.3 Equipamiento Vehicular -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">5.3</span>
                <span>Equipamiento Vehicular (Cursos Tipo)</span>
            </h3>
            
            <div id="vehicleContainer" class="space-y-6">
                <!-- Cards added via JS -->
            </div>

            <button type="button" onclick="addVehicleCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Vehículo
            </button>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step4.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
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

<!-- Template: Biometric -->
<template id="biometricCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        <h4 class="text-blue-600 font-bold mb-4">Equipo Biométrico #<span class="bio-number">1</span></h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Equipo Nro. (ID)</label>
                <input type="text" name="biometric[index][nro]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="Ingrese ID único">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Frecuencia de acceso</label>
                <input type="text" name="biometric[index][frecuencia]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="Ej: Diaria, Semanal">
            </div>
            <!-- Checkboxes group -->
            <div class="md:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-4 bg-blue-50/50 p-4 rounded-lg">
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-500 mb-1">Asis. Docentes</span>
                    <select name="biometric[index][asis_docentes]" class="bg-white border text-sm rounded p-1 cursor-pointer"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-500 mb-1">Asis. Instructores</span>
                    <select name="biometric[index][asis_instructores]" class="bg-white border text-sm rounded p-1 cursor-pointer"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-500 mb-1">Asis. Alumnos</span>
                    <select name="biometric[index][asis_alumnos]" class="bg-white border text-sm rounded p-1 cursor-pointer"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-gray-500 mb-1">Conectado Internet</span>
                    <select name="biometric[index][internet]" class="bg-white border text-sm rounded p-1 cursor-pointer"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Template: Simulator -->
<template id="simulatorCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        <h4 class="text-blue-600 font-bold mb-4">Simulador #<span class="sim-number">1</span></h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Marca</label>
                <input type="text" name="simulator[index][marca]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="Marca del fabricante">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Modelo</label>
                <input type="text" name="simulator[index][modelo]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="Modelo del equipo">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Certificado Homolog.</label>
                <input type="text" name="simulator[index][certificado]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nro de Certificado ANT">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tipo de cursos (Uso)</label>
                <input type="text" name="simulator[index][tipo_cursos]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="Ej: Tipo B, C, D, E">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Usado en Clases Prácticas</label>
                 <select name="simulator[index][uso_practico]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">% de Uso (por alumno)</label>
                <input type="text" name="simulator[index][porcentaje]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="Ej: 20%">
            </div>
        </div>
    </div>
</template>

<!-- Template: Vehicle -->
<template id="vehicleCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        <h4 class="text-blue-600 font-bold mb-4">Vehículo #<span class="veh-number">1</span></h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Row 1 -->
            <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Modelo</label>
                 <input type="text" name="vehicles[index][modelo]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm" placeholder="Modelo del vehículo">
            </div>
            <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Placa</label>
                 <input type="text" name="vehicles[index][placa]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm" placeholder="AAA-1234">
            </div>
            <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Resolución Nro.</label>
                 <input type="text" name="vehicles[index][resolucion]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm" placeholder="Nro de Resolución">
            </div>
            <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Vigencia Convenio</label>
                 <input type="date" name="vehicles[index][vigencia]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm">
            </div>

            <!-- Row 2 -->
             <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Año Fab.</label>
                 <input type="number" name="vehicles[index][anio]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm" placeholder="Ej: 2020">
            </div>
             <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Logos Ident.</label>
                 <input type="text" name="vehicles[index][logos]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm" placeholder="SI/NO o Detalle">
            </div>
             <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Doble Comando</label>
                  <select name="vehicles[index][doble_comando]" class="w-full bg-white border border-gray-200 rounded p-2 text-sm text-xs cursor-pointer"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
            </div>
             <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">100% Póliza</label>
                  <select name="vehicles[index][poliza_100]" class="w-full bg-white border border-gray-200 rounded p-2 text-sm text-xs cursor-pointer"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
            </div>

            <!-- Row 3 -->
            <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Vigencia Póliza</label>
                 <input type="text" name="vehicles[index][poliza_periodo]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm" placeholder="Desde - Hasta (Fechas)">
            </div>
            <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">F. Matrícula</label>
                 <input type="date" name="vehicles[index][fecha_matricula]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm">
            </div>
            <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">F. Rev. Técnica</label>
                 <input type="date" name="vehicles[index][fecha_revision]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm">
            </div>
             <div>
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Uso fines ajenos</label>
                  <select name="vehicles[index][fines_ajenos]" class="w-full bg-white border border-gray-200 rounded p-2 text-sm text-xs cursor-pointer"><option value="">Seleccione</option><option value="SI">SI</option><option value="NO">NO</option></select>
            </div>
            
            <div class="md:col-span-4">
                 <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Mantenimiento (Casa/Fabricante)</label>
                  <input type="text" name="vehicles[index][mantenimiento]" class="w-full bg-gray-50 border border-gray-200 rounded p-2 text-sm" placeholder="Detalle del mantenimiento preventivo y correctivo...">
            </div>
        </div>
    </div>
</template>

<script>
let bioIndex = 0;
let simIndex = 0;
let vehIndex = 0;

const biometricContainer = document.getElementById('biometricContainer');
const simulatorContainer = document.getElementById('simulatorContainer');
const vehicleContainer = document.getElementById('vehicleContainer');

const existingBiometric = <?php echo json_encode($data['biometric'] ?? []); ?>;
const existingSimulator = <?php echo json_encode($data['simulator'] ?? []); ?>;
const existingVehicles = <?php echo json_encode($data['vehicles'] ?? []); ?>;

function addCardGeneric(type, data = null) {
    let templateId, container, prefix, itemIndex;
    if (type === 'biometric') { templateId = 'biometricCardTemplate'; container = biometricContainer; itemIndex = bioIndex; }
    else if (type === 'simulator') { templateId = 'simulatorCardTemplate'; container = simulatorContainer; itemIndex = simIndex; }
    else if (type === 'vehicle') { templateId = 'vehicleCardTemplate'; container = vehicleContainer; itemIndex = vehIndex; }

    const template = document.getElementById(templateId);
    const clone = template.content.cloneNode(true);
    const card = clone.querySelector('div');
    
    // Update numbering
    const numberSpan = card.querySelector(`.${type === 'biometric' ? 'bio' : (type === 'simulator' ? 'sim' : 'veh')}-number`);
    if(numberSpan) numberSpan.textContent = itemIndex + 1;

    // Replace index
    const inputs = card.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.name = input.name.replace('index', itemIndex);
        if (data) {
            const keys = input.name.match(/\[(\w+)\]$/);
            if (keys && keys[1]) input.value = data[keys[1]] || '';
        }
    });

    container.appendChild(card);
    if (type === 'biometric') bioIndex++;
    else if (type === 'simulator') simIndex++;
    else if (type === 'vehicle') vehIndex++;
}

function addBiometricCard() { addCardGeneric('biometric'); }
function addSimulatorCard() { addCardGeneric('simulator'); }
function addVehicleCard() { addCardGeneric('vehicle'); }

// Init
if(existingBiometric.length) existingBiometric.forEach(d => addCardGeneric('biometric', d)); else addCardGeneric('biometric');
if(existingSimulator.length) existingSimulator.forEach(d => addCardGeneric('simulator', d)); else addCardGeneric('simulator');
if(existingVehicles.length) existingVehicles.forEach(d => addCardGeneric('vehicle', d)); else addCardGeneric('vehicle');
</script>

<?php include '../../includes/form_footer.php'; ?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/form_header.php';

// Retrieve existing data
$data = $_SESSION['form_data']['step4'] ?? [];
?>

<div class="max-w-4xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-lg md:text-3xl text-gray-800 mb-2">4. ÁREAS COMPLEMENTARIAS</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 4 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Servicios y Espacios Comunes</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 44%"></div>
        </div>
    </div>

    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-4 md:p-8 shadow-2xl border border-white/50 relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>
        <input type="hidden" name="step" value="4">
        <input type="hidden" name="next_url" value="../views/professional/step5.php">
        <input type="hidden" name="school_type" value="professional">

        <!-- 4.1 Baterías Sanitarias -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">4.1</span>
                <span>Baterías Sanitarias</span>
            </h3>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Destinado a Hombres (Nro)</label>
                        <input type="number" name="baterias_hombres" value="<?php echo htmlspecialchars($data['baterias_hombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Destinado a Mujeres (Nro)</label>
                        <input type="number" name="baterias_mujeres" value="<?php echo htmlspecialchars($data['baterias_mujeres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Limpieza</label>
                        <select name="baterias_limpieza" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                            <option value="">Seleccione</option>
                            <option value="Excelente" <?php echo ($data['baterias_limpieza'] ?? '') == 'Excelente' ? 'selected' : ''; ?>>Excelente</option>
                            <option value="Bueno" <?php echo ($data['baterias_limpieza'] ?? '') == 'Bueno' ? 'selected' : ''; ?>>Bueno</option>
                            <option value="Malo" <?php echo ($data['baterias_limpieza'] ?? '') == 'Malo' ? 'selected' : ''; ?>>Malo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4.2 Bar - Cafetería -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">4.2</span>
                <span>Bar - Cafetería</span>
            </h3>
            
            <div id="cafeteriaContainer" class="space-y-6">
                <!-- Dynamic cards added here -->
            </div>

            <button type="button" onclick="addCafeteriaCard()" class="mt-6 w-full md:w-auto text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 font-medium rounded-xl px-4 py-3 text-sm flex items-center justify-center gap-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Cafetería
            </button>
        </div>

        <!-- 4.3 Parqueadero -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">4.3</span>
                <span>Parqueadero</span>
            </h3>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Parqueadero destinado para:</th>
                            <th class="px-6 py-4 w-48 text-center">Nro. de Estacionamientos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-6 py-4 text-gray-700">Vehículos de instrucción práctica</td>
                            <td class="px-6 py-4">
                                <input type="number" name="parking_instruccion" value="<?php echo htmlspecialchars($data['parking_instruccion'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-100 outline-none" placeholder="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-gray-700">Vehículos de funcionarios</td>
                            <td class="px-6 py-4">
                                <input type="number" name="parking_funcionarios" value="<?php echo htmlspecialchars($data['parking_funcionarios'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-100 outline-none" placeholder="0">
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-gray-700">Vehículos usuarios</td>
                            <td class="px-6 py-4">
                                <input type="number" name="parking_usuarios" value="<?php echo htmlspecialchars($data['parking_usuarios'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-100 outline-none" placeholder="0">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4.4 Área de Recreación -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-3 mb-6 flex items-center gap-2">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0">4.4</span>
                <span>Área de Recreación</span>
            </h3>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">¿Cuenta con área?</label>
                        <select name="recreacion_estado" id="recreacion_estado" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                            <option value="">Seleccione</option>
                            <option value="SI" <?php echo ($data['recreacion_estado'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                            <option value="NO" <?php echo ($data['recreacion_estado'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                        </select>
                    </div>
                    <div class="md:col-span-3" id="recreacion_detalle_container" style="display: none;">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Detalle del área de recreación</label>
                        <textarea name="recreacion_detalle" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Describa brevemente el área de recreación..."><?php echo htmlspecialchars($data['recreacion_detalle'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recSelect = document.getElementById('recreacion_estado');
            const recContainer = document.getElementById('recreacion_detalle_container');

            function toggleRecreacion() {
                if (recSelect.value === 'SI') {
                    recContainer.style.display = 'block';
                } else {
                    recContainer.style.display = 'none';
                }
            }

            recSelect.addEventListener('change', toggleRecreacion);
            toggleRecreacion(); // Initialize on load
        });
        </script>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step3.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
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

<!-- Template for Cafeteria Card -->
<template id="cafeteriaCardTemplate">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 relative group transition-all hover:shadow-md animate-fade-in-up">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors bg-white rounded-full p-1 border border-gray-100 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <h4 class="text-blue-600 font-bold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Cafetería #<span class="cafeteria-number">1</span>
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cafetería Nro.</label>
                <input type="text" name="cafeterias[index][nro]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ej: Local 1">
            </div>
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Limpieza</label>
                <select name="cafeterias[index][limpieza]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                    <option value="">Seleccione</option>
                    <option value="Excelente">Excelente</option>
                    <option value="Bueno">Bueno</option>
                    <option value="Malo">Malo</option>
                </select>
            </div>
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Permiso Sanitario</label>
                <input type="text" name="cafeterias[index][permiso]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro de Permiso">
            </div>
            <div class="lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Vigencia Hasta</label>
                <input type="date" name="cafeterias[index][vigencia]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
            </div>
        </div>
    </div>
</template>

<script>
let cafeteriaIndex = 0;
const cafeteriaContainer = document.getElementById('cafeteriaContainer');
const existingCafeterias = <?php echo json_encode($data['cafeterias'] ?? []); ?>;

function addCafeteriaCard(data = null) {
    const template = document.getElementById('cafeteriaCardTemplate');
    const clone = template.content.cloneNode(true);
    const card = clone.querySelector('div');
    
    // Update numbering
    const numberSpan = card.querySelector('.cafeteria-number');
    numberSpan.textContent = cafeteriaIndex + 1;

    // Replace index placeholder
    const inputs = card.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.name = input.name.replace('index', cafeteriaIndex);
        
        // Populate if data exists
        if (data) {
            const keys = input.name.match(/\[(\w+)\]$/);
            if (keys && keys[1]) {
                const key = keys[1];
                input.value = data[key] || '';
            }
        }
    });

    cafeteriaContainer.appendChild(card);
    cafeteriaIndex++;
}

// Initialize
if (existingCafeterias.length > 0) {
    existingCafeterias.forEach(item => addCafeteriaCard(item));
} else {
    addCafeteriaCard(); // Add one default card
}
</script>

<?php include '../../includes/form_footer.php'; ?>

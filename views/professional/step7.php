<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/form_header.php';

// Retrieve existing data
$data = $_SESSION['form_data']['step7'] ?? [];
?>

<div class="max-w-7xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-lg md:text-3xl text-gray-800 mb-2">7. CONSEJO ACADÉMICO Y PERSONAL DOCENTE</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 7 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Talento Humano (Cont.)</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 77%"></div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Ingrese la información del Consejo Académico, Docentes e Instructores. Agregue tantos registros como sea necesario.</p>
    </div>

    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-4 md:p-8 shadow-2xl border border-white/50 relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>
        <input type="hidden" name="step" value="7">
        <input type="hidden" name="next_url" value="../views/professional/step8.php">
        <input type="hidden" name="school_type" value="professional">

        <!-- 10. Consejo Académico -->
        <div class="mb-10">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full text-sm">10</span>
                Consejo Académico
            </h3>
            
            <div id="consejo-container" class="space-y-4">
                <!-- Dynamic cards will be inserted here -->
            </div>

            <button type="button" onclick="addConsejoCard()" class="mt-4 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium transition-colors flex items-center gap-2 border border-blue-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Miembro
            </button>
        </div>

        <hr class="my-8 border-gray-200">

        <!-- 11. Personal Docente -->
        <div class="mb-10">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-cyan-600 text-white w-8 h-8 flex items-center justify-center rounded-full text-sm">11</span>
                Personal Docente
            </h3>
            
            <div id="docentes-container" class="space-y-4">
                <!-- Dynamic cards will be inserted here -->
            </div>

            <button type="button" onclick="addDocenteCard()" class="mt-4 px-4 py-2 bg-cyan-50 text-cyan-600 rounded-lg hover:bg-cyan-100 font-medium transition-colors flex items-center gap-2 border border-cyan-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Docente
            </button>
        </div>

        <hr class="my-8 border-gray-200">

        <!-- 12. Instructores -->
         <div class="mb-10">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="bg-teal-600 text-white w-8 h-8 flex items-center justify-center rounded-full text-sm">12</span>
                Instructores
            </h3>
            
            <div id="instructores-container" class="space-y-4">
                <!-- Dynamic cards will be inserted here -->
            </div>

            <button type="button" onclick="addInstructorCard()" class="mt-4 px-4 py-2 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 font-medium transition-colors flex items-center gap-2 border border-teal-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Agregar Instructor
            </button>
        </div>


        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step6.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
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

<!-- Templates for Dynamic Cards -->
<template id="consejo-template">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-blue-300 transition-all">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
             <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                <input type="text" name="consejo_nombres[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                <input type="text" name="consejo_cedula[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" maxlength="10">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                <input type="text" name="consejo_cargo[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
            </div>
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Delegación: Nro. Acta</label>
                <input type="text" name="consejo_acta[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
            </div>
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Delegación: Fecha</label>
                <input type="date" name="consejo_fecha[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none text-gray-500">
            </div>
        </div>
    </div>
</template>

<template id="docente-template">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-cyan-300 transition-all">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tipo de Curso</label>
                <select name="docente_tipo_curso[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none cursor-pointer">
                    <option value="">Seleccione</option>
                    <option value="Licencia Tipo A">Licencia Tipo A</option>
                    <option value="Licencia Tipo B">Licencia Tipo B</option>
                    <option value="Licencia Tipo C">Licencia Tipo C</option>
                    <option value="Licencia Tipo C1">Licencia Tipo C1</option>
                    <option value="Licencia Tipo D">Licencia Tipo D</option>
                    <option value="Licencia Tipo E">Licencia Tipo E</option>
                    <option value="Licencia Tipo G">Licencia Tipo G</option>
                    <option value="Convalidación">Convalidación</option>
                    <option value="Recuperación de Puntos">Recuperación de Puntos</option>
                </select>
            </div>
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cátedra</label>
                <input type="text" name="docente_catedra[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none">
            </div>
             <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                <input type="text" name="docente_nombres[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                <input type="text" name="docente_cedula[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none" maxlength="10">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                 <input type="text" name="docente_titulo[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none">
            </div>
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                 <input type="text" name="docente_senescyt[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Exp. Área Docencia</label>
                 <input type="text" name="docente_experiencia[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none" placeholder="Años/Meses">
            </div>
             <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple Requisitos</label>
                 <select name="docente_cumple[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-100 outline-none cursor-pointer font-bold text-cyan-600">
                    <option value="">Seleccione</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
        </div>
    </div>
</template>

<template id="instructor-template">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-teal-300 transition-all">
        <button type="button" onclick="this.closest('.bg-white').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </button>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
             <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                <input type="text" name="instructor_nombres[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                <input type="text" name="instructor_cedula[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none" maxlength="10">
            </div>
            <div>
                 <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tipo de Licencia</label>
                 <select name="instructor_tipo_licencia[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none cursor-pointer">
                    <option value="">Seleccione</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="C1">C1</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="G">G</option>
                 </select>
            </div>
            <div>
                 <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tipo de Curso a Capacitar</label>
                 <input type="text" name="instructor_tipo_curso[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Número de Puntos</label>
                <input type="number" name="instructor_puntos[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none" placeholder="30">
            </div>
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Experiencia (Años)</label>
                <input type="number" name="instructor_experiencia[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none">
            </div>
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Fecha Emisión Licencia</label>
                <input type="date" name="instructor_fecha_emision[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none text-gray-500">
            </div>
             <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Fecha Caducidad Licencia</label>
                <input type="date" name="instructor_fecha_caducidad[]" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none text-gray-500">
            </div>
             <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Solvencia Moral</label>
                 <select name="instructor_solvencia[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none cursor-pointer">
                    <option value="">Seleccione</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
             <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Certificado Instructor</label>
                 <select name="instructor_certificado[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none cursor-pointer">
                    <option value="">Seleccione</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
             <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple Requisitos</label>
                 <select name="instructor_cumple[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-100 outline-none cursor-pointer font-bold text-teal-600">
                    <option value="">Seleccione</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
const sessionData = <?php echo json_encode($data); ?>;

function addConsejoCard(data = {}) {
    const container = document.getElementById('consejo-container');
    const template = document.getElementById('consejo-template');
    const clone = template.content.cloneNode(true);
    
    if (Object.keys(data).length > 0) {
        clone.querySelector('input[name="consejo_nombres[]"]').value = data.nombres || '';
        clone.querySelector('input[name="consejo_cedula[]"]').value = data.cedula || '';
        clone.querySelector('input[name="consejo_cargo[]"]').value = data.cargo || '';
        clone.querySelector('input[name="consejo_acta[]"]').value = data.acta || '';
        clone.querySelector('input[name="consejo_fecha[]"]').value = data.fecha || '';
    }
    
    container.appendChild(clone);
}

function addDocenteCard(data = {}) {
    const container = document.getElementById('docentes-container');
    const template = document.getElementById('docente-template');
    const clone = template.content.cloneNode(true);
    
    if (Object.keys(data).length > 0) {
        clone.querySelector('select[name="docente_tipo_curso[]"]').value = data.tipo_curso || '';
        clone.querySelector('input[name="docente_catedra[]"]').value = data.catedra || '';
        clone.querySelector('input[name="docente_nombres[]"]').value = data.nombres || '';
        clone.querySelector('input[name="docente_cedula[]"]').value = data.cedula || '';
        clone.querySelector('input[name="docente_titulo[]"]').value = data.titulo || '';
        clone.querySelector('input[name="docente_senescyt[]"]').value = data.senescyt || '';
        clone.querySelector('input[name="docente_experiencia[]"]').value = data.experiencia || '';
        clone.querySelector('select[name="docente_cumple[]"]').value = data.cumple || '';
    }
    
    container.appendChild(clone);
}

function addInstructorCard(data = {}) {
    const container = document.getElementById('instructores-container');
    const template = document.getElementById('instructor-template');
    const clone = template.content.cloneNode(true);
    
    if (Object.keys(data).length > 0) {
        clone.querySelector('input[name="instructor_nombres[]"]').value = data.nombres || '';
        clone.querySelector('input[name="instructor_cedula[]"]').value = data.cedula || '';
        clone.querySelector('select[name="instructor_tipo_licencia[]"]').value = data.tipo_licencia || '';
        clone.querySelector('input[name="instructor_tipo_curso[]"]').value = data.tipo_curso || '';

        clone.querySelector('input[name="instructor_puntos[]"]').value = data.puntos || '';
        clone.querySelector('input[name="instructor_experiencia[]"]').value = data.experiencia || '';
        clone.querySelector('input[name="instructor_fecha_emision[]"]').value = data.fecha_emision || '';
        clone.querySelector('input[name="instructor_fecha_caducidad[]"]').value = data.fecha_caducidad || '';
        clone.querySelector('select[name="instructor_solvencia[]"]').value = data.solvencia || '';
        clone.querySelector('select[name="instructor_certificado[]"]').value = data.certificado || '';
        clone.querySelector('select[name="instructor_cumple[]"]').value = data.cumple || '';
    }
    
    container.appendChild(clone);
}

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Consejo
    if (sessionData.consejo_nombres && sessionData.consejo_nombres.length > 0) {
        sessionData.consejo_nombres.forEach((_, i) => {
            addConsejoCard({
                nombres: sessionData.consejo_nombres[i],
                cedula: sessionData.consejo_cedula[i],
                cargo: sessionData.consejo_cargo[i],
                acta: sessionData.consejo_acta[i],
                fecha: sessionData.consejo_fecha[i]
            });
        });
    } else {
        addConsejoCard(); // Default empty card
    }

    // Initialize Docentes
    if (sessionData.docente_nombres && sessionData.docente_nombres.length > 0) {
        sessionData.docente_nombres.forEach((_, i) => {
            addDocenteCard({
                tipo_curso: sessionData.docente_tipo_curso[i],
                catedra: sessionData.docente_catedra[i],
                nombres: sessionData.docente_nombres[i],
                cedula: sessionData.docente_cedula[i],
                titulo: sessionData.docente_titulo[i],
                senescyt: sessionData.docente_senescyt[i],
                experiencia: sessionData.docente_experiencia[i],
                cumple: sessionData.docente_cumple[i]
            });
        });
    } else {
        addDocenteCard();
    }

    // Initialize Instructores
    if (sessionData.instructor_nombres && sessionData.instructor_nombres.length > 0) {
        sessionData.instructor_nombres.forEach((_, i) => {
            addInstructorCard({
                nombres: sessionData.instructor_nombres[i],
                cedula: sessionData.instructor_cedula[i],
                tipo_licencia: sessionData.instructor_tipo_licencia[i],
                tipo_curso: sessionData.instructor_tipo_curso[i],

                puntos: sessionData.instructor_puntos[i],
                experiencia: sessionData.instructor_experiencia[i],
                fecha_emision: sessionData.instructor_fecha_emision[i],
                fecha_caducidad: sessionData.instructor_fecha_caducidad[i],
                solvencia: sessionData.instructor_solvencia[i],
                certificado: sessionData.instructor_certificado[i],
                cumple: sessionData.instructor_cumple[i]
            });
        });
    } else {
        addInstructorCard();
    }
});
</script>

<?php include '../../includes/form_footer.php'; ?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
include '../../includes/form_header.php';

// Retrieve existing data
$data = $_SESSION['form_data']['step6'] ?? [];
?>

<div class="max-w-6xl mx-auto">
    <!-- Progress Header -->
    <div class="mb-8 text-center animate-fade-in-down">
        <h2 class="text-3xl font-bold text-lg md:text-3xl text-gray-800 mb-2">6. TALENTO HUMANO - PERSONAL ADMINISTRATIVO</h2>
        <div class="flex items-center justify-center gap-2 text-sm font-medium text-blue-600 mb-4">
            <span class="bg-blue-100 px-3 py-1 rounded-full">Paso 6 de 9</span>
            <span class="text-gray-400">|</span>
            <span>Directivos y Asesores</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 max-w-lg mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 66%"></div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Complete la información de cada cargo administrativo. Si un cargo no aplica, puede dejarlo en blanco.</p>
    </div>

    <form action="../../actions/save_progress.php" method="POST" class="glass rounded-3xl p-4 md:p-8 shadow-2xl border border-white/50 relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-400"></div>
        <input type="hidden" name="step" value="6">
        <input type="hidden" name="next_url" value="../views/professional/step7.php">
        <input type="hidden" name="school_type" value="professional">

        <!-- 6.1 Director/a Administrativo/a -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.1 Director/a Administrativo/a
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="dir_admin_cargo" value="<?php echo htmlspecialchars($data['dir_admin_cargo'] ?? 'Director/a Administrativo/a'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="dir_admin_nombres" value="<?php echo htmlspecialchars($data['dir_admin_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="dir_admin_cedula" value="<?php echo htmlspecialchars($data['dir_admin_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="dir_admin_titulo" value="<?php echo htmlspecialchars($data['dir_admin_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Título académico">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="dir_admin_senescyt" value="<?php echo htmlspecialchars($data['dir_admin_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Exp. Gestión Formación Conductores</label>
                    <select name="dir_admin_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['dir_admin_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['dir_admin_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                 <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Solvencia e Idoneidad Moral</label>
                    <select name="dir_admin_solvencia" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['dir_admin_solvencia'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['dir_admin_solvencia'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="dir_admin_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['dir_admin_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['dir_admin_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6.2 Director/a Pedagógico/a -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-cyan-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.2 Director/a Pedagógico/a
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="dir_ped_cargo" value="<?php echo htmlspecialchars($data['dir_ped_cargo'] ?? 'Director/a Pedagógico/a'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="dir_ped_nombres" value="<?php echo htmlspecialchars($data['dir_ped_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="dir_ped_cedula" value="<?php echo htmlspecialchars($data['dir_ped_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="dir_ped_titulo" value="<?php echo htmlspecialchars($data['dir_ped_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Tercer Nivel en Ciencias de la Educación">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="dir_ped_senescyt" value="<?php echo htmlspecialchars($data['dir_ped_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Exp. >3 años Admin Educativa</label>
                    <select name="dir_ped_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['dir_ped_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['dir_ped_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                 <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Solvencia e Idoneidad Moral</label>
                    <select name="dir_ped_solvencia" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['dir_ped_solvencia'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['dir_ped_solvencia'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="dir_ped_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['dir_ped_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['dir_ped_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6.3 Tesorero/a -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-teal-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.3 Tesorero/a
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="tesorero_cargo" value="<?php echo htmlspecialchars($data['tesorero_cargo'] ?? 'Tesorero/a'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="tesorero_nombres" value="<?php echo htmlspecialchars($data['tesorero_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="tesorero_cedula" value="<?php echo htmlspecialchars($data['tesorero_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="tesorero_titulo" value="<?php echo htmlspecialchars($data['tesorero_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Tercer nivel en materia financiera">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="tesorero_senescyt" value="<?php echo htmlspecialchars($data['tesorero_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Exp. acreditada en la materia</label>
                    <select name="tesorero_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['tesorero_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['tesorero_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                 <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Caución del cargo</label>
                    <select name="tesorero_caucion" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['tesorero_caucion'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['tesorero_caucion'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="tesorero_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['tesorero_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['tesorero_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6.4 Secretario/a -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.4 Secretario/a
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="secretario_cargo" value="<?php echo htmlspecialchars($data['secretario_cargo'] ?? 'Secretario/a'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="secretario_nombres" value="<?php echo htmlspecialchars($data['secretario_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="secretario_cedula" value="<?php echo htmlspecialchars($data['secretario_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="secretario_titulo" value="<?php echo htmlspecialchars($data['secretario_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Espec. técnica o afines">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="secretario_senescyt" value="<?php echo htmlspecialchars($data['secretario_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Experiencia acreditada en la materia</label>
                    <select name="secretario_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['secretario_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['secretario_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="secretario_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['secretario_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['secretario_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6.5 Asesor Técnico en Educación y Seguridad Vial -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-orange-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.5 Asesor Técnico en Educación y Seguridad Vial
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="asesor_cargo" value="<?php echo htmlspecialchars($data['asesor_cargo'] ?? 'Asesor Técnico'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="asesor_nombres" value="<?php echo htmlspecialchars($data['asesor_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="asesor_cedula" value="<?php echo htmlspecialchars($data['asesor_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="asesor_titulo" value="<?php echo htmlspecialchars($data['asesor_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Título en educación y seguridad vial">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="asesor_senescyt" value="<?php echo htmlspecialchars($data['asesor_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Conocimientos/Exp. en educación y seguridad vial</label>
                    <select name="asesor_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['asesor_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['asesor_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="asesor_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['asesor_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['asesor_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>



        <!-- 6.6 Inspector/a -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-purple-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.6 Inspector/a
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="inspector_cargo" value="<?php echo htmlspecialchars($data['inspector_cargo'] ?? 'Inspector/a'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="inspector_nombres" value="<?php echo htmlspecialchars($data['inspector_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
        
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="inspector_cedula" value="<?php echo htmlspecialchars($data['inspector_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="inspector_titulo" value="<?php echo htmlspecialchars($data['inspector_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Tercer Nivel en Ciencias de la Educación">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="inspector_senescyt" value="<?php echo htmlspecialchars($data['inspector_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Experiencia docente mínima de 3 años</label>
                    <select name="inspector_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['inspector_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['inspector_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="inspector_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['inspector_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['inspector_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6.7 Contador/a -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-pink-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.7 Contador/a
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="contador_cargo" value="<?php echo htmlspecialchars($data['contador_cargo'] ?? 'Contador/a'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="contador_nombres" value="<?php echo htmlspecialchars($data['contador_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="contador_cedula" value="<?php echo htmlspecialchars($data['contador_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="contador_titulo" value="<?php echo htmlspecialchars($data['contador_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Contador público autorizado (CPA) tercer nivel">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="contador_senescyt" value="<?php echo htmlspecialchars($data['contador_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Experiencia acreditada</label>
                    <select name="contador_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['contador_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['contador_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="contador_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['contador_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['contador_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6.8 Evaluador Psicosensométrico -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-yellow-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.8 Evaluador Psicosensométrico
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="eval_psico_cargo" value="<?php echo htmlspecialchars($data['eval_psico_cargo'] ?? 'Evaluador Psicosensométrico'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="eval_psico_nombres" value="<?php echo htmlspecialchars($data['eval_psico_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="eval_psico_cedula" value="<?php echo htmlspecialchars($data['eval_psico_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="eval_psico_titulo" value="<?php echo htmlspecialchars($data['eval_psico_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Tercer nivel en Medicina General">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="eval_psico_senescyt" value="<?php echo htmlspecialchars($data['eval_psico_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Exp. mínima de dos años</label>
                    <select name="eval_psico_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['eval_psico_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['eval_psico_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                 <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cert. manejo equipos fabricante</label>
                    <select name="eval_psico_cert" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['eval_psico_cert'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['eval_psico_cert'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="eval_psico_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['eval_psico_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['eval_psico_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

         <!-- 6.9 Evaluador Psicológico -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.9 Evaluador Psicológico
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="eval_psicol_cargo" value="<?php echo htmlspecialchars($data['eval_psicol_cargo'] ?? 'Evaluador Psicológico'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="eval_psicol_nombres" value="<?php echo htmlspecialchars($data['eval_psicol_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="eval_psicol_cedula" value="<?php echo htmlspecialchars($data['eval_psicol_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="eval_psicol_titulo" value="<?php echo htmlspecialchars($data['eval_psicol_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Tercer nivel en Psicología">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="eval_psicol_senescyt" value="<?php echo htmlspecialchars($data['eval_psicol_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Experiencia mínima de dos años</label>
                    <select name="eval_psicol_exp" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['eval_psicol_exp'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['eval_psicol_exp'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="eval_psicol_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['eval_psicol_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['eval_psicol_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 6.10 Recepcionista -->
        <div class="mb-10 bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative">
             <div class="absolute -top-3 left-4 bg-green-600 text-white px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                9.10 Recepcionista
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-4">
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cargo</label>
                    <input type="text" name="recepcionista_cargo" value="<?php echo htmlspecialchars($data['recepcionista_cargo'] ?? 'Recepcionista'); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nombres y Apellidos</label>
                    <input type="text" name="recepcionista_nombres" value="<?php echo htmlspecialchars($data['recepcionista_nombres'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Ingrese nombre completo">
                </div>
                <div>
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cédula de Identidad</label>
                    <input type="text" name="recepcionista_cedula" value="<?php echo htmlspecialchars($data['recepcionista_cedula'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="10 dígitos">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Título</label>
                    <input type="text" name="recepcionista_titulo" value="<?php echo htmlspecialchars($data['recepcionista_titulo'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Título académico">
                </div>
                 <div class="lg:col-span-2">
                     <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nro. Registro SENESCYT</label>
                    <input type="text" name="recepcionista_senescyt" value="<?php echo htmlspecialchars($data['recepcionista_senescyt'] ?? ''); ?>" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nro Registro">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1 leading-tight">Cumple con los requisitos normativa vigente</label>
                    <select name="recepcionista_cumple" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-100 outline-none cursor-pointer font-bold text-blue-600">
                        <option value="">Seleccione</option>
                        <option value="SI" <?php echo ($data['recepcionista_cumple'] ?? '') == 'SI' ? 'selected' : ''; ?>>SI</option>
                        <option value="NO" <?php echo ($data['recepcionista_cumple'] ?? '') == 'NO' ? 'selected' : ''; ?>>NO</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="step5.php" class="w-full md:w-auto text-gray-500 hover:text-blue-600 font-medium px-6 py-3 rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 group">
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

<?php include '../../includes/form_footer.php'; ?>

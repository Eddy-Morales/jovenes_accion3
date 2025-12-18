<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
// Use new premium header with sticky nav
include '../../includes/form_header.php';

$all_data = $_SESSION['form_data'] ?? [];
?>

<div class="max-w-4xl mx-auto">
    <div class="glass rounded-3xl p-12 shadow-2xl border border-white/50 relative overflow-hidden text-center animate-fade-in-up">
         <!-- Decoration Gradient -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-teal-500"></div>

        <div class="mb-10">
            <div class="h-24 w-24 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner ring-8 ring-blue-50 animate-bounce-slow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-4 tracking-tight">¡Formularios Completados!</h2>
            <p class="text-xl text-gray-500">Toda la información ha sido guardada correctamente en la sesión.</p>
        </div>

        <div class="bg-gray-50/50 backdrop-blur-sm rounded-2xl p-6 text-left overflow-auto max-h-96 mb-10 border border-gray-200/60 shadow-inner custom-scrollbar">
            <div class="flex items-center justify-between mb-4 sticky top-0 bg-gray-50/90 p-2 rounded-lg backdrop-blur-sm z-10">
                <h3 class="font-bold text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Datos Recopilados (JSON Preview)
                </h3>
                <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded font-mono">readonly</span>
            </div>
            <pre class="text-xs text-slate-600 font-mono whitespace-pre-wrap leading-relaxed"><?php echo json_encode($all_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
             <a href="../../dashboard.php" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Volver al Inicio
            </a>
            <!-- Generate Word Button -->
            <!-- IMPORTANT: Points to generate_word_professional.php -->
            <a href="#" onclick="confirmGeneration(event)" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-xl text-white btn-premium shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-1 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generar Documento Word
            </a>
        </div>
    </div>
</div>

<style>
    .animate-bounce-slow {
        animation: bounce 3s infinite;
    }
</style>

<script>
function confirmGeneration(e) {
    e.preventDefault();
    Swal.fire({
        title: '¿Está seguro?',
        text: "Al generar el documento, el reporte se marcará como finalizado y NO podrá editarlo nuevamente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, generar y finalizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Initiate download
            window.location.href = '../../actions/generate_word_professional.php';
            
            // Redirect to dashboard after short delay
            setTimeout(() => {
                window.location.href = '../../dashboard.php?tab=home&msg=completed';
            }, 2000);
        }
    });
}
</script>

<?php include '../../includes/form_footer.php'; ?>

<?php
session_start();

// Limpiar SOLO los datos de step2 para que se vuelvan a guardar con los nuevos nombres
if (isset($_SESSION['form_data']['step2'])) {
    // Guardar los datos importantes que no cambiaron
    $taller_curso_1 = $_SESSION['form_data']['step2']['taller_curso_1'] ?? '';
    $taller_curso_2 = $_SESSION['form_data']['step2']['taller_curso_2'] ?? '';
    
    // Limpiar step2
    unset($_SESSION['form_data']['step2']);
    
    // Restaurar solo los tipos de curso
    $_SESSION['form_data']['step2'] = [
        'taller_curso_1' => $taller_curso_1,
        'taller_curso_2' => $taller_curso_2
    ];
    
    echo "✅ Sesión de Step 2 limpiada correctamente. Ahora vuelve a llenar el formulario.";
} else {
    echo "ℹ️ No había datos de Step 2 en la sesión.";
}

echo "<br><br><a href='../views/professional/step2.php'>← Volver a Step 2</a>";
?>

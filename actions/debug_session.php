<?php
session_start();

echo "<h2>Debug - Datos de Step 2 en Sesión</h2>";
echo "<pre>";
print_r($_SESSION['form_data']['step2'] ?? 'NO HAY DATOS DE STEP 2');
echo "</pre>";

echo "<hr>";
echo "<h3>Todos los datos de sesión:</h3>";
echo "<pre>";
print_r($_SESSION['form_data'] ?? 'NO HAY DATOS');
echo "</pre>";
?>

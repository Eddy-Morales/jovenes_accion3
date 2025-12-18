<?php
require_once '../includes/db_connection.php';

try {
    echo "<h1>Herramienta de Reparación de Base de Datos</h1>";
    
    // 1. Check current state
    $stmt = $pdo->query("SHOW COLUMNS FROM edit_requests LIKE 'status'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Tipo de columna actual: <strong>" . $col['Type'] . "</strong><br><br>";
    
    // 2. FORCE CHANGE TO VARCHAR
    echo "Intentando convertir la columna 'status' a VARCHAR(50)...<br>";
    // We modify it to VARCHAR to allow ANY status string, removing the ENUM restriction forever
    $sql = "ALTER TABLE edit_requests MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending'";
    $pdo->exec($sql);
    
    // 3. Verify change
    $stmt = $pdo->query("SHOW COLUMNS FROM edit_requests LIKE 'status'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Nuevo tipo de columna: <strong>" . $col['Type'] . "</strong><br><br>";
    
    if (strpos(strtolower($col['Type']), 'varchar') !== false) {
        echo "<h2 style='color: green;'>¡ÉXITO! La base de datos ha sido reparada.</h2>";
        echo "<p>Ya no deberías recibir el error 'Data truncated'.</p>";
    } else {
        echo "<h2 style='color: red;'>FALLÓ. No se pudo cambiar el tipo de columna.</h2>";
    }
    
    echo "<br><a href='../dashboard.php' style='padding:10px; background:#4F46E5; color:white; text-decoration:none; border-radius:5px;'>Volver al Dashboard</a>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>ERROR CRÍTICO: " . $e->getMessage() . "</h2>";
}
?>

<?php
require_once '../includes/db_connection.php';

try {
    // Check if column exists
    $check = $pdo->query("SHOW COLUMNS FROM edit_requests LIKE 'archive_id'");
    if ($check->rowCount() == 0) {
        $pdo->exec("ALTER TABLE edit_requests ADD COLUMN archive_id INT NULL DEFAULT NULL AFTER user_id");
        echo "Column 'archive_id' added successfully.";
    } else {
        echo "Column 'archive_id' already exists.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

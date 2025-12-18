<?php
require_once '../includes/db_connection.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS reports_archive (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        school_type VARCHAR(50) NOT NULL,
        school_name VARCHAR(255),
        form_data LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql);
    echo "Table 'reports_archive' created successfully (or already exists).";

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>

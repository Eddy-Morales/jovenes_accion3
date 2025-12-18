<?php
require_once '../includes/db_connection.php';

try {
    echo "Updating database schema...<br>";
    
    // Modify the column to include 'completed'
    // Assuming the original values were pending, approved, rejected
    $sql = "ALTER TABLE edit_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending'";
    
    $pdo->exec($sql);
    
    echo "Success! Table 'edit_requests' updated correctly. 'completed' is now a valid status.<br>";
    echo "<a href='../dashboard.php'>Return to Dashboard</a>";
    
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>

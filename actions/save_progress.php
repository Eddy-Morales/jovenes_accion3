<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $step = $_POST['step'];
    $next_url = $_POST['next_url'];
    
    // Remove metadata fields from the data to be saved
    $data_to_save = $_POST;
    unset($data_to_save['step']);
    unset($data_to_save['next_url']);

    // Save to session
    // We categorize by step to keep it organized
    $_SESSION['form_data']['step' . $step] = $data_to_save;

    // IMPORTANT: Update root school_type if present to ensure categorization is correct
    if (isset($_POST['school_type'])) {
        $_SESSION['form_data']['school_type'] = $_POST['school_type'];
    }

    // Save to Database if user is logged in
    if (isset($_SESSION['user_id'])) {
        require_once '../includes/db_connection.php';
        $userId = $_SESSION['user_id'];
        $formDataJson = json_encode($_SESSION['form_data'] ?? []);
        
        try {
            // Check if record exists
            $stmt = $pdo->prepare("SELECT user_id FROM user_progress WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            if ($stmt->rowCount() > 0) {
                // Update
                $updateStmt = $pdo->prepare("UPDATE user_progress SET form_data = ?, last_step = ?, updated_at = NOW() WHERE user_id = ?");
                $updateStmt->execute([$formDataJson, $step, $userId]);
            } else {
                // Insert
                $insertStmt = $pdo->prepare("INSERT INTO user_progress (user_id, form_data, last_step) VALUES (?, ?, ?)");
                $insertStmt->execute([$userId, $formDataJson, $step]);
            }
        } catch (PDOException $e) {
            // Log error but don't stop flow? Or maybe just ignore for now to avoid blocking user
            error_log("Error saving progress to DB: " . $e->getMessage());
        }
    }

    header("Location: " . $next_url);
    exit();
} else {
    // If accessed directly, go back
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: ../dashboard.php");
    }
    exit();
}

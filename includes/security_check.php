<?php
// Security Check: Prevent access to forms if completed and locked
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/db_connection.php'; // Use __DIR__ to be safe with includes
    
    // We need to know if we are inside a "step" file to enforce the lock
    // Simple check: if the current script name contains 'step', run the check
    $currentScript = basename($_SERVER['PHP_SELF']);
    if (strpos($currentScript, 'step') !== false) {
        
        $userId = $_SESSION['user_id'];
        
        // Check progress and type
        $stmt = $pdo->prepare("SELECT last_step, form_data FROM user_progress WHERE user_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $lastStep = $row['last_step'] ?? 0;
        $formData = json_decode($row['form_data'] ?? '', true);
        $schoolType = $formData['school_type'] ?? 'non_professional';
        
        // Define completion thresholds and redirect paths
        $completionStep = ($schoolType === 'professional') ? 9 : 13;
        $redirectPath = ($schoolType === 'professional') ? '../professional/step9.php' : '../non-professional/summary.php';
        
        // Check edit status
        $stmt = $pdo->prepare("SELECT status FROM edit_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $editStatus = $stmt->fetchColumn();
        
        // If completed AND edit is NOT approved
        if ($lastStep >= $completionStep && $editStatus !== 'approved') {
            // Redirect to summary/final step based on type
            // Avoid infinite loop if already on the target page
            $targetScript = basename($redirectPath);
            if ($currentScript !== $targetScript) {
                header("Location: " . $redirectPath);
                exit();
            }
        }
    }
}
?>

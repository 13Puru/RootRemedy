<?php
session_start();
include 'db_config.php';

// Check if the admin is logged in
if (isset($_SESSION['admin_logged_in']) && isset($_SESSION['admin_username'])) {
    $admin_username = $_SESSION['admin_username'];

    // Log the logout activity
    $activity = "Admin logged out.";
    $activityTime = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO admin_activity_log (admin_username, activity, activity_time) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_username, $activity, $activityTime);
    $stmt->execute();
    $stmt->close();

    // Destroy the session after logging activity
    session_unset();
    session_destroy();
    
    echo "Activity Logged and Session Destroyed"; // This message will be returned to JavaScript.
} else {
    echo "No active session"; // This message will be returned if no session is found.
}

$conn->close();
?>

<?php
session_start();
include 'db_config.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        echo "New password and confirm password do not match.";
    } else {
        // Ensure the username is available in the session
        if (!isset($_SESSION['admin_username'])) {
            echo "Session error: Username not found.";
            exit();
        }

        $adminUsername = $_SESSION['admin_username'];

        // Fetch current password hash from the database
        $query = "SELECT password FROM admin WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $adminUsername);
        $stmt->execute();
        $stmt->store_result(); // Store the result to free the connection for the next query

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($storedPasswordHash);
            $stmt->fetch();
            $stmt->close(); // Close the statement before running the next query

            // Verify current password
            if (!password_verify($currentPassword, $storedPasswordHash)) {
                echo "Current password is incorrect.";
            } else {
                // Hash the new password
                $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the password in the database
                $updateQuery = "UPDATE admin SET password = ? WHERE username = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ss", $newPasswordHash, $adminUsername);

                if ($updateStmt->execute()) {
                    echo "Password changed successfully.";
                } else {
                    echo "Error updating password.";
                }
                $updateStmt->close();
            }
        } else {
            echo "Error: Unable to retrieve stored password.";
            $stmt->close();
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        :root {
    --primary-color: #3498db;
    --secondary-color: #2ecc71;
    --background-color: #f4f6f7;
    --text-color: #2c3e50;
    --border-radius: 12px;
    --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.09);
}

body {
    background-color: var(--background-color);
    font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
    /* display: flex; */
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    margin: 0;
    color: var(--text-color);
}

.container {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 40px;
    width: 100%;
    max-width: 100%;
    transition: all 0.3s ease;
}

.container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
}

h2 {
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 30px;
    font-weight: 600;
}

.form-label {
    font-weight: 500;
    color: var(--text-color);
    transition: color 0.3s ease;
}

.form-control {
    border: 2px solid #e0e4e8;
    border-radius: 8px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
}

.form-control:hover {
    border-color: var(--primary-color);
}

.btn-primary {
    background-color: var(--primary-color);
    border: none;
    border-radius: 8px;
    padding: 12px 20px;
    width: 100%;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
}

.btn-primary:active {
    transform: translateY(1px);
    box-shadow: none;
}

@media (max-width: 576px) {
    .container {
        padding: 20px;
        margin: 20px;
    }
}

/* Password Strength Indicator (Optional Enhancement) */
.password-strength {
    height: 5px;
    background-color: #e0e4e8;
    margin-top: 5px;
    border-radius: 3px;
    overflow: hidden;
}

.password-strength-meter {
    height: 100%;
    width: 0;
    transition: width 0.3s ease;
}

.password-strength-weak {
    background-color: #e74c3c;
}

.password-strength-medium {
    background-color: #f39c12;
}

.password-strength-strong {
    background-color: var(--secondary-color);
}
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Change Password</h2>
    <form method="POST" action="changepass.php">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>
</body>
</html>

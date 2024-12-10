<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Include the database configuration file

include 'db_config.php';

// Fetch consultancy requests from the database where the status is 'Pending'
$sql = "SELECT user_id, name, email, phone_number, subject, message, status FROM user WHERE status = 'Pending'";
$result = $conn->query($sql);

// Initialize a variable for displaying messages
$message = '';

// Handle resolving consultancy requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $action = 'Resolved';

    if ($user_id > 0) {
        // Update the status in the user table
        $update_sql = "UPDATE user SET status = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $action, $user_id);

        if ($update_stmt->execute()) {
            // After marking the request as Resolved, refresh the page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    } else {
        $message = "Invalid input. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Remedy Admin - Consultancy Requests</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
       :root {
    --primary-color: #1abc9c;
    --secondary-color: #2ecc71;
    --background-dark: #2c3e50;
    --background-light: #ecf0f1;
    --text-color: #34495e;
    --gradient-primary: linear-gradient(135deg, #1abc9c, #16a085);
    --gradient-secondary: linear-gradient(135deg, #2ecc71, #27ae60);
    --box-shadow-subtle: 0 4px 6px rgba(0,0,0,0.1);
    --box-shadow-elevated: 0 10px 20px rgba(0,0,0,0.15);
}

body {
    font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(to right, var(--background-light) 0%, #f4f7f6 100%);
    color: var(--text-color);
    line-height: 1.6;
    overflow-x: hidden;
}

.container {
    position: relative;
    z-index: 2;
}

.table-container {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    backdrop-filter: blur(15px);
    box-shadow: var(--box-shadow-elevated);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.table-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.page-title h1 {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-align: center;
    font-weight: 700;
    position: relative;
    padding-bottom: 10px;
}

.page-title h1::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background: var(--gradient-primary);
}

.table {
    border-radius: 10px;
    overflow: hidden;
}

.table thead {
    background: var(--gradient-primary);
    color: white;
}

.table th {
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(46, 204, 113, 0.05);
    transform: scale(1.01);
}

.badge {
    background: var(--gradient-secondary) !important;
    color: white !important;
    border: none;
    padding: 0.4em 0.6em;
}

.btn-success {
    background: var(--gradient-secondary);
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-success::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg, 
        transparent, 
        rgba(255, 255, 255, 0.3), 
        transparent
    );
    transition: all 0.5s ease;
}

.btn-success:hover::before {
    left: 100%;
}

.btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .table-container {
        padding: 10px !important;
    }

    .table {
        font-size: 14px;
    }

    .page-title h1 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .table {
        font-size: 12px;
    }

    .btn-success {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Alert Styling */
.alert {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    backdrop-filter: blur(10px);
    box-shadow: var(--box-shadow-subtle);
}

.alert .btn-close {
    background-color: rgba(0,0,0,0.1);
    border-radius: 50%;
    opacity: 0.7;
}

/* Subtle Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.table-responsive {
    animation: fadeIn 0.5s ease forwards;
}
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="table-container card shadow-sm p-4">
        <div class="page-title">
            <h1>Consultancy Requests</h1>
        </div>
        <!-- Display any messages -->
        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['subject'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['message'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td><span class='badge bg-warning text-dark'>" . htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8') . "</span></td>";
                            echo "<td class='action-buttons'>";
                            // Form for Resolved action only
                            echo "<form method='post' class='consultancy-form' style='display:inline-block;'>";
                            echo "<input type='hidden' name='user_id' value='" . intval($row['user_id']) . "'>";
                            echo "<button class='btn btn-success btn-sm' type='submit'>Resolve</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No consultancy requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Include Bootstrap 5 JS and Popper.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Start session or initialize message variable
$message = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "rootremedy");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $type = $conn->real_escape_string($_POST['type']);
    
    // Process uploaded image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
        $image = $conn->real_escape_string($image);
    } else {
        $message = '<div class="alert alert-danger">Failed to upload image.</div>';
        exit;
    }

    // Insert data into the database
    $sql = "INSERT INTO featured (title, description, image, type) VALUES ('$title', '$description', '$image', '$type')";
    
    if ($conn->query($sql) === TRUE) {
        $message = '<div class="alert alert-success">Data inserted successfully.</div>';
    } else {
        $message = '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add or Update Featured Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
               
        /* Futuristic Plant Details Page Styles */
:root {
    --primary-green: #0165f1;
    --secondary-green: #0362d5;
    --background-light: #f4f6f7;
    --text-dark: #2c3e50;
    --leaf-gradient-1: rgba(46, 204, 113, 0.1);
    --leaf-gradient-2: rgba(39, 174, 96, 0.05);
}

body {
    font-family: 'Poppins', 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    color: var(--text-dark);
    line-height: 1.6;
    min-height: 100vh;
    /* display: flex; */
    align-items: center;
    justify-content: center;
    overflow-x: hidden;
}


.container {
    max-width: 100%;
    padding: 20px;
    margin: 20px;
}

.card {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 
        0 15px 35px rgba(0, 0, 0, 0.1),
        0 5px 15px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    width: 100%;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.12),
        0 8px 20px rgba(0, 0, 0, 0.08);
}

.card-header {
    background: linear-gradient(to right, var(--primary-green), var(--secondary-green));
    color: white;
    padding: 15px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255, 255, 255, 0.1);
    transform: rotate(45deg);
    animation: header-shine 3s infinite linear;
}

@keyframes header-shine {
    0% {
        transform: rotate(45deg) translateX(-100%);
    }
    100% {
        transform: rotate(45deg) translateX(100%);
    }
}

.card-body {
    padding: 30px;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    transition: color 0.3s ease;
}

.form-control {
    background: rgba(46, 204, 113, 0.05);
    border: 1px solid rgba(46, 204, 113, 0.2);
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-green);
    box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
    background: white;
}

.btn-success {
    background: linear-gradient(to right, var(--primary-green), var(--secondary-green));
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
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
    box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .container {
        padding: 10px;
        margin: 10px;
    }

    .card-body {
        padding: 20px;
    }

    .card-header h3 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .container {
        width: 95%;
        margin: 5px;
        padding: 5px;
    }

    .card-body {
        padding: 15px;
    }
}

    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <b>Add or Update Featured Item</b>
        </div>
        <div class="card-body">
            <!-- Display message here -->
            <?php echo $message; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Choose Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="plant">Plant</option>
                        <option value="disease">Disease</option>
                        <option value="medicine">Medicine</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Submit</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

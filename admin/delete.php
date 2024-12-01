<?php
include 'db_config.php';

// Handle delete request
if (isset($_POST['delete'])) {
    $table = $_POST['table'];
    $id = $_POST['id'];

    if ($table == 'plant') {
        $query = "DELETE FROM plant WHERE plant_id = ?";
    } elseif ($table == 'disease') {
        $query = "DELETE FROM disease WHERE disease_id = ?";
    } elseif ($table == 'medicine') {
        $query = "DELETE FROM medicine WHERE medicine_id = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting record"]);
    }
    $stmt->close();
    exit(); // Exit after handling AJAX request
}

// Handle data display
$selectedOption = isset($_POST['deleteOption']) ? $_POST['deleteOption'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
    --primary-color: #2c3e50;
    --secondary-color: #34495e;
    --accent-color: #3498db;
    --danger-color: #e74c3c;
    --light-background: #ecf0f1;
    --white: #ffffff;
    --text-color: #2c3e50;
    --border-radius: 8px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

body {
    font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
    background-color: var(--light-background);
    color: var(--text-color);
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: var(--white);
    box-shadow: var(--box-shadow);
    border-radius: var(--border-radius);
}

h2 {
    color: var(--primary-color);
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: var(--accent-color);
}

#deleteForm {
    background-color: var(--light-background);
    padding: 20px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    box-shadow: var(--box-shadow);
}

.form-select {
    border-radius: var(--border-radius);
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.table {
    background-color: var(--white);
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
}

.table thead {
    background-color: var(--primary-color);
    color: var(--white);
}

.table th {
    vertical-align: middle;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.table td {
    vertical-align: middle;
    padding: 15px;
}

.table tbody tr:nth-child(even) {
    background-color: rgba(52, 152, 219, 0.05);
}

.table tbody tr:hover {
    background-color: rgba(52, 152, 219, 0.1);
    transition: background-color 0.3s ease;
}

.btn-danger {
    background-color: var(--danger-color);
    border: none;
    border-radius: var(--border-radius);
    padding: 8px 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-danger:hover {
    background-color: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    .table {
        font-size: 14px;
    }

    .table th, .table td {
        padding: 10px;
    }

    .btn-danger {
        padding: 6px 10px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    h2 {
        font-size: 1.5rem;
    }

    .table-responsive {
        font-size: 12px;
    }

    .table th, .table td {
        padding: 8px;
    }
}

/* Subtle Animations and Interactions */
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

#dataDisplay {
    animation: fadeIn 0.5s ease forwards;
}

/* Focus and Active States */
*:focus {
    outline: 2px solid var(--accent-color);
    outline-offset: 2px;
}
    </style>
</head>
<body>
<div class="container mt-5" id="containerArea">
    <h2 class="text-center mb-4">Delete Records</h2>

    <!-- Select Option Form -->
    <form method="post" class="mb-4" id="deleteForm">
        <div class="mb-3">
            <label for="deleteOption" class="form-label">Select option to delete:</label>
            <select name="deleteOption" id="deleteOption" class="form-select">
                <option value="">-- Select --</option>
                <option value="plants" <?php if ($selectedOption == 'plants') echo 'selected'; ?>>Delete Plants</option>
                <option value="disease" <?php if ($selectedOption == 'disease') echo 'selected'; ?>>Delete Disease</option>
                <option value="medicine" <?php if ($selectedOption == 'medicine') echo 'selected'; ?>>Delete Medicine</option>
            </select>
        </div>
    </form>

    <!-- Data Display -->
    <div id="dataDisplay">
        <?php if ($selectedOption == 'plants'): ?>
            <h3>Plants List</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Serial No.</th>
                        <th>Plant Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT plant_id, plant_name FROM plant ORDER BY plant_name ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial}</td>
                                <td>{$row['plant_name']}</td>
                                <td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='table' value='plant'>
                                        <input type='hidden' name='id' value='{$row['plant_id']}'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No plants found</td></tr>";
                }
                ?>
                </tbody>
            </table>

        <?php elseif ($selectedOption == 'disease'): ?>
            <h3>Disease List</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Serial No.</th>
                        <th>Disease Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT disease_id, disease_name FROM disease ORDER BY disease_name ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial}</td>
                                <td>{$row['disease_name']}</td>
                                <td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='table' value='disease'>
                                        <input type='hidden' name='id' value='{$row['disease_id']}'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No diseases found</td></tr>";
                }
                ?>
                </tbody>
            </table>

        <?php elseif ($selectedOption == 'medicine'): ?>
            <h3>Medicine List</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Serial No.</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT medicine_id, medicine_name, category FROM medicine ORDER BY medicine_name ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial}</td>
                                <td>{$row['medicine_name']}</td>
                                <td>{$row['category']}</td>
                                <td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='table' value='medicine'>
                                        <input type='hidden' name='id' value='{$row['medicine_id']}'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No medicines found</td></tr>";
                }
                ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<script src="/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>

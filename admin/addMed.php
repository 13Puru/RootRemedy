<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
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

<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="mb-0">Add Medicine</h3>
        </div>
        <div class="card-body">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                try {
                    $conn = new PDO("mysql:host=localhost;dbname=rootremedy", "root", "");
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Step 1: Generate a new combination_id
                    $combinationStmt = $conn->prepare("INSERT INTO combination (combination_id) VALUES (NULL)");
                    $combinationStmt->execute();
                    $combination_id = $conn->lastInsertId();

                    // Step 2: Insert selected plants into the combination_plants table
                    $selectedPlants = $_POST['plant_ids'];
                    foreach ($selectedPlants as $plant_id) {
                        $insertPlantStmt = $conn->prepare("INSERT INTO combination_plants (combination_id, plant_id) VALUES (:combination_id, :plant_id)");
                        $insertPlantStmt->execute([
                            ':combination_id' => $combination_id,
                            ':plant_id' => $plant_id,
                        ]);
                    }

                    // Step 3: Insert the new medicine details into the medicine table
                    $stmt = $conn->prepare("INSERT INTO medicine 
                        (medicine_name, disease_id, preparation_method, how_to_take, category, combination_id) 
                        VALUES (:medicine_name, :disease_id, :preparation_method, :how_to_take, :category, :combination_id)");

                    $stmt->execute([
                        ':medicine_name' => filter_var($_POST['medicine_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':disease_id' => filter_var($_POST['disease_id'], FILTER_VALIDATE_INT),
                        ':preparation_method' => filter_var($_POST['preparation_method'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':how_to_take' => filter_var($_POST['how_to_take'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':category' => filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':combination_id' => $combination_id,
                    ]);

                    echo "<div class='alert alert-success text-center'>New medicine details added successfully!</div>";
                } catch (PDOException $e) {
                    error_log("Database query failed: " . $e->getMessage());
                    echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
                }
            }
            ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="medicineName" class="form-label">Medicine Name</label>
                    <input type="text" class="form-control" id="medicineName" name="medicine_name" placeholder="Enter the medicine name" required>
                </div>

                <div class="mb-3">
                    <label for="selectDisease" class="form-label">Select Disease</label>
                    <select class="form-select" id="selectDisease" name="disease_id" required>
                        <option value="" disabled selected>Select a disease</option>
                        <?php
                        try {
                            $disease_conn = new PDO("mysql:host=localhost;dbname=rootremedy", "root", "");
                            $disease_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $disease_stmt = $disease_conn->prepare("SELECT disease_id, disease_name FROM disease");
                            $disease_stmt->execute();
                            $disease_result = $disease_stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($disease_result as $row) {
                                echo "<option value='" . htmlspecialchars($row['disease_id']) . "'>" . htmlspecialchars($row['disease_name']) . "</option>";
                            }
                        } catch (PDOException $e) {
                            error_log("Database query failed: " . $e->getMessage());
                            echo "<option value='' disabled>Error fetching diseases</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Multiple Select for Plants -->
                <div class="mb-3">
                    <label for="selectPlants" class="form-label">Select Plants</label>
                    <select class="form-select" id="selectPlants" name="plant_ids[]" multiple required>
                        <?php
                        try {
                            $plant_conn = new PDO("mysql:host=localhost;dbname=rootremedy", "root", "");
                            $plant_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $plant_stmt = $plant_conn->prepare("SELECT plant_id, plant_name FROM plant");
                            $plant_stmt->execute();
                            $plant_result = $plant_stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($plant_result as $row) {
                                echo "<option value='" . htmlspecialchars($row['plant_id']) . "'>" . htmlspecialchars($row['plant_name']) . "</option>";
                            }
                        } catch (PDOException $e) {
                            error_log("Database query failed: " . $e->getMessage());
                            echo "<option value='' disabled>Error fetching plants</option>";
                        }
                        ?>
                    </select>
                    <small class="form-text text-muted">Hold down the Ctrl (Windows) / Command (Mac) button to select multiple options.</small>
                </div>

                <div class="mb-3">
                    <label for="preparationMethod" class="form-label">Preparation Method</label>
                    <textarea class="form-control" id="preparationMethod" name="preparation_method" rows="3" placeholder="Describe the preparation method" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="howToTake" class="form-label">How to Take</label>
                    <textarea class="form-control" id="howToTake" name="how_to_take" rows="3" placeholder="Instructions on how to take the medicine" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="selectCategory" class="form-label">Select Category</label>
                    <select class="form-select" id="selectCategory" name="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="Herbal">Herbal</option>
                        <option value="Ayurvedic">Ayurvedic</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="reset" class="btn btn-outline-success">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#selectPlants').select2({
            placeholder: "Select plants",
            allowClear: true
        });
    });
</script>
</body>
</html>

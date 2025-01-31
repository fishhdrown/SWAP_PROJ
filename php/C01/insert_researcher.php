<?php
// Database credentials
$dbHost = 'localhost';
$dbUser = 'admin';
$dbPass = 'admin';
$dbName = 'project_swap';

// Connect to database
$con = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($con->connect_error) {
    die('Database connection failed: ' . $con->connect_error);
}

$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $expertise_id = intval($_POST['expertise_id']);
    $assigned_projects_id = intval($_POST['assigned_projects_id']);
    $role = htmlspecialchars(trim($_POST['role']));

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        // Check if the table contains the "role" column
        $query = "SHOW COLUMNS FROM researcher_profiles LIKE 'role'";
        $result = $con->query($query);

        if ($result && $result->num_rows > 0) {
            // Insert into database
            $stmt = $con->prepare("INSERT INTO researcher_profiles (name, email, expertise_id, assigned_projects_id, role) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param('ssiis', $name, $email, $expertise_id, $assigned_projects_id, $role);
                if ($stmt->execute()) {
                    $success_message = "Profile inserted successfully.";
                } else {
                    $error_message = "Error inserting profile: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error_message = "Database error: " . $con->error;
            }
        } else {
            $error_message = "The 'role' column does not exist in the researcher_profiles table. Please update your database schema.";
        }
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert New Researcher</title>
    <link rel="stylesheet" href="../css/navigation.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 140px;
            background-color: #ffffff;
            color: #333;
        }
        .container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="number"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .success-message {
            color: green;
            text-align: center;
            font-weight: bold;
        }
        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('../navigation.php'); ?>

    <div class="container">
        <h1>Insert New Researcher</h1>

        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="insert_researcher.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="expertise_id">Expertise ID:</label>
            <input type="number" name="expertise_id" id="expertise_id" required>

            <label for="assigned_projects_id">Assigned Projects ID:</label>
            <input type="number" name="assigned_projects_id" id="assigned_projects_id" required>

            <label for="role">Role:</label>
            <input type="text" name="role" id="role" required>

            <input type="submit" value="Insert Record">
        </form>
    </div>
</body>
</html>

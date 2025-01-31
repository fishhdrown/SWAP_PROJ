<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database credentials
    $dbHost = 'localhost';  // Server location
    $dbUser = 'admin';      // Username
    $dbPass = 'admin';      // Password
    $dbName = 'project_swap'; // Database name

    // Connect to database
    $con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
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

        // Prepare and execute the insert query
        $stmt = $con->prepare("INSERT INTO researcher_profiles (name, email, expertise_id, assigned_projects_id, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssiis', $name, $email, $expertise_id, $assigned_projects_id, $role);

        if ($stmt->execute()) {
            $success_message = "Profile inserted successfully.";
        } else {
            $error_message = "Error inserting profile: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert New Researcher</title>
    <link rel="stylesheet" href="../css/navigation.css">
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 140px;
            background-color: #ffffff;
            color: #333;
        }

        /* Container styling */
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
            font-size: 28px;
            font-weight: bold;
        }

        label {
            font-weight: bold;
        }

        /* Input fields styling */
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Submit button styling */
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Success and error message styling */
        .success-message, .error-message {
            text-align: center;
            font-weight: bold;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <?php include('../navigation.php'); ?>

    <div class="container">
        <h1>Insert New Profile</h1>

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
            <input type="text" name="email" id="email" required>

            <label for="expertise_id">Area of Expertise:</label>
            <input type="text" name="expertise_id" id="expertise_id" required>

            <label for="assigned_projects_id">Assigned Projects ID:</label>
            <input type="text" name="assigned_projects_id" id="assigned_projects_id" required>

            <label for="role">Role:</label>
            <input type="text" name="role" id="role" required>

            <input type="submit" value="Insert Record">
        </form>
    </div>
</body>
</html>

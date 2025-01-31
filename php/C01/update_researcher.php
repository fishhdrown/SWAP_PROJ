<?php
$dbHost = 'localhost';
$dbUser = 'admin';
$dbPass = 'admin';
$dbName = 'project_swap';

// Connect to database
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}

$success_message = "";
$error_message = "";

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $expertise_id = intval($_POST['expertise_id']);
    $assigned_projects_id = intval($_POST['assigned_projects_id']);
    $role = htmlspecialchars(trim($_POST['role']));

    $stmt = $con->prepare("UPDATE researcher_profiles SET name = ?, email = ?, expertise_id = ?, assigned_projects_id = ?, role = ? WHERE id = ?");
    $stmt->bind_param('ssiisi', $name, $email, $expertise_id, $assigned_projects_id, $role, $id);

    if ($stmt->execute()) {
        header("Location: select_researcher.php"); // Redirect after update
        exit();
    } else {
        $error_message = "Error updating record: " . $stmt->error;
    }
    $stmt->close();
} else {
    $item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
    
    // Debugging to check if ID is passed correctly
    if ($item_id == 0) {
        die('Invalid researcher ID.');
    }

    $stmt = $con->prepare("SELECT * FROM researcher_profiles WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if (!$row) {
        die('Record not found.');
    }
    $stmt->close();
}
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Researcher</title>
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
            font-size: 28px;
            font-weight: bold;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="submit"] {
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
    </style>
</head>
<body>
    <?php include('../navigation.php'); ?>

    <div class="container">
        <h1>Update Researcher</h1>
        <form action="update_researcher.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $row['name']; ?>" required>
            
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo $row['email']; ?>" required>
            
            <label for="expertise_id">Expertise ID:</label>
            <input type="text" name="expertise_id" id="expertise_id" value="<?php echo $row['expertise_id']; ?>" required>
            
            <label for="assigned_projects_id">Assigned Projects ID:</label>
            <input type="text" name="assigned_projects_id" id="assigned_projects_id" value="<?php echo $row['assigned_projects_id']; ?>" required>
            
            <label for="role">Role:</label>
            <input type="text" name="role" id="role" value="<?php echo $row['role']; ?>" required>

            <input type="submit" value="Update Record">
        </form>
    </div>
</body>
</html>

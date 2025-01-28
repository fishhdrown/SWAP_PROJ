<html>
<head>
    <title>Insert New Researcher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="submit"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        button {
            background-color: #008CBA; /* Blue */
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #007B9A;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
 
<h2>Insert New Researcher</h2>
 
<?php
// Database connection
$con = mysqli_connect("localhost", "admin", "admin", "project_swap");
if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}
 
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $expertise_id = intval($_POST['expertise_id']);
    $assigned_projects_id = intval($_POST['assigned_projects_id']);
 
    // Insert query
    $stmt = $con->prepare("INSERT INTO researcher_profiles (name, email, expertise_id, assigned_projects_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssii', $name, $email, $expertise_id, $assigned_projects_id);
 
    if ($stmt->execute()) {
        echo "<p>Record inserted successfully.</p>";
        header("Location: select_researcher.php"); // Redirect after insertion
        exit();
    } else {
        echo "<p>Error inserting record: " . $stmt->error . "</p>";
    }
 
    $stmt->close();
}
?>
 
<form action="insert_researcher.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required><br>
 
    <label for="email">Email:</label>
    <input type="text" name="email" id="email" required><br>
 
    <label for="expertise_id">Expertise ID:</label>
    <input type="text" name="expertise_id" id="expertise_id" required><br>
 
    <label for="assigned_projects_id">Assigned Projects ID:</label>
    <input type="text" name="assigned_projects_id" id="assigned_projects_id" required><br>
 
    <input type="submit" value="Insert Record">
</form>
 
<div class="button-container">
    <!-- View Researchers button -->
    <a href="read_researcher.php">
        <button>View Researchers</button>
    </a>
    <!-- Edit Researchers button -->
    <a href="select_researcher.php">
        <button>Edit Researchers</button>
    </a>
</div>
 
</body>
</html>
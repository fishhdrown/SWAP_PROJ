<html>
<head>
    <style>
        #title {
            text-align: center;
        }
    </style>
</head>
<body>  

<h3 id="title">Researcher Profiles</h3>

<!-- Form for inserting new researcher data -->
<h4 id="title">Insert New Profile</h4>
<form action="insert_proj.php" method="POST">
<table align="center">
    <tr>
        <td>name:</td>
        <td><input type="text" name="name" required></td>
    </tr>
    <tr>
        <td>email:</td>
        <td><input type="text" name="email" required></td>
    </tr>
    <tr>
        <td>expertise_id:</td>
        <td><input type="text" name="expertise_id" required></td>
    </tr>
    <tr>
        <td>assigned_projects_id:</td>
        <td><input type="text" name="assigned_projects_id" required></td>
    </tr>
    <tr>
        <td align="center" colspan="2"><input type="submit" value="Insert Record"></td>
    </tr>
</table>
<br>
</form>

<!-- Button to navigate to read_proj.php (View Profiles) -->
<div style="text-align: center;">
    <a href="read_proj.php">
        <button>View All Profiles</button>
    </a>
</div>

<?php
// Connect to database
$con = mysqli_connect("localhost", "admin", "admin", "project_swap"); 
if (!$con) {
    die('Could not connect: ' . mysqli_connect_errno());
}

// Handle form submission for inserting data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from form
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    
    // Make sure expertise_id and assigned_projects_id are integers
    $expertise_id = filter_var($_POST["expertise_id"], FILTER_VALIDATE_INT);
    $assigned_projects_id = filter_var($_POST["assigned_projects_id"], FILTER_VALIDATE_INT);

    // Check if expertise_id and assigned_projects_id are valid integers
    if ($expertise_id === false || $assigned_projects_id === false) {
        echo "Please enter valid integer values for expertise_id and assigned_projects_id.";
        exit;
    }

    // Prepare the statement to insert the data into the table
    $stmt = $con->prepare("INSERT INTO `researcher_profiles` (`name`, `email`, `expertise_id`, `assigned_projects_id`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssii', $name, $email, $expertise_id, $assigned_projects_id);
    
    // Execute query
    if ($stmt->execute()) {
        echo "Record inserted successfully.";
    } else {
        echo "Error executing INSERT query: " . $stmt->error;
    }
    
    $stmt->close(); // Close the prepared statement
}

// Close SQL Connection
$con->close();
?>
</body>
</html>
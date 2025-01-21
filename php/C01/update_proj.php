<html>
<body>
<?php
// Connect to database
$con = mysqli_connect("localhost", "admin", "admin", "project_swap");
if (!$con) {
    die('Could not connect: ' . mysqli_connect_errno());
}

// Handle the update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $expertise_id = htmlspecialchars($_POST["expertise_id"]);
    $assigned_projects_id = htmlspecialchars($_POST["assigned_projects_id"]);

    // Update the record in the database
    $stmt = $con->prepare("UPDATE researcher_profiles SET name = ?, email = ?, expertise_id = ?, assigned_projects_id = ? WHERE id = ?");
    $stmt->bind_param('ssiii', $name, $email, $expertise_id, $assigned_projects_id, $id);
    
    if ($stmt->execute()) {
        echo "Record updated successfully.";
        header("Location: insert_proj.php"); // Redirect back to the view page
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Get the ID of the record to update
    $item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

    // Fetch the record by ID
    $stmt = $con->prepare("SELECT * FROM researcher_profiles WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        die('Record not found!');
    }

    // Create the update form
    echo '<form action="update_proj.php" method="POST">';
    echo '<input type="hidden" name="id" value="'.$row['id'].'">';
    echo 'Name: <input type="text" name="name" value="'.$row['name'].'" required><br>';
    echo 'Email: <input type="text" name="email" value="'.$row['email'].'" required><br>';
    echo 'Expertise ID: <input type="text" name="expertise_id" value="'.$row['expertise_id'].'" required><br>';
    echo 'Assigned Project ID: <input type="text" name="assigned_projects_id" value="'.$row['assigned_projects_id'].'" required><br>';
    echo '<input type="submit" value="Update Record">';
    echo '</form>';

    $stmt->close();
}

// Close the database connection
$con->close();
?>
</body>
</html>
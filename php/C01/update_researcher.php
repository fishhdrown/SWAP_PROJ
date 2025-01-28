<html>
<head>
    <title>Update Researcher</title>
</head>
<body>
 
<h2>Update Researcher Profile</h2>
 
<?php
// Database connection
$con = mysqli_connect("localhost", "admin", "admin", "project_swap");
if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $expertise_id = intval($_POST['expertise_id']);
    $assigned_projects_id = intval($_POST['assigned_projects_id']);
 
    // Update query
    $stmt = $con->prepare("UPDATE researcher_profiles SET name = ?, email = ?, expertise_id = ?, assigned_projects_id = ? WHERE id = ?");
    $stmt->bind_param('ssiii', $name, $email, $expertise_id, $assigned_projects_id, $id);
 
    if ($stmt->execute()) {
        echo "<p>Record updated successfully.</p>";
        header("Location: select_researcher.php"); // Redirect to the researcher list page
        exit();
    } else {
        echo "<p>Error updating record: " . $stmt->error . "</p>";
    }
 
    $stmt->close();
} else {
    $item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;
 
    // Fetch record to update
    $stmt = $con->prepare("SELECT * FROM researcher_profiles WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
 
    if (!$row) {
        die('Record not found.');
    }
 
    // Show update form
    echo '<form action="update_researcher.php" method="POST">';
    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
    echo 'Name: <input type="text" name="name" value="' . $row['name'] . '" required><br>';
    echo 'Email: <input type="text" name="email" value="' . $row['email'] . '" required><br>';
    echo 'Expertise ID: <input type="text" name="expertise_id" value="' . $row['expertise_id'] . '" required><br>';
    echo 'Assigned Projects ID: <input type="text" name="assigned_projects_id" value="' . $row['assigned_projects_id'] . '" required><br>';
    echo '<input type="submit" value="Update Record">';
    echo '</form>';
 
    $stmt->close();
}
 
$con->close();
?>
 
</body>
</html>
<html>
<body>
<?php
// Connect to the database
$con = mysqli_connect("localhost", "admin", "admin", "project_swap");
if (!$con) {
    die('Could not connect: ' . mysqli_connect_errno()); // Return error if connection fails
}

// Prepare the statement to delete the profile
$stmt = $con->prepare("DELETE FROM researcher_profiles WHERE id = ?");

// Sanitize the GET entry to prevent SQL injection
$del_itemid = isset($_GET["item_id"]) ? intval($_GET["item_id"]) : 0;

// Bind the parameters
$stmt->bind_param('i', $del_itemid);

if ($stmt->execute()) {
    echo "Profile deleted successfully.";
    header("Location: select_proj.php"); // Redirect back to the page displaying profiles
} else {
    echo "Error executing DELETE query.";
}

// Close the statement and connection
$stmt->close();
$con->close();
?>
</body>
</html>
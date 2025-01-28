<html>
<head>
    <title>Delete Researcher</title>
</head>
<body>
 
<h2>Delete Researcher Profile</h2>
 
<?php
// Database connection
$con = mysqli_connect("localhost", "admin", "admin", "project_swap");
if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}
 
if (isset($_GET['item_id'])) {
    $item_id = intval($_GET['item_id']);
 
    // Delete query
    $stmt = $con->prepare("DELETE FROM researcher_profiles WHERE id = ?");
    $stmt->bind_param("i", $item_id);
 
    if ($stmt->execute()) {
        echo "<p>Profile deleted successfully.</p>";
        header("Location: select_researcher.php"); // Redirect to the researcher list page
        exit();
    } else {
        echo "<p>Error deleting profile: " . $stmt->error . "</p>";
    }
 
    $stmt->close();
} else {
    echo "<p>No ID provided for deletion.</p>";
}
 
$con->close();
?>
 
</body>
</html>
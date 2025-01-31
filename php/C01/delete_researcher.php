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

// Check if ID is provided
if (isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $id = intval($_GET['item_id']);

    // Delete researcher record
    $stmt = $con->prepare("DELETE FROM researcher_profiles WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: select_researcher.php"); // Redirect after deletion
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

mysqli_close($con);
?>

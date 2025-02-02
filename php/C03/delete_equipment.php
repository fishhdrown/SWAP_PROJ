<?php
session_start();
$conn = new mysqli("localhost", "root", "", "swap_project_db");

if ($conn->connect_error) {
    die("Database connection failed.");
}

$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM research_equipment WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Equipment deleted successfully!";
    } else {
        $_SESSION['error_msg'] = "Error deleting: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
header("Location: read_equipment.php");
exit;
?>

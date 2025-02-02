<?php
session_start(); 


$conn = new mysqli("localhost", "root", "", "swap_project_db");


if ($conn->connect_error) {
    die("Database connection failed.");
}


$id = $_GET['id'] ?? 0;
if (!$id || !is_numeric($id)) {
    die("Invalid request.");
}


$stmt = $conn->prepare("SELECT * FROM research_equipment WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$equipment = $result->fetch_assoc();
$stmt->close();

if (!$equipment) {
    die("Equipment not found.");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF verification failed.");
    }

    
    $name = htmlspecialchars(trim($_POST['name']));
    $status = htmlspecialchars(trim($_POST['status']));
    $availability = htmlspecialchars(trim($_POST['availability']));

    
    $stmt = $conn->prepare("UPDATE research_equipment SET name=?, status=?, availability=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $status, $availability, $id);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Equipment updated successfully!";
        header("Location: read_equipment.php");
        exit;
    } else {
        $_SESSION['error_msg'] = "Error updating: " . $stmt->error;
    }
    $stmt->close();
}


$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Equipment</title>
</head>
<body>
    <h2>Update Equipment</h2>

    
    <?php if (isset($_SESSION['success_msg'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_msg'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></p>
    <?php endif; ?>

    
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Equipment Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($equipment['name']); ?>" required>

        <label>Status:</label>
        <select name="status">
            <option value="Available" <?= $equipment['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
            <option value="In Use" <?= $equipment['status'] == 'In Use' ? 'selected' : '' ?>>In Use</option>
            <option value="Under Maintenance" <?= $equipment['status'] == 'Under Maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
        </select>

        <label>Availability:</label>
        <input type="text" name="availability" value="<?php echo htmlspecialchars($equipment['availability']); ?>" required>

        <button type="submit">Update Equipment</button>
    </form>
</body>
</html>

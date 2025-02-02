<?php
session_start(); 


$conn = new mysqli("localhost", "root", "", "swap_project_db");


if ($conn->connect_error) {
    die("Database connection failed.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF verification failed.");
    }

    
    $name = htmlspecialchars(trim($_POST['name']));
    $status = htmlspecialchars(trim($_POST['status']));
    $availability = htmlspecialchars(trim($_POST['availability']));

    if (!empty($name) && !empty($status) && !empty($availability)) {
        $stmt = $conn->prepare("INSERT INTO research_equipment (name, status, availability) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $status, $availability);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Equipment added successfully!";
            header("Location: read_equipment.php");
            exit;
        } else {
            $_SESSION['error_msg'] = "Error adding equipment.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_msg'] = "All fields are required!";
    }
}


$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Equipment</title>
</head>
<body>
    <h2>Add New Equipment</h2>

    
    <?php if (isset($_SESSION['success_msg'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_msg'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label>Equipment Name:</label>
        <input type="text" name="name" required>

        <label>Status:</label>
        <select name="status">
            <option value="Available">Available</option>
            <option value="In Use">In Use</option>
            <option value="Under Maintenance">Under Maintenance</option>
        </select>

        <label>Availability:</label>
        <input type="text" name="availability" required>

        <button type="submit">Add Equipment</button>
    </form>
</body>
</html>

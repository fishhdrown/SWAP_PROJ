<?php
$dbHost = 'localhost';
$dbUser = 'admin';
$dbPass = 'admin';
$dbName = 'project_swap';

// Connect to database
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}

// Handle POST request (Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize input data
        $upd_title = htmlspecialchars(trim($_POST['upd_title']));
        $upd_description = htmlspecialchars(trim($_POST['upd_description']));
        $upd_funding = filter_var($_POST['upd_funding'], FILTER_SANITIZE_NUMBER_INT);
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

        // Prepare update statement
        $update_stmt = $con->prepare("UPDATE research_projects SET title = ?, description = ?, funding = ? WHERE id = ?");
        if ($update_stmt === false) {
            throw new Exception('Prepare failed: ' . $con->error);
        }

        // Bind parameters and execute
        $update_stmt->bind_param('ssii', $upd_title, $upd_description, $upd_funding, $id);
        if ($update_stmt->execute()) {
            echo "<script>alert('Record updated successfully!');</script>";
            echo "<script>window.location.href='select_research_projects.php';</script>";
            exit;
        } else {
            throw new Exception("Error updating record: " . $update_stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// GET request handling (Display form)
$edit_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$stmt = $con->prepare("SELECT * FROM research_projects WHERE id = ?");
if ($stmt === false) {
    die('Prepare failed: ' . $con->error);
}

$stmt->bind_param('i', $edit_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Form</title>
    <style>
        th {
            text-align: left;
            padding-right: 10px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        table {
            margin: 20px auto;
            padding: 10px;
            /* border-collapse: collapse; */
            background-color: #fdfd96;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 5px;
        }
        input[type="submit"], input[type="button"] {
            padding: 5px 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php if ($result->num_rows > 0): ?>
        <?php $row = $result->fetch_assoc(); ?>
        <form action="update_research_projects.php?id=<?php echo htmlspecialchars($edit_id); ?>" method="POST">
            <table border="0">
                <tr>
                    <th>ID</th>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td>
                        <input type="text" name="upd_title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <input type="text" name="upd_description" value="<?php echo htmlspecialchars($row['description']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>Funding</th>
                    <td>
                        <input type="number" name="upd_funding" value="<?php echo htmlspecialchars($row['funding']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        <input type="submit" value="Update Record">
                        <input type="button" value="Cancel" onclick="window.location.href='select_research_projects.php'">
                    </td>
                </tr>
            </table>
        </form>
    <?php else: ?>
        <p class="error">Record not found.</p>
    <?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$con->close();
?>

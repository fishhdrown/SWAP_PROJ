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

        // Check for duplicate title
        $check_stmt = $con->prepare("SELECT id FROM research_projects WHERE title = ? AND id != ?");
        if ($check_stmt === false) {
            throw new Exception('Prepare failed: ' . $con->error);
        }

        $check_stmt->bind_param('si', $upd_title, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Title already exists. Please try a different title.');</script>";
            $check_stmt->close();
        } else {
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

            $update_stmt->close();
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
    <title>Update Research Project</title>
    <link rel="stylesheet" href="http://localhost/SWAP_PROJ/css/C02_read.css"> <!-- Link to the external CSS -->
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Container to add margin-top to avoid overlap with navbar */
        .container {
            margin-top: 80px; /* Adjust based on your navbar height */
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #f9f9f9;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            word-wrap: break-word;
            max-width: 200px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"], input[type="button"] {
            padding: 10px 15px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        a {
            text-decoration: none;
            color: blue;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Include the navigation bar -->
    <?php include('../navigation.php'); ?> <!-- Include the navigation bar -->

    <div class="container">
        <h1>Editing Research Project</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php $row = $result->fetch_assoc(); ?>
            <form action="update_research_projects.php?id=<?php echo htmlspecialchars($edit_id); ?>" method="POST">
                <table border="0">
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
                        <th>Funding (in $)</th>
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
    </div>

</body>
</html>

<?php
$stmt->close();
$con->close();
?>

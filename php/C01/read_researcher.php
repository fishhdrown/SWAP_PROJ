<?php
// Database credentials
$dbHost = 'localhost';
$dbUser = 'admin';
$dbPass = 'admin';
$dbName = 'project_swap';

// Connect to MySQL
$con = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch researcher profiles
$stmt = $con->prepare("SELECT name, email, expertise_id, assigned_projects_id, role FROM researcher_profiles");
if (!$stmt) {
    die("Query failed: " . $con->error);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Researcher Profiles</title>
    <link rel="stylesheet" href="../css/navigation.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 140px;
            background-color: #ffffff;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            padding: 15px;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        /* Styling for the Edit button */
        .edit-btn {
            background-color:#4caf50;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
            text-decoration: none;
            display: inline-block;
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include('../navigation.php'); ?>
    
    <h1>Researcher Profiles</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Expertise ID</th>
            <th>Assigned Projects ID</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['expertise_id'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['assigned_projects_id'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['role'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="select_researcher.php?email=<?php echo urlencode($row['email']); ?>" class="edit-btn">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No data found</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

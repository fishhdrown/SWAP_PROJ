<?php

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database credentials
    $dbHost = getenv('DB_HOST') ?: 'localhost';
    $dbUser = 'admin';
    $dbPass = 'admin';
    $dbName = 'project_swap';

    // Connect to MySQL
    $con = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    return $con;

    // Fetch researcher profiles
    $stmt = $con->prepare("SELECT * FROM researcher_profiles");
    if (!$stmt) {
        die("Query failed: " . $con->error);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
}
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

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include('../navigation.php'); ?>
    
    <div class="header-container">
        <h1>Researcher Profiles</h1>
    </div>
    
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
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['expertise_id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['assigned_projects_id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['role'] ?? ''); ?></td>
                    <td>
                        <button>Edit</button>
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
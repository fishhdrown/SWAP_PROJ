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

// Fetch researcher profiles
$stmt = $con->prepare("SELECT * FROM researcher_profiles");
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
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
            margin-bottom: 10px;
            color: #4CAF50;
            padding: 15px;
            font-size: 28px;
            font-weight: bold;
        }
        .header-container {
            text-align: center;
            margin-top: 120px;
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            margin: auto;
            margin-top: 12px;
            border-collapse: collapse;
            background-color: white;
            color: #333;
            border-radius: 8px;
            overflow: hidden;
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
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .action-buttons button {
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .action-buttons .edit {
            background-color: #4CAF50;
            color: white;
        }
        .action-buttons .edit:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .action-buttons .delete {
            background-color: #f44336;
            color: white;
        }
        .action-buttons .delete:hover {
            background-color: #e53935;
            transform: scale(1.05);
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
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Expertise ID</th>
            <th>Assigned Projects ID</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['expertise_id']); ?></td>
                <td><?php echo htmlspecialchars($row['assigned_projects_id']); ?></td>
                <td class="action-buttons">
                    <a href="update_researcher.php?item_id=<?php echo $row['id']; ?>">
                        <button class="edit">Edit</button>
                    </a>
                    <a href="delete_researcher.php?item_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this researcher?');">
                        <button class="delete">Delete</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php mysqli_close($con); ?>

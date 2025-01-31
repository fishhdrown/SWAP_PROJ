<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database credentials
    $dbHost = 'localhost';  // Server location
    $dbUser = 'admin';      // Username
    $dbPass = 'admin';      // Password
    $dbName = 'project_swap'; // Database name

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
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 140px; /* Ensure padding accounts for the navbar height */
            background-color: #ffffff;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px; /* Reduced spacing between header and table */
            color: #4CAF50;
            padding: 15px;
            font-size: 28px;
            font-weight: bold;
        }

        .header-container {
            text-align: center;
            margin-top: 120px;
            margin-bottom: 20px; /* Adjusted margin for better spacing */
        }

        /* Table styling */
        table {
            width: 80%;
            margin: auto; /* Center table horizontally */
            margin-top: 12px; /* Added margin-top to lower the table */
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

        /* Button styling */
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
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Expertise ID</th>
            <th>Assigned Projects ID</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['expertise_id']); ?></td>
                <td><?php echo htmlspecialchars($row['assigned_projects_id']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td>
                    <a href="select_researcher.php?id=<?php echo $row['id']; ?>">
                        <button>Edit</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

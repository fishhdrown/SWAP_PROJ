<?php

$conn = mysqli_connect("localhost", "root", "", "project_swap");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT * FROM research_equipment";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equipment List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
        }

        
        .add-button {
            display: block;
            width: fit-content;
            margin: 10px auto;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            font-size: 16px;
        }

        .add-button:hover {
            background-color: #45a049;
        }

        
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        
        .action-links a {
            margin: 0 5px;
            color: blue;
            text-decoration: none;
        }

        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Equipment List</h2>

    
    <a href="create_equipment.php" class="add-button">➕ Add Equipment</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['availability']); ?></td>
                <td class="action-links">
                    <a href="update_equipment.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="delete_equipment.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php

$conn->close();
?>

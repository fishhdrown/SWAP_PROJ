<html>
<head>
    <style>
        #title {
            text-align: center;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h3 id="title">Researcher Profiles</h3>

<?php
// Connect to the database
$con = mysqli_connect("localhost", "admin", "admin", "project_swap");
if (!$con) {
    die('Could not connect: ' . mysqli_connect_errno()); // Return error if connection fails
}

// Prepare and execute query to fetch all records
$stmt = $con->prepare("SELECT * FROM researcher_profiles");
$stmt->execute();
$result = $stmt->get_result();

// Display records in a table
echo '<table>';
echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Expertise ID</th><th>Assigned Projects ID</th></tr>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['name'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['expertise_id'] . '</td>';
    echo '<td>' . $row['assigned_projects_id'] . '</td>';
    echo '</tr>';
}

echo '</table>';

$stmt->close();
$con->close();
?>

<!-- Button to navigate to insert_proj.php or select_proj.php -->
<div style="text-align: center; margin-top: 20px;">
    <a href="select_proj.php">
        <button>Add Researcher Profiles</button>
    </a>
    <a href="insert_proj.php" style="margin-left: 20px;">
        <button>Update Profiles</button>
    </a>
</div>

</body>
</html>
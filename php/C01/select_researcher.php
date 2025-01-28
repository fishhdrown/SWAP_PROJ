<html>
<head>
    <title>Researcher Profiles</title>
    <style>
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
        #button-container {
            text-align: center;
            margin-top: 20px;
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
        .action-buttons button.edit {
            background-color: #4CAF50; /* Green */
            color: white;
        }
        .action-buttons button.edit:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .action-buttons button.delete {
            background-color: #f44336; /* Red */
            color: white;
        }
        .action-buttons button.delete:hover {
            background-color: #e53935;
            transform: scale(1.05);
        }
        .action-buttons button:focus {
            outline: none;
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
echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Expertise ID</th><th>Assigned Projects ID</th><th>Actions</th></tr>';
 
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['name'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['expertise_id'] . '</td>';
    echo '<td>' . $row['assigned_projects_id'] . '</td>';
    echo '<td class="action-buttons">';
    // Edit Button
    echo '<a href="update_researcher.php?item_id=' . $row['id'] . '"><button class="edit">Edit</button></a> ';
    // Delete Button
    echo '<a href="delete_researcher.php?item_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this researcher?\');"><button class="delete">Delete</button></a>';
    echo '</td>';
    echo '</tr>';
}
 
echo '</table>';
 
$stmt->close();
$con->close();
?>
 
<!-- Button to navigate to insert_researcher.php -->
<div id="button-container">
    <a href="insert_researcher.php">
        <button>Add More Researchers</button>
    </a>
</div>
 
</body>
</html>
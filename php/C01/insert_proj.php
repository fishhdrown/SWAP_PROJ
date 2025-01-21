<html>
<head>
    <style>
        #title {
            text-align: center;
        }
    </style>
</head>
<body>  

<h3 id="title">Researcher Profiles</h3>

<?php
// Connect to database
$con = mysqli_connect("localhost", "admin", "admin", "project_swap"); 
if (!$con) {
    die('Could not connect: ' . mysqli_connect_errno());
}

// Fetch and display all records from researcher_profiles
$stmt = $con->prepare("SELECT * from researcher_profiles");
$stmt->execute();
$result = $stmt->get_result();

// Display records in a table
echo '<table border="1" bgcolor="pink" align="center">';
echo '<tr><th>name</th><th>email</th><th>expertise_id</th><th>assigned_projects_id</th><th colspan="2">CRUD</th></tr>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>'.$row['name'].'</td>';
    echo '<td>'.$row['email'].'</td>';
    echo '<td>'.$row['expertise_id'].'</td>';
    echo '<td>'.$row['assigned_projects_id'].'</td>';
    echo '<td><a href="update_proj.php?item_id='.$row['id'].'">Edit</a></td>';  // Update link
    echo '<td><a href="delete_proj.php?item_id='.$row['id'].'">Delete</a></td>'; // Delete link
    echo '</tr>';
}

echo '</table>';

$stmt->close();
$con->close();
?>

<!-- Button to navigate back to select_proj.php -->
<div style="text-align: center;">
    <a href="select_proj.php">
        <button>Insert New Profiles</button>
    </a>
</div>

</body>
</html>
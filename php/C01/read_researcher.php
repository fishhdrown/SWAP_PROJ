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
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #4CAF50; /* Green */
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
 
<h2>Researcher Profiles</h2>
 
<?php
// Database connection
$con = mysqli_connect("localhost", "admin", "admin", "project_swap");
if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}
 
$stmt = $con->prepare("SELECT * FROM researcher_profiles");
$stmt->execute();
$result = $stmt->get_result();
 
// Display the records
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
 
<div class="button-container">
    <!-- Back button to return to Insert Researcher page -->
    <a href="insert_researcher.php">
        <button>Back</button>
    </a>
</div>
 
</body>
</html>
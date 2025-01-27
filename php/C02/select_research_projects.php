<!DOCTYPE html>
<html>
<head>
    <title>Research Projects</title>
</head>
<body>

<?php
// Database connection credentials
$dbHost = 'localhost';
$dbUser = 'admin';
$dbPass = 'admin';
$dbName = 'project_swap';

// Connect to database
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if (!$con) {
    die('Could not connect: ' . mysqli_connect_error()); // Return error if connection fails
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data from the form
    $in_title = htmlspecialchars($_POST["in_title"]);
    $in_description = htmlspecialchars($_POST["in_description"]);
    $in_funding = intval($_POST["in_funding"]); // Ensure funding is an integer

    // Prepare SQL query using a prepared statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO research_projects (title, description, funding) VALUES (?, ?, ?)");

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die('Prepare failed: ' . $con->error);
    }

    // Bind parameters to the SQL statement
    // 's' stands for string, 'i' stands for integer
    $stmt->bind_param('ssi', $in_title, $in_description, $in_funding);

    // Execute the query and check if the insert was successful
    if ($stmt->execute()) {
        echo "Insert Query executed successfully.";
        header("Location: select_research_projects.php"); // Redirect to another page after success
        exit; // Ensure no further code is executed after the redirect
    } else {
        echo "Error executing INSERT query: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Fetch and display data from the database
$query = "SELECT * FROM research_projects";
$result = mysqli_query($con, $query);

if ($result) {
    echo '<table border="1" bgcolor="turquoise" align="center">';
    echo '<tr><th>ID</th><th>Title</th><th>Description</th><th>Funding</th><th colspan="2">UPDATE</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        // echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['title'] . '</td>';
        echo '<td>' . $row['description'] . '</td>';
        echo '<td>' . $row['funding'] . '</td>';
        echo '<td><a href="update_research_projects.php?id=' . $row['id'] . '">Edit</a></td>';  // Update link
        echo '<td><a href="delete_research_projects.php?id=' . $row['id'] . '">Delete</a></td>'; // Delete link
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "Error fetching data: " . mysqli_error($con);
}

// Close SQL connection
mysqli_close($con);
?>

</body>
</html>
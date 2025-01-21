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
    $in_id = htmlspecialchars($_POST["id"]);
    $in_title = htmlspecialchars($_POST["in_title"]);
    $in_description = htmlspecialchars($_POST["in_description"]);
    $in_funding = htmlspecialchars($_POST["in_funding"]);
    $in_team_members = htmlspecialchars($_POST["in_team_members"]);

    // Prepare SQL query using a prepared statement to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO `research_projects` (`id`, `title`, `description`, `funding`, `team_members`) VALUES (?, ?, ?, ?, ?)");

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die('Prepare failed: ' . $con->error);
    }

    // Bind parameters to the SQL statement
    // 's' stands for string, 'i' stands for integer
    $stmt->bind_param('ssdis', $in_id, $in_title, $in_description, $in_funding, $in_team_members);

    // Execute the query and check if the insert was successful
    if ($stmt->execute()) {
        echo "Insert Query executed successfully.";
        header("Location: select_research_projects.php"); // Redirect to another page after success
        exit; // Ensure no further code is executed after the redirect
    } else {
        echo "Error executing INSERT query: " . $stmt->error;
    }

    // Close the prepared statement and database connection
    $stmt->close();
}

// Close SQL connection
$con->close();
?>
<!DOCTYPE html>
<body>  
    nice
</body>
</html>

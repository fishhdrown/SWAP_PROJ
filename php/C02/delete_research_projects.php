<html>
<body>  
<?php

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

// Prepare the statement 
$stmt= $con->prepare("DELETE FROM research_projects WHERE id=?");

// Sanitize the GET entry
$del_id = htmlspecialchars($_GET["id"]);


// Bind the parameters 
$stmt->bind_param('i', $del_id); 
if ($stmt->execute()){
  echo "<script>alert('Record deleted successfully!');</script>";
  echo "<script>window.location.href='select_research_projects.php';</script>";
    exit;

}else{
  echo "Error executing DELETE query.";
}
?>
</body>
</html>

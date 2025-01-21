<!DOCTYPE html>
<body>  
<?php
$con = mysqli_connect("project_swap"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}

// Prepare the statement
$stmt= $con->prepare("INSERT INTO `item` (`title`, `description`, `funding`) VALUES (?,?,?)");

/*
// inputs from form via Superglobals $_POST or $_GET
$name='ADMIN USER1';
$pwd = 'admin1pwd';
$address = 'ang mo kio ave 2';
$email = 'admin1@email.com';
$contact = '11223344';
$role = 'ADMIN';
*/

$title = htmlspecialchars($_POST["title"]);
$description = htmlspecialchars($_POST["description"]);
$funding = htmlspecialchars($_POST["funding"]);


//bind the parameters
$stmt->bind_param('ssi', $title, $description, $funding); 

//execute query
if ($stmt->execute()){  
    print "Insert Query executed.";
    header("location:create_research_projects.php");
}else{
  echo "Error executing INSERT query.";
}

// Close SQL Connection
$con->close();
?>
</body>
</html>

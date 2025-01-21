<html>
<body>  
<?php
$con = mysqli_connect("localhost","admin","admin","project_swap"); //connect to database
if (!$con){
	die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}

// Prepare the statement 
$stmt= $con->prepare("DELETE FROM research_projects WHERE id=?");

// Sanitize the GET entry
$del_itemid = htmlspecialchars($_GET["id"]);


// Bind the parameters 
$stmt->bind_param('i', $del_id); 
if ($stmt->execute()){
 echo "Delete Query executed.";
 header("location:select_research_projects.php");

}else{
  echo "Error executing DELETE query.";
}
?>
</body>
</html>

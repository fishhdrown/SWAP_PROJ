<?php
// Start of PHP logic for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database credentials
    $dbHost = 'localhost';  // server location
    $dbUser = 'admin';      // username
    $dbPass = 'admin';      // password
    $dbName = 'project_swap'; // name of database

    // Connect to the database
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    // Check connection (seriously, no errors pls :') )
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);  // If connection fails, no data, no fun :(
    }

    // Retrieve form data [getting those user inputs >:)) ]
    $in_title = htmlspecialchars($_POST['in_title']);       // Sanitize the project title
    $in_description = htmlspecialchars($_POST['in_description']);  // Sanitize the project description
    $in_funding = htmlspecialchars($_POST['in_funding']);         // Sanitize the funding amount
    $in_team_members = htmlspecialchars($_POST['in_team_members']); // Sanitize the number of team members

    // Input validation
    if (empty($in_title) || empty($in_description) || empty($in_funding) || empty($in_team_members)) {
        // Popup error message if fields are empty
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    alert('All fields are required!');  // Simple alert for missing input
                });
            </script>
        ";
    } else {
        // Prepare SQL statement to insert data into the database
        $stmt = $conn->prepare("INSERT INTO research_projects (title, description, funding, team_members) VALUES (?, ?, ?, ?)");

        // Bind parameters to the statement (bind values for each placeholder)
        $stmt->bind_param('ssdi', $in_title, $in_description, $in_funding, $in_team_members); 

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        alert('Project inserted successfully!');
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        alert('Error inserting data.');
                    });
                  </script>";
        }

        // Close the statement and the connection
        $stmt->close();
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Research Project</title>
    <style>
        #title {
            text-align: center;
        }
        table {
            border: 1px solid #000;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<!-- Page Title -->
<h3 id="title">Insert Research Project</h3>

<!-- Form to insert a new research project -->
<form action="" method="POST">
    <table align="center">
        <tr>
            <td>Title:</td>
            <td><input type="text" name="in_title" required></td>
        </tr>
        <tr>
            <td>Description:</td>
            <td><input type="text" name="in_description" required></td>
        </tr>
        <tr>
            <td>Funding:</td>
            <td><input type="number" step="0.01" name="in_funding" required></td>
        </tr>
        <tr>
            <td>Team Members:</td>
            <td><input type="number" name="in_team_members" required></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Insert Record">
            </td>
        </tr>
    </table>
</form>

</body>
</html>

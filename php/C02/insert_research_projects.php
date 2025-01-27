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

    // Input validation
    if (empty($in_title) || empty($in_description) || empty($in_funding)) {
        // Popup error message if fields are empty
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    alert('All fields are required!');  // Simple alert for missing input
                });
            </script>
        ";
    } else {
        // Check for duplicate title
        $check_query = $conn->prepare("SELECT COUNT(*) FROM research_projects WHERE title = ?");
        $check_query->bind_param('s', $in_title);
        $check_query->execute();
        $check_query->bind_result($title_count);
        $check_query->fetch();
        $check_query->close();

        if ($title_count > 0) {
            // Alert user if title already exists
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        alert('Title already exists! Please use a unique title.');
                    });
                </script>
            ";
        } else {
            // Prepare SQL statement to insert data into the database
            $stmt = $conn->prepare("INSERT INTO research_projects (`title`, `description`, `funding`) VALUES (?, ?, ?)");

            if ($stmt) {
                // Bind parameters to the statement (bind values for each placeholder)
                $stmt->bind_param('ssi', $in_title, $in_description, $in_funding); 

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

                // Close the statement
                $stmt->close();
            } else {
                echo "Error preparing the SQL statement.";
            }
        }
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
            <td><input type="number" name="in_funding" required></td>
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
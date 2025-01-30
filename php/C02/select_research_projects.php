<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Projects</title>
    <link rel="stylesheet" href="http://localhost/SWAP_PROJ/css/C02_read.css"> <!-- Link to the external CSS -->
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Container to add margin-top to avoid overlap with navbar */
        .container {
            margin-top: 80px; /* Adjust based on your navbar height (e.g., 60px for navbar) */
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #f9f9f9;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            word-wrap: break-word;
            max-width: 200px;
        }

        a {
            text-decoration: none;
            color: blue;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Navigation bar styling can be customized in the navigation.php file */
    </style>
</head>
<body>

    <?php include('../navigation.php'); ?> <!-- Include the navigation bar -->

    <div class="container">
        <h1>Editing Research Projects</h1>

        <!-- Display research projects in a table -->
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
            die('Could not connect: ' . mysqli_connect_error());
        }

        // Fetch and display data from the database
        $query = "SELECT * FROM research_projects";
        $result = mysqli_query($con, $query);

        if ($result) {
            echo '<table>';
            echo '<tr><th>Title</th><th>Description</th><th>Fundings (in $)</th><th>Update</th><th>Delete</th></tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                echo '<td>' . htmlspecialchars($row['funding']) . '</td>';
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
    </div>

</body>
</html>

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

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $in_title = htmlspecialchars($_POST['in_title']);
    $in_description = htmlspecialchars($_POST['in_description']);
    $in_funding = htmlspecialchars($_POST['in_funding']);

    // Input validation
    if (empty($in_title) || empty($in_description) || empty($in_funding)) {
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    alert('All fields are required!');
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
            echo "
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        alert('Title already exists! Please use a unique title.');
                    });
                </script>
            ";
        } else {
            // Insert project
            $stmt = $conn->prepare("INSERT INTO research_projects (`title`, `description`, `funding`) VALUES (?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param('ssi', $in_title, $in_description, $in_funding);
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
                $stmt->close();
            } else {
                echo "Error preparing the SQL statement.";
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/SWAP_PROJ/css/C02_read.css">
    <style>
        /* Adjust for margin-top to prevent overlap with fixed navbar */
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 80px; /* Adjust based on your navbar height (60px) + padding */
            padding: 20px;
        }

        #title {
            text-align: center;
        }

        table {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-title {
            font-size: 24px;
            text-align: center;
        }

        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

    <?php include('../navigation.php'); ?> <!-- Include the navigation bar -->

    <div class="container">
        <!-- Page Title -->
        <h1 class="form-title">New Research Project</h1>

        <!-- Form to insert a new research project -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="in_title">Title:</label>
                <input type="text" name="in_title" id="in_title" required>
            </div>

            <div class="form-group">
                <label for="in_description">Description:</label>
                <input type="text" name="in_description" id="in_description" required>
            </div>

            <div class="form-group">
                <label for="in_funding">Fundings (in $):</label>
                <input type="number" name="in_funding" id="in_funding" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Insert Record">
            </div>
        </form>

        <!-- Error or success message -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && (empty($in_title) || empty($in_description) || empty($in_funding))): ?>
            <p class="error-message">Please fill in all fields before submitting.</p>
        <?php endif; ?>
    </div>

</body>
</html>

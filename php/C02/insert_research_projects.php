<?php
session_start(); // Start session for CSRF protection

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Start of PHP logic for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed.");
    }

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

    // Retrieve and sanitize form data
    $in_title = htmlspecialchars(trim($_POST['in_title']));
    $in_description = htmlspecialchars(trim($_POST['in_description']));
    $in_funding = filter_var($_POST['in_funding'], FILTER_VALIDATE_INT);

    // Input validation
    if (empty($in_title) || empty($in_description) || $in_funding === false) {
        echo "
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    alert('All fields are required and funding must be a valid number!');
                });
            </script>
        ";
    } else {
        // Check for duplicate title securely
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
            // Insert project securely
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
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 80px;
            padding: 20px;
        }

        .form-title {
            font-size: 24px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
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
    </style>
</head>
<body>

    <?php include('../navigation.php'); ?> <!-- Include the navigation bar -->

    <div class="container">
        <!-- Page Title -->
        <h1 class="form-title">New Research Project</h1>

        <!-- Form to insert a new research project -->
        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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
    </div>

</body>
</html>

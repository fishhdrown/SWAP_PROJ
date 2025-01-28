<?php
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

// Initialize variables
$search_query = '';
$search_results = [];
$project_details = null;

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_query'])) {
    $search_query = filter_var($_POST['search_query'], FILTER_SANITIZE_STRING);

    if (strlen($search_query) >= 3) {
        $search_stmt = $con->prepare("SELECT id, title FROM research_projects WHERE title LIKE ?");
        if ($search_stmt === false) {
            die('Prepare failed: ' . $con->error);
        }

        $search_term = '%' . $search_query . '%';
        $search_stmt->bind_param('s', $search_term);
        $search_stmt->execute();
        $search_results = $search_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $search_stmt->close();
    }
}

// Handle project detail fetch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
    $selected_id = filter_var($_POST['project_id'], FILTER_SANITIZE_NUMBER_INT);

    $detail_stmt = $con->prepare("SELECT * FROM research_projects WHERE id = ?");
    if ($detail_stmt === false) {
        die('Prepare failed: ' . $con->error);
    }

    $detail_stmt->bind_param('i', $selected_id);
    $detail_stmt->execute();
    $details_result = $detail_stmt->get_result();
    if ($details_result->num_rows > 0) {
        $project_details = $details_result->fetch_assoc();
    }
    $detail_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Research Projects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        input[type="text"], input[type="submit"] {
            padding: 5px;
            margin: 10px 0;
        }
        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Search Research Projects</h1>
    <form method="POST" action="">
        <label for="search_query">Search by Title (min 3 letters):</label>
        <input type="text" name="search_query" id="search_query" value="<?php echo htmlspecialchars($search_query); ?>" required>
        <input type="submit" value="Search">
    </form>

    <?php if (!empty($search_results)): ?>
        <h2>Search Results</h2>
        <form method="POST" action="">
            <table>
                <tr>
                    <th>Select</th>
                    <th>Title</th>
                </tr>
                <?php foreach ($search_results as $result): ?>
                    <tr>
                        <td>
                            <input type="radio" name="project_id" value="<?php echo htmlspecialchars($result['id']); ?>" required>
                        </td>
                        <td><?php echo htmlspecialchars($result['title']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <input type="submit" value="View Details">
        </form>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strlen($search_query) >= 3): ?>
        <p style="color: red;">No projects found matching your search.</p>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p style="color: red;">Please enter at least 3 letters to search.</p>
    <?php endif; ?>

    <?php if ($project_details): ?>
        <h2>Project Details</h2>
        <table>
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($project_details['id']); ?></td>
            </tr>
            <tr>
                <th>Title</th>
                <td><?php echo htmlspecialchars($project_details['title']); ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo htmlspecialchars($project_details['description']); ?></td>
            </tr>
            <tr>
                <th>Funding</th>
                <td><?php echo htmlspecialchars($project_details['funding']); ?></td>
            </tr>
        </table>
    <?php endif; ?>
</body>
</html>

<?php
// Close connection
mysqli_close($con);
?>

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

// Fetch all titles from the database
$title_query = "SELECT id, title FROM research_projects";
$title_result = mysqli_query($con, $title_query);

// Handle form submission (fetch details for the selected title)
$project_details = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <title>Read Research Projects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        select, input[type="submit"] {
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
    <h1>View Research Projects</h1>
    <form method="POST" action="">
        <label for="project_id">Select a Project Title:</label>
        <select name="project_id" id="project_id" required>
            <option value="">-- Select Title --</option>
            <?php while ($row = mysqli_fetch_assoc($title_result)): ?>
                <option value="<?php echo htmlspecialchars($row['id']); ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <input type="submit" value="View Details">
    </form>

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
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p style="color: red;">No details found for the selected project.</p>
    <?php endif; ?>
</body>
</html>

<?php
// Close connection
mysqli_close($con);
?>

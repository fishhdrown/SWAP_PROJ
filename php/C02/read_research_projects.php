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
    <title>Research Projects Database</title>
    <link rel="stylesheet" href="http://localhost/SWAP_PROJ/css/C02_read.css">
</head>
<body>
    <div class="container">
        <h1>Research Projects Database</h1>
        
        <div class="search-form">
            <form method="POST" action="">
                <label for="search_query">Search Projects</label>
                <input type="text" 
                        name="search_query" 
                        id="search_query" 
                        value="<?php echo htmlspecialchars($search_query); ?>" 
                        placeholder="Enter at least 3 characters to search..."
                        required>
                <input type="submit" value="Search Projects">
            </form>
        </div>

        <?php if (!empty($search_results)): ?>
            <div class="results-container">
                <h2>Search Results</h2>
                <form method="POST" action="">
                    <div class="results-list">
                        <?php foreach ($search_results as $result): ?>
                            <div class="radio-input">
                                <!-- Input for the radio button -->
                                <input type="radio" 
                                    id="radio_<?php echo $result['id']; ?>" 
                                    name="project_id" 
                                    value="<?php echo htmlspecialchars($result['id']); ?>" 
                                    required
                                    class="radio-btn">
                                
                                <!-- Custom Circle for the radio button -->
                                <div class="circle"></div>
                                
                                <!-- Label associated with the radio button -->
                                <label for="radio_<?php echo $result['id']; ?>">
                                    <?php echo htmlspecialchars($result['title']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="submit" value="View Details" style="margin-top: 20px;">
                </form>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && strlen($search_query) >= 3): ?>
            <p class="error-message">No projects found matching your search.</p>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="error-message">Please enter at least 3 letters to search.</p>
        <?php endif; ?>


        <?php if ($project_details): ?>
            <div class="project-details">
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
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php mysqli_close($con); ?>
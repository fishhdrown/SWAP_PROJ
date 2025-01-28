<?php
// homepage.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="homepage.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <ul class="dropdown-menu">
                <!-- Researcher Profiles Dropdown -->
                <li class="dropdown">
                    <button class="dropdown-btn">Researcher Profiles</button>
                    <ul class="dropdown-content">
                        <li><a href="#">View Profiles</a></li>
                        <li><a href="#">Add Profile</a></li>
                    </ul>
                </li>
                
                <!-- Research Projects Dropdown -->
                <li class="dropdown">
                    <button class="dropdown-btn">Research Projects</button>
                    <ul class="dropdown-content">
                        <li><a href="php/C02/insert_research_projects.php">Insert Project</a></li>
                        <li><a href="php/C02/select_research_projects.php">Select Project</a></li>
                        <li><a href="php/C02/update_research_projects.php">Update Project</a></li>
                        <li><a href="php/C02/delete_research_projects.php">Delete Project</a></li>
                        <li><a href="php/C02/read_research_projects.php">Read Project</a></li>
                    </ul>
                </li>
                
                <!-- Research Equipment Dropdown -->
                <li class="dropdown">
                    <button class="dropdown-btn">Research Equipment</button>
                    <ul class="dropdown-content">
                        <li><a href="#">View Equipment</a></li>
                        <li><a href="#">Add Equipment</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <h1>Welcome to the Research Management Dashboard</h1>
            <p>Select an option from the menu to get started.</p>
        </div>
    </div>

    <script>
        // JavaScript for dropdown behavior
        document.querySelectorAll('.dropdown-btn').forEach(button => {
            button.addEventListener('click', () => {
                const dropdownContent = button.nextElementSibling;
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>

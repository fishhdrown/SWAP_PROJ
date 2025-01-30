<?php
// homepage.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/navigation.css">
    <style>
        /* Reset default margins and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navigation Bar at the Top */
        .navbar {
            background-color: #333;
            color: #4CAF50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 60px;
            z-index: 1000;
        }
        
        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .menu {
            list-style-type: none;
            display: flex;
            gap: 20px;
        }

        .menu li {
            position: relative;
            padding: 10px;
            cursor: pointer;
        }

        .menu a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 16px;
        }

        /* Dropdown Menu */
        .dropdown-content {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            background-color: #444;
            min-width: 180px;
            top: 45px;
            left: 0;
            border-radius: 5px;
            transition: visibility 0.2s ease, opacity 0.2s ease;
        }

        .dropdown-content li {
            padding: 10px;
        }

        .dropdown-content a {
            color: #4CAF50;
            display: block;
        }

        /* Show dropdown when hovering over the parent item */
        .dropdown:hover .dropdown-content {
            visibility: visible;
            opacity: 1;
        }

        /* Adjust the position of content below the navbar */
        body {
            margin: 0;
            padding: 0;
        }

        /* Apply margin-top to the body or main content area to avoid overlap */
        .main-content {
            margin-top: 80px; /* Adjust based on your navbar height (60px) plus padding */
            padding: 20px;
        }

        /* Add a generic container that other pages can use */
        .container {
            margin-top: 80px; /* Adjust this value to prevent overlap with navbar */
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <h2>AMC Corporation Research</h2>
        <ul class="menu">
            <!-- Researcher Profiles Dropdown -->
            <li class="dropdown">
                <a href="#">Researcher Profiles</a>
                <ul class="dropdown-content">
                    <li><a href="#">View Profiles</a></li>
                    <li><a href="#">Add Profile</a></li>
                </ul>
            </li>
            
            <!-- Research Projects Dropdown -->
            <li class="dropdown">
                <a href="#">Research Projects</a>
                <ul class="dropdown-content">
                    <li><a href="/SWAP_PROJ/php/C02/insert_research_projects.php">Insert Project</a></li>
                    <li><a href="/SWAP_PROJ/php/C02/select_research_projects.php">Edit Project</a></li>
                    <li><a href="/SWAP_PROJ/php/C02/read_research_projects.php">Read Project</a></li>
                </ul>
            </li>

            <!-- Research Equipment Dropdown -->
            <li class="dropdown">
                <a href="#">Research Equipment</a>
                <ul class="dropdown-content">
                    <li><a href="#">View Equipment</a></li>
                    <li><a href="#">Add Equipment</a></li>
                </ul>
            </li>
        </ul>
    </div>

</body>
</html>

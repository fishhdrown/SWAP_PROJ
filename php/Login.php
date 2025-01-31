<?php
// Start of PHP logic for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $role = htmlspecialchars($_POST['role']);
    $passwordHash = htmlspecialchars(hash("sha512", $_POST['password']));

    // Validate form data
    if (empty($name) || empty($passwordHash) || empty($role)) {
        $error = "All fields are required!";
    } else {
        // Get the researcher record
        $recordQuery = "SELECT id, role, password_hash FROM researcher_profiles WHERE researcher_id = ? AND role = ?";
        $query = $conn->prepare($recordQuery);
        $query->bind_param("ss", $researcherId, $role);
        $query->execute();
        $query->bind_result($userId, $userRole, $hash);
        $query->store_result();

        if ($query->num_rows > 0) {
            $query->fetch();
            if ($passwordHash != $hash) {
                $error = "Invalid credentials. Please try again.";
            } else {
                switch($userRole) {
                    case 'admin':
                        header('Location: /admin/dashboard.php');
                        break;
                    case 'researcher':
                        header('Location: /researcher/dashboard.php');
                        break;
                    case 'research_assistant':
                        header('Location: /assistant/dashboard.php');
                        break;
                    default:
                        header('Location: /dashboard.php');
                }
                exit();
            }
        } else {
            $error = "Invalid credentials. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Portal Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; height: 100vh; display: flex; }
        .container { display: flex; width: 100%; height: 100%; }
        .info-section { width: 50%; background: linear-gradient(135deg, #1a237e, #0d47a1); padding: 40px; color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .logo { max-width: 200px; margin-bottom: 30px; }
        .features { list-style: none; }
        .features li { margin: 15px 0; display: flex; align-items: center; }
        .features li:before { content: "✓"; margin-right: 10px; color: #4caf50; }
        .login-section { width: 50%; background: white; padding: 40px; display: flex; align-items: center; justify-content: center; }
        .login-container { width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #333; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .submit-btn { width: 100%; padding: 12px; background-color: #1a237e; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .submit-btn:hover { background-color: #0d47a1; }
        .error-message { background-color: #ff5252; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
        .forgot-password { text-align: right; margin-top: 10px; }
        .forgot-password a { color: #1a237e; text-decoration: none; }
        .forgot-password a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Info Section -->
        <div class="info-section">
            <img src="../image/logo.png" alt="Research Portal Logo" class="logo">
            <h2>Research Portal</h2>
        </div>

        <!-- Login Section -->
        <div class="login-section">
            <div class="login-container">
                <h1>Sign In</h1>
                <p>Welcome to the Research Portal. Please login with your credentials.</p>
                <br>
                
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name"><b>Name<b></label>
                        <input type="text" name="name" id="name" placeholder="Enter your name" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>

                    <div class="forgot-password">
                        <a href="forgot_password.php">Forgot Password?</a>
                    </div>

                    <button type="submit" class="submit-btn">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

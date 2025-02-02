<?php
session_start();


$conn = mysqli_connect("localhost", "root", "", "swap_project_db");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}


$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : '';

    
    if (empty($name) || empty($password) || empty($role)) {
        $error = "All fields are required!";
    } else {
        
        $query = $conn->prepare("SELECT id, role, password_hash FROM researcher_profiles WHERE name = ? AND role = ?");
        $query->bind_param("ss", $name, $role);
        $query->execute();
        $query->bind_result($userId, $userRole, $hash);
        $query->store_result();

        if ($query->num_rows > 0) {
            $query->fetch();
            if (!password_verify($password, $hash)) {
                $error = "Invalid credentials. Please try again.";
            } else {
                $_SESSION['user_id'] = $userId;
                $_SESSION['role'] = $userRole;

                
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
    <title>Research Portal Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; height: 100vh; display: flex; }
        .container { display: flex; width: 100%; height: 100%; }
        .login-section { width: 100%; background: white; padding: 40px; display: flex; align-items: center; justify-content: center; }
        .login-container { width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #333; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .submit-btn { width: 100%; padding: 12px; background-color: #1a237e; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .submit-btn:hover { background-color: #0d47a1; }
        .error-message { background-color: #ff5252; color: white; padding: 10px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Login Section -->
        <div class="login-section">
            <div class="login-container">
                <h1>Sign In</h1>
                <p>Welcome to the Research Portal. Please login with your credentials.</p>
                <br>

                <?php if (!empty($error)): ?>
                    <div class="error-message">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name"><b>Name</b></label>
                        <input type="text" name="name" id="name" placeholder="Enter your name" required>
                    </div>

                    <div class="form-group">
                        <label for="password"><b>Password</b></label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>

                    <div class="form-group">
                        <label for="role"><b>Select Role</b></label>
                        <select name="role" id="role" required>
                            <option value="admin">Admin</option>
                            <option value="researcher">Researcher</option>
                            <option value="research_assistant">Research Assistant</option>
                        </select>
                    </div>

                    <button type="submit" class="submit-btn">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

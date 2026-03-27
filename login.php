<?php
require_once 'includes/db.php';
session_start();

// 1. If already logged in, skip the login page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShieldScan | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-96 border border-gray-700">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-cyan-400">ShieldScan</h2>
            <p class="text-gray-400 text-sm">Privacy Risk Control System</p>
        </div>
        
        <?php if (isset($_GET['registered'])): ?>
            <div class="bg-green-900/50 border border-green-500 text-green-200 p-3 rounded mb-4 text-sm text-center">
                Account created! Please log in.
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-900/50 border border-red-500 text-red-200 p-3 rounded mb-4 text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Username</label>
                <input type="text" name="username" required 
                    class="w-full bg-gray-700 border border-gray-600 rounded p-2 focus:outline-none focus:border-cyan-400">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Password</label>
                <input type="password" name="password" required 
                    class="w-full bg-gray-700 border border-gray-600 rounded p-2 focus:outline-none focus:border-cyan-400">
            </div>
            <button type="submit" 
                class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 rounded transition duration-200">
                Authorized Access
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-gray-400">
            Don't have an account? <a href="register.php" class="text-cyan-400 hover:underline">Register here</a>
        </p>
    </div>
</body>
</html>
<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/config.php'; // 1. Pull in the secret key from config
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $confirm = $_POST['confirm'];
    $user_secret = $_POST['secret_key'];

    // 2. Check the user input AGAINST the constant from config.php
    if ($user_secret !== SYSTEM_REGISTRATION_KEY) {
        $msg = "Invalid System Secret Key. Access Denied.";
    } elseif ($pass !== $confirm) {
        $msg = "Passwords do not match!";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            header("Location: login.php?registered=success");
            exit;
        } catch (PDOException $e) {
            $msg = "Username already taken.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShieldScan | Secure Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md border border-gray-700">
        <h2 class="text-3xl font-bold text-cyan-400 text-center mb-2">Join ShieldScan</h2>
        <p class="text-center text-gray-400 text-sm mb-8">Authorized Analyst Registration</p>
        
        <?php if($msg): ?>
            <div class="bg-red-900/30 border border-red-500 text-red-200 p-3 rounded-lg mb-6 text-sm text-center">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="text" name="username" placeholder="Choose Username" required 
                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 outline-none focus:border-cyan-400 transition">
            
            <input type="password" name="password" placeholder="Password" required 
                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 outline-none focus:border-cyan-400 transition">
            
            <input type="password" name="confirm" placeholder="Confirm Password" required 
                class="w-full bg-gray-700 border border-gray-600 rounded-lg p-3 outline-none focus:border-cyan-400 transition">
            
            <div class="pt-2 border-t border-gray-700">
                <label class="block text-xs text-cyan-500 uppercase font-bold mb-1">System Secret Key</label>
                <input type="text" name="secret_key" placeholder="Enter Authorization Key" required 
                    class="w-full bg-gray-900 border border-cyan-900/50 rounded-lg p-3 outline-none focus:border-cyan-400 transition text-cyan-400 font-mono">
            </div>

            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 font-bold py-3 rounded-lg shadow-lg transition">
                Create Account
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-gray-400">
            Already registered? <a href="login.php" class="text-cyan-400 hover:underline">Log in here</a>
        </p>
    </div>
</body>
</html>
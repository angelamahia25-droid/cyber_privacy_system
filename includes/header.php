<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShieldScan v1.0 | Data Privacy Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <nav class="bg-gray-800 border-b border-gray-700 p-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-2xl">🛡️</span>
                <h1 class="text-xl font-bold text-cyan-400 tracking-tight">ShieldScan</h1>
            </div>
            
            <?php if(isset($_SESSION['user_id'])): ?>
            <div class="hidden md:flex space-x-6 text-sm items-center">
                <a href="dashboard.php" class="hover:text-cyan-400 transition">Dashboard</a>
                <a href="inventory.php" class="hover:text-cyan-400 transition">Inventory</a>
                <a href="scan.php" class="hover:text-red-400 font-bold transition">Risk Monitor</a>
                <a href="logs.php" class="hover:text-cyan-400 transition">Audit Logs</a>
                <a href="policy.php" class="hover:text-cyan-400 transition">Policy</a>
                
                <div class="h-4 w-[1px] bg-gray-600"></div>
                
                <span class="text-gray-500 text-xs">User: <span class="text-gray-300"><?php echo htmlspecialchars($_SESSION['username']); ?></span></span>
                <a href="logout.php" class="bg-red-900/30 text-red-400 px-3 py-1 rounded border border-red-900/50 hover:bg-red-900/50 transition">Logout</a>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container mx-auto mt-8 px-4 flex-grow pb-12">
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ShieldScan v1.0 | Data Privacy Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* This hides the scrollbar but allows swiping for a cleaner look */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col overflow-x-hidden">
    <nav class="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
        <div class="container mx-auto">
            <div class="flex justify-between items-center p-4">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">🛡️</span>
                    <h1 class="text-lg font-bold text-cyan-400 tracking-tight">ShieldScan</h1>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="bg-red-900/30 text-red-400 text-[10px] uppercase font-bold px-3 py-1 rounded border border-red-900/50">Logout</a>
                <?php endif; ?>
            </div>

            <?php if(isset($_SESSION['user_id'])): ?>
            <div class="flex overflow-x-auto no-scrollbar border-t border-gray-700/50 bg-gray-800/50">
                <div class="flex flex-nowrap px-4 py-3 space-x-6 text-xs font-medium uppercase tracking-wider">
                    <a href="dashboard.php" class="hover:text-cyan-400 whitespace-nowrap">Dashboard</a>
                    <a href="inventory.php" class="hover:text-cyan-400 whitespace-nowrap">Inventory</a>
                    <a href="scan.php" class="hover:text-red-400 whitespace-nowrap font-bold">Risk Monitor</a>
                    <a href="logs.php" class="hover:text-cyan-400 whitespace-nowrap">Audit Logs</a>
                    <a href="policy.php" class="hover:text-cyan-400 whitespace-nowrap text-gray-500">Policy</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container mx-auto mt-6 px-4 flex-grow pb-12 max-w-full overflow-x-hidden">
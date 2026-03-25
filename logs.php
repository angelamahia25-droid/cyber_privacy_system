<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch historical logs by joining the tables
$query = "
    SELECT risk_logs.*, data_assets.asset_name, data_assets.data_type 
    FROM risk_logs 
    JOIN data_assets ON risk_logs.asset_id = data_assets.id 
    ORDER BY found_at DESC 
    LIMIT 50
";
$logs = $pdo->query($query)->fetchAll();
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white">System Audit Trail</h2>
        <p class="text-gray-400">Historical sequence of privacy risk detection events.</p>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-700/50 text-gray-400 text-xs uppercase tracking-widest">
                    <th class="p-4 border-b border-gray-700">Event Timestamp</th>
                    <th class="p-4 border-b border-gray-700">Resource Name</th>
                    <th class="p-4 border-b border-gray-700">Data Type</th>
                    <th class="p-4 border-b border-gray-700">Risk Level</th>
                    <th class="p-4 border-b border-gray-700 text-right">Action Logged</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                <?php foreach ($logs as $log): ?>
                <tr class="border-b border-gray-700/50 hover:bg-gray-700/30 transition">
                    <td class="p-4 text-sm font-mono text-gray-500"><?php echo $log['found_at']; ?></td>
                    <td class="p-4 font-bold text-cyan-400"><?php echo htmlspecialchars($log['asset_name']); ?></td>
                    <td class="p-4 text-sm"><?php echo htmlspecialchars($log['data_type']); ?></td>
                    <td class="p-4">
                        <span class="bg-red-900/30 text-red-500 border border-red-900 px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                            <?php echo $log['risk_level']; ?>
                        </span>
                    </td>
                    <td class="p-4 text-right text-xs italic text-gray-600 font-mono">
                        SCAN_DETECT_PLAINTEXT_ERROR
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="5" class="p-20 text-center text-gray-600 italic">
                        No security events found in audit history.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
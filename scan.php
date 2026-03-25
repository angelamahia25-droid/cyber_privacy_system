<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

// HANDLER: Remediation Action
if (isset($_POST['fix_asset_id'])) {
    $asset_id = $_POST['fix_asset_id'];
    $update = $pdo->prepare("UPDATE data_assets SET is_encrypted = 1 WHERE id = ?");
    $update->execute([$asset_id]);
    $success_msg = "Remediation Successful: Asset has been encrypted.";
}

// THE SCANNER LOGIC - Smart Detection
$stmt = $pdo->query("SELECT * FROM data_assets WHERE sensitivity = 'High' AND is_encrypted = 0");
$vulnerabilities = $stmt->fetchAll();

foreach ($vulnerabilities as $v) {
    // Only log if we haven't logged this specific asset in the last hour to prevent bloat
    $checkLog = $pdo->prepare("SELECT COUNT(*) FROM risk_logs WHERE asset_id = ? AND found_at > datetime('now', '-1 hour')");
    $checkLog->execute([$v['id']]);
    
    if ($checkLog->fetchColumn() == 0) {
        $logStmt = $pdo->prepare("INSERT INTO risk_logs (asset_id, risk_level) VALUES (?, 'Critical')");
        $logStmt->execute([$v['id']]);
    }
}

$riskCount = count($vulnerabilities);
?>

<div class="max-w-5xl mx-auto">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-white">Vulnerability Monitor</h2>
            <p class="text-gray-400 text-sm">Real-time Privacy Impact Assessment</p>
        </div>
        <a href="scan.php" class="bg-cyan-600 hover:bg-cyan-500 px-4 py-2 rounded text-xs font-bold transition uppercase tracking-widest text-white">Run Fresh Scan</a>
    </div>

    <?php if (isset($success_msg)): ?>
        <div class="bg-green-900/30 border border-green-500 text-green-200 p-4 rounded-lg mb-6 flex justify-between">
            <span><?php echo $success_msg; ?></span>
            <button onclick="this.parentElement.remove()" class="text-green-500 font-bold">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($riskCount > 0): ?>
        <div class="grid grid-cols-1 gap-4">
            <?php foreach ($vulnerabilities as $v): ?>
                <div class="bg-gray-800 border border-red-900/50 p-6 rounded-xl flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0 text-left">
                        <div class="flex items-center mb-1">
                            <span class="w-3 h-3 bg-red-600 rounded-full animate-pulse mr-2"></span>
                            <h4 class="text-xl font-bold text-white"><?php echo htmlspecialchars($v['asset_name']); ?></h4>
                        </div>
                        <p class="text-gray-400 text-sm">Classification: <span class="text-red-400 font-mono">High Sensitivity</span></p>
                    </div>
                    
                    <div class="flex flex-col items-end">
                        <form method="POST">
                            <input type="hidden" name="fix_asset_id" value="<?php echo $v['id']; ?>">
                            <button type="submit" class="bg-green-600 hover:bg-green-500 text-white text-xs font-bold px-6 py-3 rounded-lg transition shadow-lg">
                                APPLY ENCRYPTION
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-gray-800 border border-gray-700 p-16 text-center rounded-2xl">
            <div class="inline-block p-4 rounded-full bg-green-900/20 text-green-500 text-4xl mb-4">🛡️</div>
            <h3 class="text-2xl font-bold text-white mb-2">Perimeter Secure</h3>
            <p class="text-gray-400">All high-sensitivity assets comply with encryption standards.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
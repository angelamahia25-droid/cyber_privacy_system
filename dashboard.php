<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

$total_assets = $pdo->query("SELECT COUNT(*) FROM data_assets")->fetchColumn();
$encrypted_count = $pdo->query("SELECT COUNT(*) FROM data_assets WHERE is_encrypted = 1")->fetchColumn();

$stmt = $pdo->query("SELECT * FROM data_assets WHERE sensitivity = 'High' AND is_encrypted = 0");
$critical_risks = $stmt->fetchAll();
$risk_count = count($critical_risks);

$score = ($total_assets > 0) ? round((($total_assets - $risk_count) / $total_assets) * 100) : 100;
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-white">Security Dashboard</h2>
    <p class="text-gray-400">Automated Privacy Risk Assessment</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 text-white">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
        <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mb-2">Total Assets</p>
        <p class="text-3xl font-bold"><?php echo $total_assets; ?></p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
        <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mb-1">Security Score</p>
        <div class="w-full bg-gray-700 rounded-full h-2 mb-2">
            <div class="h-2 rounded-full <?php echo $score > 70 ? 'bg-green-500' : 'bg-red-500'; ?>" style="width: <?php echo $score; ?>%"></div>
        </div>
        <p class="text-2xl font-bold"><?php echo $score; ?>%</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-lg">
        <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mb-2">Encrypted</p>
        <p class="text-3xl font-bold text-cyan-400"><?php echo $encrypted_count; ?></p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg border <?php echo $risk_count > 0 ? 'border-red-600 bg-red-900/10' : 'border-gray-700'; ?>">
        <p class="text-gray-400 text-xs uppercase font-bold tracking-widest mb-2">Active Risks</p>
        <p class="text-3xl font-bold text-red-500"><?php echo $risk_count; ?></p>
    </div>
</div>

<div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-2xl">
    <div class="bg-gray-700/30 p-4 border-b border-gray-700">
        <h3 class="font-bold text-white uppercase tracking-tighter">🚨 High-Risk Exposures</h3>
    </div>
    <div class="p-6">
        <?php if ($risk_count > 0): ?>
            <div class="space-y-4">
                <?php foreach ($critical_risks as $risk): ?>
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-red-900/10 border border-red-900/50 rounded-lg">
                        <div class="mb-3 md:mb-0">
                            <h4 class="font-bold text-red-200"><?php echo htmlspecialchars($risk['asset_name']); ?></h4>
                            <p class="text-xs text-red-300 opacity-70">Exposed Data: <span class="font-mono bg-black/40 px-2 py-1 rounded"><?php echo htmlspecialchars($risk['sample_data']); ?></span></p>
                        </div>
                        <a href="scan.php" class="text-[10px] font-bold bg-red-600 text-white px-3 py-2 rounded hover:bg-red-500 transition text-center">FIX IN RISK MONITOR</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="py-10 text-center">
                <span class="text-4xl block mb-4">🛡️</span>
                <p class="text-green-400 font-bold">Zero Critical Vulnerabilities Detected.</p>
                <p class="text-gray-500 text-sm">All high-sensitivity data is cryptographically protected.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
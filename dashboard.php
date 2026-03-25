<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

// THE SCANNER ENGINE 
$total_assets = $pdo->query("SELECT COUNT(*) FROM data_assets")->fetchColumn();
$encrypted_count = $pdo->query("SELECT COUNT(*) FROM data_assets WHERE is_encrypted = 1")->fetchColumn();

$stmt = $pdo->query("SELECT * FROM data_assets WHERE sensitivity = 'High' AND is_encrypted = 0");
$critical_risks = $stmt->fetchAll();
$risk_count = count($critical_risks);

$score = ($total_assets > 0) ? round((($total_assets - $risk_count) / $total_assets) * 100) : 100;
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold text-white">Security Dashboard</h2>
    <p class="text-gray-400">Automated Privacy Risk Assessment for Small Businesses</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 text-white">
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <p class="text-gray-400 text-sm uppercase font-semibold">Total Assets</p>
        <p class="text-3xl font-bold"><?php echo $total_assets; ?></p>
    </div>
    
    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <p class="text-gray-400 text-sm uppercase font-semibold mb-2">Security Score</p>
        <div class="w-full bg-gray-700 rounded-full h-3 mb-2">
            <div class="h-3 rounded-full transition-all duration-1000 <?php echo $score > 70 ? 'bg-green-500' : ($score > 40 ? 'bg-yellow-500' : 'bg-red-500'); ?>" 
                 style="width: <?php echo $score; ?>%"></div>
        </div>
        <p class="text-2xl font-bold"><?php echo $score; ?>%</p>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg border border-gray-700">
        <p class="text-gray-400 text-sm uppercase font-semibold">Encrypted Assets</p>
        <p class="text-3xl font-bold text-cyan-400"><?php echo $encrypted_count; ?></p>
    </div>

    <div class="bg-gray-800 p-6 rounded-lg border <?php echo $risk_count > 0 ? 'border-red-600 bg-red-900/10' : 'border-gray-700'; ?>">
        <p class="<?php echo $risk_count > 0 ? 'text-red-400' : 'text-gray-400'; ?> text-sm uppercase font-semibold">Critical Risks</p>
        <p class="text-3xl font-bold <?php echo $risk_count > 0 ? 'text-red-500' : 'text-white'; ?>"><?php echo $risk_count; ?></p>
    </div>
</div>

<div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden text-white">
    <div class="bg-gray-700/50 p-4 border-b border-gray-700 flex justify-between items-center">
        <h3 class="font-bold <?php echo $risk_count > 0 ? 'text-red-400' : 'text-green-400'; ?>">
            <?php echo $risk_count > 0 ? 'Vulnerabilities Detected' : 'System Status: Secure'; ?>
        </h3>
    </div>
    <div class="p-6">
        <?php if ($risk_count > 0): ?>
            <div class="space-y-4">
                <?php foreach ($critical_risks as $risk): ?>
                    <div class="flex items-start p-4 bg-red-900/20 border border-red-800 rounded">
                        <span class="mr-4 text-2xl">⚠️</span>
                        <div>
                            <h4 class="font-bold text-red-200"><?php echo htmlspecialchars($risk['asset_name']); ?> Exposed</h4>
                            <p class="text-sm text-red-300/80">Asset contains High Sensitivity data but is stored in Plaintext.</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="flex items-center text-green-400">
                <span class="mr-3 text-2xl">✅</span>
                <p>All high-sensitivity data assets are correctly encrypted.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
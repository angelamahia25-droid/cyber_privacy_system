<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

// --- LOGIC: Handle New Asset Addition (CREATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_asset'])) {
    $name = $_POST['asset_name'];
    $type = $_POST['data_type'];
    $sensitivity = $_POST['sensitivity'];
    $sample = $_POST['sample_data']; // New Field
    $encrypted = isset($_POST['is_encrypted']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO data_assets (asset_name, data_type, sensitivity, sample_data, is_encrypted) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $type, $sensitivity, $sample, $encrypted]);
}

// --- LOGIC: Handle Asset Retirement (DELETE) ---
if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM data_assets WHERE id = ?");
    if ($stmt->execute([$_POST['delete_id']])) {
        $logCleanup = $pdo->prepare("DELETE FROM risk_logs WHERE asset_id = ?");
        $logCleanup->execute([$_POST['delete_id']]);
    }
    echo "<script>window.location.href='inventory.php';</script>";
}

$assets = $pdo->query("SELECT * FROM data_assets ORDER BY id DESC")->fetchAll();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-white">
    <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-xl h-fit">
        <h3 class="text-xl font-bold text-cyan-400 mb-4 flex items-center">
            <span class="mr-2">➕</span> Register New Asset
        </h3>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Asset Name</label>
                <input type="text" name="asset_name" placeholder="e.g., Payroll Database" required 
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 outline-none focus:border-cyan-400 transition">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Sample Data Preview (e.g. Card No.)</label>
                <input type="text" name="sample_data" placeholder="e.g. 4242-5555-6666-7777" required 
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 outline-none focus:border-cyan-400 transition font-mono text-cyan-200">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Data Category</label>
                <select name="data_type" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 outline-none focus:border-cyan-400">
                    <option>Personal Identifiable Information (PII)</option>
                    <option>Financial Records</option>
                    <option>Intellectual Property</option>
                    <option>Credentials/Passwords</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gray-500 mb-1">Impact Level</label>
                <select name="sensitivity" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 outline-none focus:border-cyan-400">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="flex items-center space-x-3 p-2 bg-gray-900/50 rounded-lg">
                <input type="checkbox" name="is_encrypted" id="enc" class="w-5 h-5 accent-cyan-500 bg-gray-700 border-gray-600 rounded">
                <label for="enc" class="text-sm text-gray-300">Data is Encrypted at Rest</label>
            </div>
            <button type="submit" name="add_asset" class="w-full bg-cyan-600 hover:bg-cyan-500 font-bold py-3 rounded-lg shadow-lg transition transform active:scale-95">
                Commit to Registry
            </button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-xl">
        <h3 class="text-xl font-bold text-cyan-400 mb-4">Asset Inventory</h3>
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-700 text-xs uppercase tracking-widest">
                        <th class="pb-3 px-2">Data Resource</th>
                        <th class="pb-3 px-2">Data Sample</th>
                        <th class="pb-3 px-2 text-center">Security</th>
                        <th class="pb-3 px-2 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    <?php foreach ($assets as $asset): ?>
                    <tr class="hover:bg-gray-700/30 transition group">
                        <td class="py-4 px-2">
                            <div class="font-bold text-white"><?php echo htmlspecialchars($asset['asset_name']); ?></div>
                            <div class="text-[10px] text-gray-500 uppercase"><?php echo htmlspecialchars($asset['data_type']); ?></div>
                        </td>
                        <td class="py-4 px-2 font-mono text-[10px]">
                            <?php if ($asset['is_encrypted']): ?>
                                <span class="text-green-400"><?php echo htmlspecialchars($asset['sample_data']); ?></span>
                            <?php else: ?>
                                <span class="text-red-400"><?php echo htmlspecialchars($asset['sample_data']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-2 text-center">
                            <?php if ($asset['is_encrypted']): ?>
                                <span class="text-green-500 text-[10px] font-bold">LOCKED</span>
                            <?php else: ?>
                                <span class="text-red-500 text-[10px] font-bold animate-pulse">EXPOSED</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-2 text-right">
                            <form method="POST">
                                <input type="hidden" name="delete_id" value="<?php echo $asset['id']; ?>">
                                <button type="submit" class="text-gray-600 hover:text-red-500 text-[10px]">[ Retire ]</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
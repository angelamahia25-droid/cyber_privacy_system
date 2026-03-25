<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';
?>
<div class="max-w-3xl mx-auto bg-gray-800 p-8 rounded-xl border border-gray-700">
    <h2 class="text-3xl font-bold text-cyan-400 mb-6">Security Logic & Policy</h2>
    <div class="space-y-6 text-gray-300">
        <section>
            <h3 class="text-xl font-semibold text-white mb-2">1. Sensitivity Classification</h3>
            <p class="text-sm">Assets marked as <span class="text-red-400 font-bold">High</span> represent PII (Personally Identifiable Information) or financial data. Per GDPR and local data protection acts, these MUST be encrypted at rest.</p>
        </section>
        <section>
            <h3 class="text-xl font-semibold text-white mb-2">2. The Risk Scanner</h3>
            <p class="text-sm">The engine cross-references <code>sensitivity_level</code> against <code>is_encrypted</code>. Any mismatch triggers an immediate entry in the <strong>Audit Log</strong>.</p>
        </section>
    </div>
</div>
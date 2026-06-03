<?php
session_start();
header('Content-Type: application/json');
if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data   = json_decode(file_get_contents('php://input'), true) ?? [];

// ── Get targets ──
if ($method === 'GET' && $action === 'get') {
    $today      = date('Y-m-d');
    $month      = date('Y-m');

    // Get targets
    $stmt = $pdo->query("SELECT * FROM sales_targets ORDER BY created_at DESC");
    $targets = $stmt->fetchAll();

    $daily   = array_filter($targets, fn($t) => $t['target_type'] === 'daily');
    $monthly = array_filter($targets, fn($t) => $t['target_type'] === 'monthly');

    $dailyTarget   = $daily   ? reset($daily)['target_amount']   : 2000;
    $monthlyTarget = $monthly ? reset($monthly)['target_amount'] : 50000;

    // Get actual sales
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total),0) as total FROM transactions WHERE txn_date = ?");
    $stmt->execute([$today]);
    $dailySales = $stmt->fetch()['total'];

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total),0) as total FROM transactions
                           WHERE DATE_FORMAT(txn_date, '%Y-%m') = ?");
    $stmt->execute([$month]);
    $monthlySales = $stmt->fetch()['total'];

    echo json_encode([
        'daily_target'   => $dailyTarget,
        'monthly_target' => $monthlyTarget,
        'daily_sales'    => $dailySales,
        'monthly_sales'  => $monthlySales,
        'daily_pct'      => $dailyTarget > 0 ? min(100, round($dailySales / $dailyTarget * 100)) : 0,
        'monthly_pct'    => $monthlyTarget > 0 ? min(100, round($monthlySales / $monthlyTarget * 100)) : 0,
    ]);
    exit;
}

// ── Update target ──
if ($method === 'POST' && $action === 'update') {
    $stmt = $pdo->prepare("DELETE FROM sales_targets WHERE target_type = ?");
    $stmt->execute([$data['type']]);

    $stmt = $pdo->prepare("INSERT INTO sales_targets (target_type, target_amount, target_month, created_by)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['type'], $data['amount'],
        $data['type'] === 'monthly' ? date('Y-m') : null,
        $data['created_by'] ?? 'admin'
    ]);
    echo json_encode(['success' => true]);
    exit;
}
?>
<?php
session_start();
header('Content-Type: application/json');
if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
require_once '../config/database.php';

$action = $_GET['action'] ?? '';

// ── Today's summary ──
if ($action === 'summary') {
    $today = date('Y-m-d');
    $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
    $thisMonthStart = date('Y-m-d', strtotime('first day of this month'));

    // Today
    $stmt = $pdo->prepare("SELECT COUNT(*) as count, COALESCE(SUM(total),0) as total
                           FROM transactions WHERE txn_date = ?");
    $stmt->execute([$today]);
    $todayData = $stmt->fetch();

    // This week
    $stmt = $pdo->prepare("SELECT COUNT(*) as count, COALESCE(SUM(total),0) as total
                           FROM transactions WHERE txn_date >= ?");
    $stmt->execute([$thisWeekStart]);
    $weekData = $stmt->fetch();

    // This month
    $stmt = $pdo->prepare("SELECT COUNT(*) as count, COALESCE(SUM(total),0) as total
                           FROM transactions WHERE txn_date >= ?");
    $stmt->execute([$thisMonthStart]);
    $monthData = $stmt->fetch();

    // All time
    $stmt = $pdo->query("SELECT COUNT(*) as count, COALESCE(SUM(total),0) as total FROM transactions");
    $allData = $stmt->fetch();

    // Total cost (profit calculation)
    $stmt = $pdo->query("SELECT COALESCE(SUM(ti.quantity * p.cost),0) as total_cost
                         FROM transaction_items ti
                         LEFT JOIN products p ON ti.product_name = p.name");
    $costData = $stmt->fetch();

    // Payment method breakdown
    $stmt = $pdo->query("SELECT payment_method, COALESCE(SUM(total),0) as total
                         FROM transactions GROUP BY payment_method");
    $payData = $stmt->fetchAll();

    // Low stock count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE stock <= reorder_point AND stock > 0");
    $lowStock = $stmt->fetch();

    // Out of stock count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products WHERE stock = 0");
    $outStock = $stmt->fetch();

    // Last 7 days sales for chart
    $stmt = $pdo->query("SELECT txn_date, COALESCE(SUM(total),0) as total
                         FROM transactions
                         WHERE txn_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                         GROUP BY txn_date ORDER BY txn_date ASC");
    $chartData = $stmt->fetchAll();

    // Top 5 products
    $stmt = $pdo->query("SELECT product_name, SUM(quantity) as units, SUM(quantity * unit_price) as revenue
                         FROM transaction_items
                         GROUP BY product_name
                         ORDER BY revenue DESC LIMIT 5");
    $topProducts = $stmt->fetchAll();

    echo json_encode([
        'today'       => $todayData,
        'week'        => $weekData,
        'month'       => $monthData,
        'all'         => $allData,
        'cost'        => $costData,
        'payments'    => $payData,
        'low_stock'   => $lowStock['count'],
        'out_stock'   => $outStock['count'],
        'chart'       => $chartData,
        'top_products'=> $topProducts,
    ]);
    exit;
}
?>
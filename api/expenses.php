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

// ── List expenses ──
if ($method === 'GET' && $action === 'list') {
    $stmt = $pdo->query("SELECT * FROM expenses ORDER BY expense_date DESC, created_at DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Summary (total by category) ──
if ($method === 'GET' && $action === 'summary') {
    $today      = date('Y-m-d');
    $monthStart = date('Y-m-d', strtotime('first day of this month'));

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0) as total FROM expenses WHERE expense_date = ?");
    $stmt->execute([$today]);
    $todayTotal = $stmt->fetch()['total'];

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(amount),0) as total FROM expenses WHERE expense_date >= ?");
    $stmt->execute([$monthStart]);
    $monthTotal = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COALESCE(SUM(amount),0) as total FROM expenses");
    $allTotal = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT category, COALESCE(SUM(amount),0) as total
                         FROM expenses GROUP BY category ORDER BY total DESC");
    $byCategory = $stmt->fetchAll();

    echo json_encode([
        'today' => $todayTotal,
        'month' => $monthTotal,
        'all'   => $allTotal,
        'by_category' => $byCategory
    ]);
    exit;
}

// ── Add expense ──
if ($method === 'POST' && $action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO expenses (title, category, amount, notes, expense_date, added_by)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['title'], $data['category'], $data['amount'],
        $data['notes'] ?? '', $data['expense_date'],
        $data['added_by'] ?? 'Unknown'
    ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

// ── Edit expense ──
if ($method === 'POST' && $action === 'edit') {
    $stmt = $pdo->prepare("UPDATE expenses SET title=?, category=?, amount=?, notes=?, expense_date=?
                           WHERE id=?");
    $stmt->execute([
        $data['title'], $data['category'], $data['amount'],
        $data['notes'] ?? '', $data['expense_date'], $data['id']
    ]);
    echo json_encode(['success' => true]);
    exit;
}

// ── Delete expense ──
if ($method === 'POST' && $action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true]);
    exit;
}
?>
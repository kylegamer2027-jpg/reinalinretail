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

// ── List all customers ──
if ($method === 'GET' && $action === 'list') {
    $stmt = $pdo->query("
        SELECT c.*,
        COALESCE(SUM(t.total),0) as total_spent,
        COUNT(t.id) as txn_count,
        COALESCE(SUM(CASE WHEN t.payment_method='Utang' AND cr.paid=0 THEN t.total ELSE 0 END),0) as outstanding_credit
        FROM customers c
        LEFT JOIN transactions t ON t.customer_name = c.name
        LEFT JOIN credits cr ON cr.customer_name = c.name AND cr.paid = 0
        GROUP BY c.id
        ORDER BY c.name ASC
    ");
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Search customers ──
if ($method === 'GET' && $action === 'search') {
    $q = '%' . ($_GET['q'] ?? '') . '%';
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE name LIKE ? OR phone LIKE ? ORDER BY name ASC LIMIT 10");
    $stmt->execute([$q, $q]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Get single customer history ──
if ($method === 'GET' && $action === 'history') {
    $name = $_GET['name'] ?? '';
    $stmt = $pdo->prepare("SELECT t.*, GROUP_CONCAT(ti.quantity,'x ',ti.product_name SEPARATOR ', ') as items_summary
                           FROM transactions t
                           LEFT JOIN transaction_items ti ON t.id = ti.transaction_id
                           WHERE t.customer_name = ?
                           GROUP BY t.id ORDER BY t.created_at DESC");
    $stmt->execute([$name]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Add customer ──
if ($method === 'POST' && $action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO customers (name, phone, email, address, notes)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['name'], $data['phone'] ?? '',
        $data['email'] ?? '', $data['address'] ?? '',
        $data['notes'] ?? ''
    ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

// ── Edit customer ──
if ($method === 'POST' && $action === 'edit') {
    $stmt = $pdo->prepare("UPDATE customers SET name=?, phone=?, email=?, address=?, notes=?
                           WHERE id=?");
    $stmt->execute([
        $data['name'], $data['phone'] ?? '',
        $data['email'] ?? '', $data['address'] ?? '',
        $data['notes'] ?? '', $data['id']
    ]);
    echo json_encode(['success' => true]);
    exit;
}

// ── Delete customer ──
if ($method === 'POST' && $action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true]);
    exit;
}
?>
<?php
session_start();
header('Content-Type: application/json');
if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
require_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data   = json_decode(file_get_contents('php://input'), true) ?? [];

// ── List all products ──
if ($method === 'GET' && $action === 'list') {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Find by barcode ──
if ($method === 'GET' && $action === 'find_barcode') {
    $barcode = $_GET['barcode'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE barcode = ? OR sku = ?");
    $stmt->execute([$barcode, $barcode]);
    $product = $stmt->fetch();
    echo json_encode($product ? ['found' => true, 'product' => $product] : ['found' => false]);
    exit;
}

// ── Get expiring products ──
if ($method === 'GET' && $action === 'expiring') {
    $stmt = $pdo->query("SELECT * FROM products
                         WHERE expiry_date IS NOT NULL
                         AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL expiry_alert_days DAY)
                         ORDER BY expiry_date ASC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Get expired products ──
if ($method === 'GET' && $action === 'expired') {
    $stmt = $pdo->query("SELECT * FROM products
                         WHERE expiry_date IS NOT NULL
                         AND expiry_date < CURDATE()
                         ORDER BY expiry_date ASC");
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Add product ──
if ($method === 'POST' && $action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO products
        (name, category, emoji, cost, price, stock, reorder_point, img, sku, barcode, expiry_date, expiry_alert_days)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['name'], $data['category'], $data['emoji'] ?? '📦',
        $data['cost'] ?? 0, $data['price'],
        $data['stock'] ?? 0, $data['reorder_point'] ?? 10,
        $data['img'] ?? '',
        $data['sku'] ?: null,
        $data['barcode'] ?: null,
        $data['expiry_date'] ?: null,
        $data['expiry_alert_days'] ?? 30
    ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

// ── Edit product ──
if ($method === 'POST' && $action === 'edit') {
    $stmt = $pdo->prepare("UPDATE products SET
        name=?, category=?, emoji=?, cost=?, price=?,
        stock=?, reorder_point=?, img=?, sku=?, barcode=?,
        expiry_date=?, expiry_alert_days=?
        WHERE id=?");
    $stmt->execute([
        $data['name'], $data['category'], $data['emoji'] ?? '📦',
        $data['cost'] ?? 0, $data['price'],
        $data['stock'] ?? 0, $data['reorder_point'] ?? 10,
        $data['img'] ?? '',
        $data['sku'] ?: null,
        $data['barcode'] ?: null,
        $data['expiry_date'] ?: null,
        $data['expiry_alert_days'] ?? 30,
        $data['id']
    ]);
    echo json_encode(['success' => true]);
    exit;
}

// ── Restock product ──
if ($method === 'POST' && $action === 'restock') {
    $stmt = $pdo->prepare("SELECT stock, name FROM products WHERE id = ?");
    $stmt->execute([$data['id']]);
    $product = $stmt->fetch();

    $previousStock = $product['stock'];
    $newStock      = $previousStock + $data['qty'];

    $stmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
    $stmt->execute([$data['qty'], $data['id']]);

    $stmt = $pdo->prepare("INSERT INTO restock_logs
        (product_id, product_name, quantity_added, previous_stock, new_stock, restocked_by)
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['id'], $product['name'], $data['qty'],
        $previousStock, $newStock,
        $data['restocked_by'] ?? 'Unknown'
    ]);

    echo json_encode(['success' => true]);
    exit;
}

// ── Restock logs ──
if ($method === 'GET' && $action === 'restock_logs') {
    $stmt = $pdo->query("SELECT * FROM restock_logs ORDER BY created_at DESC LIMIT 50");
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Delete product ──
if ($method === 'POST' && $action === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true]);
    exit;
}
?>
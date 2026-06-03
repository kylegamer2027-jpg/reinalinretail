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

if ($method === 'GET' && $action === 'list') {
    $stmt = $pdo->query("SELECT t.* FROM transactions t ORDER BY t.created_at DESC");
    $txns = $stmt->fetchAll();
    foreach ($txns as &$txn) {
        $s = $pdo->prepare("SELECT * FROM transaction_items WHERE transaction_id = ?");
        $s->execute([$txn['id']]);
        $txn['items'] = $s->fetchAll();
    }
    echo json_encode($txns);
    exit;
}

if ($method === 'POST' && $action === 'add') {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO transactions (txn_code, total, payment_method, customer_name, staff_name, txn_date, txn_time)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['txn_code'], $data['total'], $data['payment_method'],
            $data['customer_name'] ?? null, $data['staff_name'],
            $data['txn_date'], $data['txn_time']
        ]);
        $txnId = $pdo->lastInsertId();

        foreach ($data['items'] as $item) {
            $s = $pdo->prepare("INSERT INTO transaction_items (transaction_id, product_name, quantity, unit_price)
                                VALUES (?, ?, ?, ?)");
            $s->execute([$txnId, $item['name'], $item['qty'], $item['price']]);

            $s2 = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE name = ?");
            $s2->execute([$item['qty'], $item['name']]);
        }

        if ($data['payment_method'] === 'Utang') {
            $s = $pdo->prepare("INSERT INTO credits (customer_name, total, txn_date) VALUES (?, ?, ?)");
            $s->execute([$data['customer_name'], $data['total'], $data['txn_date']]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'txn_id' => $txnId, 'txn_code' => $data['txn_code']]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}
?>
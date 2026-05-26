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

if ($method === 'GET' && $action === 'list') {
    $stmt = $pdo->query("SELECT * FROM credits ORDER BY created_at DESC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($method === 'POST' && $action === 'mark_paid') {
    $ids = $data['ids'] ?? [];
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("UPDATE credits SET paid = 1 WHERE id IN ($placeholders)");
        $stmt->execute($ids);
    }
    echo json_encode(['success' => true]);
    exit;
}
?>
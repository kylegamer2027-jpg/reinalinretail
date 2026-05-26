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
    $stmt = $pdo->query("SELECT * FROM staff ORDER BY last_name ASC");
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($method === 'POST' && $action === 'add') {
    $stmt = $pdo->prepare("INSERT INTO staff
        (first_name, middle_name, last_name, role, emp_type, salary_per_day,
         phone, email, address, city, province, dob, gender, date_hired,
         sss, philhealth, pagibig, tin, ec_name, ec_phone, ec_relation, username)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $data['first'], $data['middle'] ?? '', $data['last'],
        $data['role'], $data['empType'] ?? 'Full-time', $data['sal'],
        $data['phone'], $data['email'] ?? '', $data['address'] ?? '',
        $data['city'] ?? '', $data['province'] ?? '',
        $data['dob'] ?: null, $data['gender'] ?? '',
        $data['hired'] ?: null,
        $data['sss'] ?? '', $data['philhealth'] ?? '',
        $data['pagibig'] ?? '', $data['tin'] ?? '',
        $data['ecName'] ?? '', $data['ecPhone'] ?? '',
        $data['ecRel'] ?? '', $data['username'] ?? ''
    ]);

    if (!empty($data['username']) && !empty($data['password'])) {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        $us = $pdo->prepare("INSERT IGNORE INTO users (username, password, name, role) VALUES (?,?,?,?)");
        $us->execute([$data['username'], $hash, $data['first'].' '.$data['last'], $data['role']]);
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($method === 'POST' && $action === 'edit') {
    $stmt = $pdo->prepare("UPDATE staff SET
        first_name=?, middle_name=?, last_name=?, role=?, emp_type=?, salary_per_day=?,
        phone=?, email=?, address=?, city=?, province=?, dob=?, gender=?, date_hired=?,
        sss=?, philhealth=?, pagibig=?, tin=?, ec_name=?, ec_phone=?, ec_relation=?, username=?
        WHERE id=?");
    $stmt->execute([
        $data['first'], $data['middle'] ?? '', $data['last'],
        $data['role'], $data['empType'] ?? 'Full-time', $data['sal'],
        $data['phone'], $data['email'] ?? '', $data['address'] ?? '',
        $data['city'] ?? '', $data['province'] ?? '',
        $data['dob'] ?: null, $data['gender'] ?? '',
        $data['hired'] ?: null,
        $data['sss'] ?? '', $data['philhealth'] ?? '',
        $data['pagibig'] ?? '', $data['tin'] ?? '',
        $data['ecName'] ?? '', $data['ecPhone'] ?? '',
        $data['ecRel'] ?? '', $data['username'] ?? '',
        $data['id']
    ]);
    echo json_encode(['success' => true]);
    exit;
}
?>
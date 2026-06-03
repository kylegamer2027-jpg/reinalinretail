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

// ── List attendance ──
if ($method === 'GET' && $action === 'list') {
    $date = $_GET['date'] ?? date('Y-m-d');
    $stmt = $pdo->prepare("SELECT a.*, s.salary_per_day FROM attendance a
                           LEFT JOIN staff s ON a.staff_id = s.id
                           WHERE a.work_date = ? ORDER BY a.time_in ASC");
    $stmt->execute([$date]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Time in ──
if ($method === 'POST' && $action === 'time_in') {
    $today = date('Y-m-d');
    // Check if already timed in today
    $stmt = $pdo->prepare("SELECT id FROM attendance WHERE staff_id = ? AND work_date = ?");
    $stmt->execute([$data['staff_id'], $today]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Already timed in today']);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO attendance (staff_id, staff_name, time_in, work_date, status)
                           VALUES (?, ?, NOW(), ?, 'Present')");
    $stmt->execute([$data['staff_id'], $data['staff_name'], $today]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

// ── Time out ──
if ($method === 'POST' && $action === 'time_out') {
    $stmt = $pdo->prepare("SELECT * FROM attendance WHERE staff_id = ? AND work_date = ? AND time_out IS NULL");
    $stmt->execute([$data['staff_id'], date('Y-m-d')]);
    $record = $stmt->fetch();
    if (!$record) {
        echo json_encode(['success' => false, 'message' => 'No time-in record found']);
        exit;
    }
    // Calculate hours worked
    $timeIn  = new DateTime($record['time_in']);
    $timeOut = new DateTime();
    $hours   = round($timeIn->diff($timeOut)->h + $timeIn->diff($timeOut)->i / 60, 2);

    $stmt = $pdo->prepare("UPDATE attendance SET time_out = NOW(), hours_worked = ? WHERE id = ?");
    $stmt->execute([$hours, $record['id']]);
    echo json_encode(['success' => true, 'hours' => $hours]);
    exit;
}

// ── Get monthly summary ──
if ($method === 'GET' && $action === 'monthly') {
    $month = $_GET['month'] ?? date('Y-m');
    $stmt  = $pdo->prepare("SELECT a.staff_id, a.staff_name,
                            COUNT(*) as days_present,
                            SUM(a.hours_worked) as total_hours,
                            s.salary_per_day
                            FROM attendance a
                            LEFT JOIN staff s ON a.staff_id = s.id
                            WHERE DATE_FORMAT(a.work_date, '%Y-%m') = ?
                            AND a.time_out IS NOT NULL
                            GROUP BY a.staff_id, a.staff_name, s.salary_per_day");
    $stmt->execute([$month]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Generate payroll ──
if ($method === 'POST' && $action === 'generate_payroll') {
    $month      = $data['month'];
    $start      = $month . '-01';
    $end        = date('Y-m-t', strtotime($start));

    $stmt = $pdo->prepare("SELECT a.staff_id, a.staff_name,
                           COUNT(*) as days_worked,
                           s.salary_per_day
                           FROM attendance a
                           LEFT JOIN staff s ON a.staff_id = s.id
                           WHERE a.work_date BETWEEN ? AND ?
                           AND a.time_out IS NOT NULL
                           GROUP BY a.staff_id, a.staff_name, s.salary_per_day");
    $stmt->execute([$start, $end]);
    $records = $stmt->fetchAll();

    foreach ($records as $r) {
        $grossPay = $r['days_worked'] * $r['salary_per_day'];
        $deductions = $grossPay * 0.05; // 5% deduction example
        $netPay   = $grossPay - $deductions;

        // Check if payroll already exists
        $check = $pdo->prepare("SELECT id FROM payroll WHERE staff_id = ? AND period_start = ?");
        $check->execute([$r['staff_id'], $start]);
        if ($check->fetch()) continue;

        $stmt2 = $pdo->prepare("INSERT INTO payroll
            (staff_id, staff_name, period_start, period_end, days_worked, daily_rate, gross_pay, deductions, net_pay)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->execute([
            $r['staff_id'], $r['staff_name'], $start, $end,
            $r['days_worked'], $r['salary_per_day'],
            $grossPay, $deductions, $netPay
        ]);
    }
    echo json_encode(['success' => true]);
    exit;
}

// ── List payroll ──
if ($method === 'GET' && $action === 'payroll_list') {
    $month = $_GET['month'] ?? date('Y-m');
    $stmt  = $pdo->prepare("SELECT * FROM payroll
                            WHERE DATE_FORMAT(period_start, '%Y-%m') = ?
                            ORDER BY staff_name ASC");
    $stmt->execute([$month]);
    echo json_encode($stmt->fetchAll());
    exit;
}

// ── Mark payroll as paid ──
if ($method === 'POST' && $action === 'mark_paid') {
    $stmt = $pdo->prepare("UPDATE payroll SET status = 'Paid' WHERE id = ?");
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true]);
    exit;
}
?>
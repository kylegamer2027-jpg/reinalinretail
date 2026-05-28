<?php
session_start();
if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Only admin can backup
if ($_SESSION['user']['role'] !== 'Administrator') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

require_once '../db.php';

$action = $_GET['action'] ?? '';

if ($action === 'download') {
    $dbName   = 'reinalin_db';
    $filename = 'reinalin_backup_' . date('Y-m-d_H-i-s') . '.sql';

    // Set headers for file download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');

    $output = '';

    // Header comment
    $output .= "-- ============================================\n";
    $output .= "-- Reinalin Retail Management System\n";
    $output .= "-- Database Backup\n";
    $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $output .= "-- Database: " . $dbName . "\n";
    $output .= "-- ============================================\n\n";
    $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        // Drop table statement
        $output .= "-- Table: $table\n";
        $output .= "DROP TABLE IF EXISTS `$table`;\n";

        // Create table statement
        $create = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
        $output .= $create['Create Table'] . ";\n\n";

        // Get table data
        $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            $output .= "INSERT INTO `$table` VALUES\n";
            $values = [];
            foreach ($rows as $row) {
                $escaped = array_map(function($val) use ($pdo) {
                    if ($val === null) return 'NULL';
                    return "'" . str_replace("'", "''", $val) . "'";
                }, array_values($row));
                $values[] = '(' . implode(', ', $escaped) . ')';
            }
            $output .= implode(",\n", $values) . ";\n\n";
        }
    }

    $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
    $output .= "-- End of backup\n";

    echo $output;
    exit;
}

// Get backup stats
if ($action === 'stats') {
    header('Content-Type: application/json');
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $stats  = [];
    $totalRows = 0;

    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        $stats[] = ['table' => $table, 'rows' => $count];
        $totalRows += $count;
    }

    echo json_encode([
        'tables'     => count($tables),
        'total_rows' => $totalRows,
        'stats'      => $stats,
        'last_backup'=> date('Y-m-d H:i:s')
    ]);
    exit;
}
?>
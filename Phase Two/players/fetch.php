<?php
/**
 * DataTables Server-Side Processing
 */
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["draw" => 0, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Columns definition for DataTables
$columns = ['image_path', 'first_name', 'last_name', 'position', 'phone', 'email', 'team_name', 'id'];

// Count total records
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM players WHERE user_id = ?");
$countStmt->execute([$user_id]);
$totalRecords = $countStmt->fetchColumn();

// Base SQL query
$sql = "SELECT * FROM players WHERE user_id = :user_id";
$params = [':user_id' => $user_id];

// Searching
if (!empty($_POST['search']['value'])) {
    $search = $_POST['search']['value'];
    $sql .= " AND (first_name LIKE :s OR last_name LIKE :s OR position LIKE :s OR team_name LIKE :s OR email LIKE :s)";
    $params[':s'] = "%$search%";
}

// Get count after filtering
$filteredStmt = $pdo->prepare($sql);
$filteredStmt->execute($params);
$recordsFiltered = $filteredStmt->rowCount();

// Ordering
if (isset($_POST['order'])) {
    $columnIndex = $_POST['order'][0]['column'];
    $columnDir = $_POST['order'][0]['dir'];
    if (isset($columns[$columnIndex])) {
        $sql .= " ORDER BY " . $columns[$columnIndex] . " " . ($columnDir === 'desc' ? 'DESC' : 'ASC');
    }
} else {
    $sql .= " ORDER BY id DESC";
}

// Pagination
if (isset($_POST['start']) && $_POST['length'] != -1) {
    $sql .= " LIMIT " . (int)$_POST['start'] . ", " . (int)$_POST['length'];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

// Response JSON
echo json_encode([
    "draw" => intval($_POST['draw'] ?? 0),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($recordsFiltered),
    "data" => $data
]);
?>

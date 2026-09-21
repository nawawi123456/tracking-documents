<?php
// session_start();
// require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

if (!isset($_GET['divisi_id'])) {
    echo json_encode([]);
    exit;
}

$divisi_id = $_GET['divisi_id'];

$query = "SELECT user_id, nama_lengkap FROM users WHERE divisi_id = ? ORDER BY nama_lengkap ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $divisi_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);

?>
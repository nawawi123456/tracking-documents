<?php
// require_once 'config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$jenis = isset($_GET['jenis']) ? trim($_GET['jenis']) : '';

$user_id = $_SESSION['user_id'] ?? '';

$filterClauses = [];
if ($user_id == 'USER-999') {
    $filterClauses[] = '1=1';
} else {
    $filterClauses[] = "(d.div_pengirim = '" . $conn->real_escape_string($user_id) . "' OR d.div_penerima = '" . $conn->real_escape_string($user_id) . "' OR df.sent_by = '" . $conn->real_escape_string($user_id) . "' OR df.received_by = '" . $conn->real_escape_string($user_id) . "')";
}

if (!empty($search)) {
    $searchTerm = $conn->real_escape_string($search);
    $filterClauses[] = "(d.judul LIKE '%$searchTerm%' OR d.document_id LIKE '%$searchTerm%' OR d.deskripsi LIKE '%$searchTerm%')";
}

if (!empty($status) && $status != 'semua') {
    $filterClauses[] = "d.status = '" . $conn->real_escape_string($status) . "'";
}

if (!empty($jenis) && $jenis != 'semua') {
    $filterClauses[] = "d.jenis_dokumen = '" . $conn->real_escape_string($jenis) . "'";
}

$whereSql = count($filterClauses) > 0 ? ' WHERE ' . implode(' AND ', $filterClauses) : '';

// Paging and total count should use a lighter ID-only query before loading main row data.
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$idsQuery = "
    SELECT DISTINCT d.document_id
    FROM documents d
    LEFT JOIN document_flows df ON d.document_id = df.document_id
    $whereSql
";

$idsResult = mysqli_query($conn, $idsQuery);
$documentIds = [];

if ($idsResult) {
    while ($row = mysqli_fetch_assoc($idsResult)) {
        $documentIds[] = "'" . $conn->real_escape_string($row['document_id']) . "'";
    }
}

$totalItems = count($documentIds);
$totalPages = $totalItems > 0 ? (int)ceil($totalItems / $limit) : 0;

if ($totalItems === 0) {
    $result = mysqli_query($conn, "SELECT d.*, u.nama_lengkap, d.update_doc AS doc_updated_at FROM documents d LEFT JOIN users u ON d.div_penerima = u.user_id WHERE 1=0");
} else {
    $idsList = implode(',', $documentIds);
    $query = "
        SELECT d.*, u.nama_lengkap, d.update_doc AS doc_updated_at
        FROM documents d
        LEFT JOIN users u ON d.div_penerima = u.user_id
        WHERE d.document_id IN ($idsList)
        ORDER BY d.created_at DESC
        LIMIT " . (int)$limit . " OFFSET " . (int)$offset . "
    ";
    $result = mysqli_query($conn, $query);
}

$status_query = "SELECT DISTINCT status FROM documents ORDER BY status";
$jenis_query = "SELECT DISTINCT jenis_dokumen FROM documents ORDER BY jenis_dokumen";
$status_result = mysqli_query($conn, $status_query);
$jenis_result = mysqli_query($conn, $jenis_query);
?>
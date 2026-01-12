<?php
// require_once 'config.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';

$user_id = $_SESSION['user_id'];

$query = "SELECT d.*, d.update_doc as doc_updated_at, dv.nama_divisi, u.* 
          FROM documents d 
          LEFT JOIN divisions dv ON d.current_divisi_id = dv.division_id 
          LEFT JOIN users u ON d.penerima = u.user_id
          LEFT JOIN document_flows df ON d.document_id = df.document_id
          WHERE (d.pengirim = '$user_id' OR d.penerima = '$user_id' OR df.sent_by = '$user_id' OR df.received_by = '$user_id')";

if (!empty($search)) {
    $query .= " AND (d.judul LIKE '%$search%' 
              OR d.document_id LIKE '%$search%' 
              OR d.deskripsi LIKE '%$search%')";
}

if (!empty($status) && $status != 'semua') {
    $query .= " AND d.status = '$status'";
}

if (!empty($jenis) && $jenis != 'semua') {
    $query .= " AND d.jenis_dokumen = '$jenis'";
}

$query .= "GROUP BY d.document_id ORDER BY d.created_at DESC";

$result = mysqli_query($conn, $query);

$status_query = "SELECT DISTINCT status FROM documents ORDER BY status";
$jenis_query = "SELECT DISTINCT jenis_dokumen FROM documents ORDER BY jenis_dokumen";
$status_result = mysqli_query($conn, $status_query);
$jenis_result = mysqli_query($conn, $jenis_query);
?>
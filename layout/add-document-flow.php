<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../');
    exit;
}

$document_id = isset($_POST['document_id']) ? trim($_POST['document_id']) : '';
$from_divisi_id = isset($_POST['from_divisi_id']) ? trim($_POST['from_divisi_id']) : '';
$to_divisi_id = isset($_POST['to_divisi_id']) ? trim($_POST['to_divisi_id']) : '';
$sent_by = isset($_POST['sent_by']) ? trim($_POST['sent_by']) : '';
$received_by = isset($_POST['received_by']) ? trim($_POST['received_by']) : null;
$catatan = isset($_POST['catatan']) ? trim($_POST['catatan']) : null;
$status = isset($_POST['status']) ? trim($_POST['status']) : 'Dikirim';

if (empty($document_id) || empty($from_divisi_id) || empty($to_divisi_id) || empty($sent_by)) {
    $_SESSION['error'] = 'Data tidak lengkap!';
    header('Location: ../?view=document-detail&id=' . $document_id);
    exit;
}

$flow_id = 'FLW-' . date('Ymd') . '-' . substr(uniqid(), -6);

if (empty($received_by)) {
    $received_by = null;
}

$query = "INSERT INTO document_flows 
          (flow_id, document_id, from_divisi_id, to_divisi_id, sent_by, received_by, catatan, status, created_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssss", $flow_id, $document_id, $from_divisi_id, $to_divisi_id, $sent_by, $received_by, $catatan, $status);

if ($stmt->execute()) {
    $update_query = "UPDATE documents 
                    SET pengirim = ?, penerima = ?,
                    current_divisi_id = ?, status = ?, update_doc = NOW() 
                    WHERE document_id = ?";
    
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("sssss", $sent_by, $received_by, $to_divisi_id, $status, $document_id);
    $stmt_update->execute();
    $stmt_update->close();
    
    $_SESSION['success'] = 'Riwayat perjalanan berhasil ditambahkan!';
} else {
    $_SESSION['error'] = 'Gagal menambahkan riwayat perjalanan: ' . $stmt->error;
}

$stmt->close();
$conn->close();

header('Location: ../?view=document-detail&id=' . $document_id);
exit;
?>
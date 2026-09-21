<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$document_id   = $_POST['document_id'] ?? '';
$sent_by       = $_POST['sent_by'] ?? '';
$received_by   = $_POST['received_by'] ?? null;
$name_received = $_POST['nama_penerima'] ?? null;
$name_sender   = $_POST['nama_pengirim'] ?? null;
$catatan       = $_POST['catatan'] ?? null;
$status        = $_POST['aksi'] ?? 'Dikirim';
$tanggal       = $_POST['tanggal'] ?? null;

if (!$document_id || !$status || !$sent_by) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$flow_id = 'FLW-' . date('Ymd') . '-' . substr(uniqid(), -6);

$query = "INSERT INTO document_flows 
          (flow_id, document_id, sent_by, received_by, catatan, tanggal, aksi) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssssss", $flow_id, $document_id, $sent_by, $received_by, $catatan, $tanggal, $status);

if (!$stmt->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menambahkan riwayat perjalanan: ' . $stmt->error
    ]);
    exit;
}

$stmt->close();

/* Update document */
$update_query = "UPDATE documents 
                 SET div_pengirim = ?, div_penerima = ?, nama_pengirim = ?, nama_penerima = ?, 
                     status = ?, tanggal_kirim = ?, update_doc = NOW()
                 WHERE document_id = ?";

$stmt_update = $conn->prepare($update_query);
$stmt_update->bind_param("sssssss",
    $sent_by, $received_by, $name_sender, $name_received, $status, $tanggal, $document_id
);
$stmt_update->execute();
$stmt_update->close();

$conn->close();

echo json_encode([
    'success' => true,
    'message' => 'Riwayat perjalanan berhasil ditambahkan!'
]);
exit;
?>
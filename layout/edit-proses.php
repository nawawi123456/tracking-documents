<?php
include 'config.php';
session_start();

// CSRF check
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('Token CSRF tidak valid.');
}

if (!isset($_POST['document_id'])) {
    die("Akses ditolak.");
}

$document_id = mysqli_real_escape_string($conn, $_POST['document_id']);

$cek = $conn->prepare("SELECT document_id FROM documents WHERE document_id = ?");
$cek->bind_param('s', $document_id);
$cek->execute();
$cekRes = $cek->get_result();
if ($cekRes->num_rows == 0) {
    die("Dokumen tidak ditemukan.");
}

$nomor_dokumen   = isset($_POST['nomor_dokumen']) ? trim($_POST['nomor_dokumen']) : '';
$judul           = isset($_POST['judul']) ? trim($_POST['judul']) : '';
$deskripsi       = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
$jenis_dokumen   = isset($_POST['jenis_dokumen']) ? trim($_POST['jenis_dokumen']) : '';
$prioritas       = isset($_POST['prioritas']) ? trim($_POST['prioritas']) : '';
$tanggalkirim    = isset($_POST['tanggal_kirim']) ? trim($_POST['tanggal_kirim']) : null;

$updateStmt = $conn->prepare("UPDATE documents SET
        nomor_dokumen = ?,
        judul = ?,
        deskripsi = ?,
        jenis_dokumen = ?,
        prioritas = ?,
        tanggal_kirim = ?,
        update_doc = NOW()
    WHERE document_id = ?");

if (!$updateStmt) {
    die('Prepare failed: ' . $conn->error);
}

$updateStmt->bind_param('sssssss', $nomor_dokumen, $judul, $deskripsi, $jenis_dokumen, $prioritas, $tanggalkirim, $document_id);
if ($updateStmt->execute()) {
    header("Location: ../?view=document-detail&id=" . urlencode($document_id));
    exit;
} else {
    echo "Gagal mengupdate dokumen: " . $updateStmt->error;
}

?>

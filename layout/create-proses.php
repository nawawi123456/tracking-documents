<?PHP
session_start();
require_once 'config.php';

// CSRF verification
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('Token CSRF tidak valid.');
}

$document_id = "DOC-" . date('Ymd') . "-" . strtoupper(substr(uniqid(), -2));

// sanitize inputs
$nomor_dokumen = isset($_POST['nomor_dokumen']) ? trim($_POST['nomor_dokumen']) : '';
$judul         = isset($_POST['judul']) ? trim($_POST['judul']) : '';
$deskripsi     = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
$jenis         = isset($_POST['jenis_dokumen']) ? trim($_POST['jenis_dokumen']) : '';
$prioritas     = isset($_POST['prioritas']) ? trim($_POST['prioritas']) : '';
$divpengirim   = isset($_POST['pengirim']) ? trim($_POST['pengirim']) : '';
$divpenerima   = isset($_POST['penerima']) ? trim($_POST['penerima']) : '';
$namapengirim  = isset($_POST['nama_pengirim']) ? trim($_POST['nama_pengirim']) : '';
$namapenerima  = isset($_POST['nama_penerima']) ? trim($_POST['nama_penerima']) : '';
$tanggalkirim  = isset($_POST['tanggal_kirim']) ? trim($_POST['tanggal_kirim']) : null;
$created_by    = $_SESSION['user_id'];

$flow_id = 'FLW-' . date('Ymd') . '-' . substr(uniqid(), -6);

// begin transaction
mysqli_begin_transaction($conn);

// insert document using prepared statement
$docStmt = $conn->prepare("INSERT INTO documents (
    document_id, nomor_dokumen, judul, deskripsi,
    jenis_dokumen, prioritas, div_pengirim, nama_pengirim, div_penerima, nama_penerima, tanggal_kirim, status, created_by, update_doc
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Draft', ?, NOW())");
if (!$docStmt) {
    mysqli_rollback($conn);
    die('Prepare failed: ' . $conn->error);
}
$docStmt->bind_param('ssssssssssss', $document_id, $nomor_dokumen, $judul, $deskripsi, $jenis, $prioritas, $divpengirim, $namapengirim, $divpenerima, $namapenerima, $tanggalkirim, $created_by);
if (!$docStmt->execute()) {
    mysqli_rollback($conn);
    die('Gagal menyimpan dokumen: ' . $docStmt->error);
}

// insert flow
$flowStmt = $conn->prepare("INSERT INTO document_flows (
    flow_id, document_id, sent_by, received_by, catatan, tanggal, created_at, aksi
) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Dibuat')");
if (!$flowStmt) {
    mysqli_rollback($conn);
    die('Prepare failed: ' . $conn->error);
}
$catatan = 'new document';
$flowStmt->bind_param('ssssss', $flow_id, $document_id, $divpengirim, $divpenerima, $catatan, $tanggalkirim);
if (!$flowStmt->execute()) {
    mysqli_rollback($conn);
    die('Gagal menyimpan flow: ' . $flowStmt->error);
}

mysqli_commit($conn);

header("Location: ../?view=document-detail&id=" . urlencode($document_id));
exit;

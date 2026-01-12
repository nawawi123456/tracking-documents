<?php
require 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil dokumen
$q = mysqli_query($conn, "SELECT * FROM documents WHERE document_id='$id'");
$doc = mysqli_fetch_assoc($q);

if (!$doc) {
    die("Dokumen tidak ditemukan.");
}

// if ($doc['created_by'] !== $_SESSION['user_id']) {
//     die("Akses ditolak.");
// }

mysqli_query($conn, "DELETE FROM document_flows WHERE document_id='$id'");
mysqli_query($conn, "DELETE FROM documents WHERE document_id='$id'");

header("Location: ../?view=home");
exit;
?>

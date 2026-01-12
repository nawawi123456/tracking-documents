<?php
include 'config.php';

if (!isset($_POST['document_id'])) {
    die("Akses ditolak.");
}

$document_id = mysqli_real_escape_string($conn, $_POST['document_id']);

$cek = mysqli_query($conn, "SELECT * FROM documents WHERE document_id = '$document_id'");
if (mysqli_num_rows($cek) == 0) {
    die("Dokumen tidak ditemukan.");
}

$nomor_dokumen   = mysqli_real_escape_string($conn, $_POST['nomor_dokumen']);
$kode_barcode    = mysqli_real_escape_string($conn, $_POST['kode_barcode']);
$judul           = mysqli_real_escape_string($conn, $_POST['judul']);
$deskripsi       = mysqli_real_escape_string($conn, $_POST['deskripsi']);
$jenis_dokumen   = mysqli_real_escape_string($conn, $_POST['jenis_dokumen']);
$prioritas       = mysqli_real_escape_string($conn, $_POST['prioritas']);
// $pengirim        = mysqli_real_escape_string($conn, $_POST['pengirim']);
// $penerima        = mysqli_real_escape_string($conn, $_POST['penerima']);
// $lokasi          = mysqli_real_escape_string($conn, $_POST['lokasi']);
$tanggal_tenggat = mysqli_real_escape_string($conn, $_POST['tanggal_tenggat']);

$query = "
    UPDATE documents SET
        nomor_dokumen     = '$nomor_dokumen',
        kode_barcode      = '$kode_barcode',
        judul             = '$judul',
        deskripsi         = '$deskripsi',
        jenis_dokumen     = '$jenis_dokumen',
        prioritas         = '$prioritas',
        tanggal_tenggat   = '$tanggal_tenggat',
        update_doc        = NOW()
    WHERE document_id = '$document_id'
";

if (mysqli_query($conn, $query)) {
    header("Location: ../?view=document-detail&id=" . $document_id);
    exit;
} else {
    echo "Gagal mengupdate dokumen: " . mysqli_error($conn);
}
?>

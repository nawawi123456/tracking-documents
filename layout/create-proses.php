<?PHP
session_start();
require_once 'config.php';

$document_id = "DOC-" . date('Ymd') . "-" . strtoupper(substr(uniqid(), -2));

$nomor_dokumen = $_POST['nomor_dokumen'];
$kode_barcode  = $_POST['kode_barcode'];
$judul         = $_POST['judul'];
$deskripsi     = $_POST['deskripsi'];
$jenis         = $_POST['jenis_dokumen'];
$prioritas     = $_POST['prioritas'];
$pengirim      = $_POST['pengirim'];
$penerima      = $_POST['penerima'];
$tenggat       = $_POST['tanggal_tenggat'];
$created_by    = $_SESSION['user_id'];

$rdiv = mysqli_query($conn, "SELECT divisi_id FROM users WHERE user_id = '$penerima'");
$receiver = mysqli_fetch_assoc($rdiv);
$to_divisi = $receiver ? $receiver['divisi_id'] : $_SESSION['divisi_id'];

$sdiv = mysqli_query($conn, "SELECT divisi_id FROM users WHERE user_id = '$pengirim'");
$sender = mysqli_fetch_assoc($sdiv);
$from_divisi = $sender ? $sender['divisi_id'] : $_SESSION['divisi_id'];

$flow_id = 'FLW-' . date('Ymd') . '-' . substr(uniqid(), -6);

$query = "
INSERT INTO documents (
    document_id, nomor_dokumen, kode_barcode, judul, deskripsi,
    jenis_dokumen, prioritas, pengirim, penerima, tanggal_tenggat, current_divisi_id, status,
    created_by, update_doc
)
VALUES (
    '$document_id', '$nomor_dokumen', '$kode_barcode', '$judul', '$deskripsi',
    '$jenis', '$prioritas', '$pengirim', '$penerima', '$tenggat', '$to_divisi', 'Draft',
    '$created_by', NOW()
)
";

$query2 = "
INSERT INTO document_flows (
    flow_id, document_id, from_divisi_id, to_divisi_id, sent_by, received_by, catatan,
    created_at, status
)
VALUES (
    '$flow_id', '$document_id', '$from_divisi', '$to_divisi', '$pengirim',
    '$penerima', 'new document', NOW(), 'Draft'
)
";

if (mysqli_query($conn, $query) && (mysqli_query($conn,$query2)) ) {
    header("Location: ../?view=document-detail&id=$document_id");
    exit;
} else {
    echo "Terjadi kesalahan: " . mysqli_error($conn);
}

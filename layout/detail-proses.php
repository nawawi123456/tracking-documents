<?php
if (!isset($_GET['id'])) {
    die('Dokumen tidak ditemukan');
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$query = "
    SELECT 
        d.nomor_dokumen AS nomor_documen,
        d.*, u.*,
        u.nama_lengkap AS receive,
        u_pengirim.nama_lengkap AS sender
    FROM documents d
    LEFT JOIN users u ON d.div_penerima = u.user_id
    LEFT JOIN document_flows df ON d.document_id = df.document_id
    LEFT JOIN users u_pengirim ON d.div_pengirim = u_pengirim.user_id
    WHERE d.document_id = '$id'
    ORDER BY df.created_at DESC
    LIMIT 1
";

$result = mysqli_query($conn, $query);
$doc = mysqli_fetch_assoc($result);

if (!$doc) {
    die('Data tidak ditemukan');
}

?>
<?php
if (!isset($_GET['id'])) {
    die('Dokumen tidak ditemukan');
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$query = "
    SELECT 
        d.document_id AS document_id,
        d.*,
        u.nama_lengkap AS receive,
        dv.nama_divisi,
        u_pengirim.nama_lengkap AS sender,
        dv_tujuan.nama_divisi AS divisi_tujuan
    FROM documents d
    LEFT JOIN users u ON d.penerima = u.user_id
    LEFT JOIN divisions dv ON u.divisi_id = dv.division_id
    LEFT JOIN document_flows df ON d.document_id = df.document_id
    LEFT JOIN users u_pengirim ON d.pengirim = u_pengirim.user_id
    LEFT JOIN divisions dv_tujuan ON df.to_divisi_id = dv_tujuan.division_id
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
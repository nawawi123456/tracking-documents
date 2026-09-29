<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/r2-cloudflare.php';

// CEK LOGIN
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Anda belum login.');
}

$userId = $_SESSION['user_id'];

// AMBIL DOCUMENT ID
$documentId = trim($_GET['id'] ?? '');

if ($documentId === '') {
    http_response_code(400);
    exit('Document ID tidak ditemukan.');
}

// AMBIL DATA DOKUMEN
$sql = "
    SELECT
        document_id,
        created_by,
        file_key,
        file_name_original,
        file_size,
        file_type
    FROM documents
    WHERE document_id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    exit('Database error.');
}

$stmt->bind_param('s', $documentId);
$stmt->execute();

$result = $stmt->get_result();
$document = $result->fetch_assoc();

$stmt->close();

if (!$document) {
    http_response_code(404);
    exit('Dokumen tidak ditemukan.');
}

if (empty($document['file_key'])) {
    http_response_code(404);
    exit('Dokumen ini belum memiliki file.');
}

// CEK HAK AKSES
$bolehDownload = false;

// Jika user adalah ADMIN
if ((string)$userId === 'USER-999') {
    $bolehDownload = true;
}

// Jika user adalah pembuat dokumen
if ((string)$document['created_by'] === (string)$userId) {
    $bolehDownload = true;
}

// Jika bukan pembuat, cek document_flows
if (!$bolehDownload) {

    $sqlFlow = "
        SELECT flow_id
        FROM document_flows
        WHERE document_id = ?
        AND (
            sent_by = ?
            OR received_by = ?
        )
        LIMIT 1
    ";

    $stmtFlow = $conn->prepare($sqlFlow);

    if ($stmtFlow) {

        $stmtFlow->bind_param(
            'sss',
            $documentId,
            $userId,
            $userId
        );

        $stmtFlow->execute();

        $resultFlow = $stmtFlow->get_result();

        if ($resultFlow->num_rows > 0) {
            $bolehDownload = true;
        }

        $stmtFlow->close();
    }
}

// Jika tidak memiliki hak akses
if (!$bolehDownload) {
    http_response_code(403);
    exit('Anda tidak memiliki izin untuk mengunduh dokumen ini.');
}

// BUAT PRESIGNED URL R2
try {

    $command = $r2->getCommand(
        'GetObject',
        [
            'Bucket' => $r2Bucket,
            'Key' => $document['file_key'],

            'ResponseContentDisposition' =>
                'attachment; filename="' .
                addslashes($document['file_name_original']) .
                '"',

            'ResponseContentType' =>
                $document['file_type']
                    ?: 'application/octet-stream'
        ]
    );

    $presignedRequest = $r2->createPresignedRequest(
        $command,
        '+10 minutes'
    );

    $downloadUrl = (string)$presignedRequest->getUri();

    // REDIRECT KE CLOUDFLARE R2
    header('Location: ' . $downloadUrl);

    exit;

} catch (Throwable $e) {

    error_log(
        'R2 Download Error: ' .
        $e->getMessage()
    );

    http_response_code(500);
    exit('Gagal membuat link download.');
}
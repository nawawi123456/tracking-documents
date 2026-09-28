```php
<?php

// ============================================================
// DEBUG MODE
// ============================================================

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Menghindari output HTML yang merusak redirect
ob_start();

header('Content-Type: text/plain; charset=UTF-8');

function debugLog($label, $value = null)
{
    echo "\n========== " . $label . " ==========\n";

    if ($value !== null) {
        if (is_array($value) || is_object($value)) {
            print_r($value);
        } else {
            var_dump($value);
        }
    }

    echo "\n";
    flush();
}


// ============================================================
// ERROR HANDLER
// ============================================================

set_error_handler(function ($severity, $message, $file, $line) {

    if (!(error_reporting() & $severity)) {
        return false;
    }

    debugLog("PHP ERROR", [
        'severity' => $severity,
        'message'  => $message,
        'file'     => $file,
        'line'     => $line
    ]);

    return false;
});


// ============================================================
// EXCEPTION HANDLER
// ============================================================

set_exception_handler(function ($e) {

    http_response_code(500);

    debugLog("UNCAUGHT EXCEPTION", [
        'class'   => get_class($e),
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'trace'   => $e->getTraceAsString()
    ]);

    exit;
});


// ============================================================
// DEBUG PHP
// ============================================================

debugLog("PHP INFORMATION", [
    'PHP_VERSION' => PHP_VERSION,
    'PHP_SAPI' => PHP_SAPI,
    'OS' => PHP_OS,
    'TIME' => date('Y-m-d H:i:s'),
    'MEMORY_USAGE' => memory_get_usage(true),
    'MEMORY_LIMIT' => ini_get('memory_limit'),
    'MAX_EXECUTION_TIME' => ini_get('max_execution_time'),
    'DISPLAY_ERRORS' => ini_get('display_errors'),
    'ERROR_REPORTING' => error_reporting()
]);


// ============================================================
// CEK EXTENSION
// ============================================================

$extensions = [
    'curl',
    'json',
    'openssl',
    'mbstring',
    'mysqli',
    'fileinfo'
];

$extensionStatus = [];

foreach ($extensions as $ext) {
    $extensionStatus[$ext] = extension_loaded($ext);
}

debugLog("PHP EXTENSIONS", $extensionStatus);


// ============================================================
// CEK FILE KONFIGURASI
// ============================================================

$configPath = __DIR__ . '/config.php';
$r2Path = __DIR__ . '/r2-cloudflare.php';

debugLog("FILE CONFIGURATION", [
    'config.php' => file_exists($configPath),
    'config.php path' => $configPath,
    'r2-cloudflare.php' => file_exists($r2Path),
    'r2-cloudflare.php path' => $r2Path
]);


// ============================================================
// LOAD CONFIG
// ============================================================

try {

    require_once $configPath;

    debugLog("CONFIG LOADED", "config.php berhasil dimuat");

} catch (Throwable $e) {

    debugLog("CONFIG ERROR", [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);

    exit;
}


// ============================================================
// CEK SESSION
// ============================================================

debugLog("SESSION STATUS", [
    'session_status' => session_status(),
    'session_id_exists' => session_id() !== '',
    'user_id_exists' => isset($_SESSION['user_id']),
    'user_id_type' => isset($_SESSION['user_id'])
        ? gettype($_SESSION['user_id'])
        : null
]);

if (empty($_SESSION['user_id'])) {

    http_response_code(401);

    debugLog("LOGIN ERROR", "Anda belum login.");

    exit;
}

$userId = $_SESSION['user_id'];


// ============================================================
// CEK DATABASE
// ============================================================

debugLog("DATABASE CONNECTION", [
    'conn_exists' => isset($conn),
    'conn_type' => isset($conn) ? get_class($conn) : null
]);

if (!isset($conn) || !($conn instanceof mysqli)) {

    http_response_code(500);

    debugLog("DATABASE ERROR", "Koneksi MySQLi tidak tersedia.");

    exit;
}

if ($conn->connect_errno) {

    http_response_code(500);

    debugLog("DATABASE CONNECTION ERROR", [
        'errno' => $conn->connect_errno,
        'error' => $conn->connect_error
    ]);

    exit;
}

debugLog("DATABASE CONNECTED", "Koneksi database berhasil");


// ============================================================
// AMBIL DOCUMENT ID
// ============================================================

$documentId = trim($_GET['id'] ?? '');

debugLog("DOCUMENT ID", $documentId);

if ($documentId === '') {

    http_response_code(400);

    debugLog("DOCUMENT ERROR", "Document ID tidak ditemukan.");

    exit;
}


// ============================================================
// AMBIL DATA DOKUMEN
// ============================================================

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

debugLog("SQL QUERY", $sql);

$stmt = $conn->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    debugLog("SQL PREPARE ERROR", [
        'errno' => $conn->errno,
        'error' => $conn->error
    ]);

    exit;
}

$stmt->bind_param('s', $documentId);

if (!$stmt->execute()) {

    http_response_code(500);

    debugLog("SQL EXECUTE ERROR", [
        'errno' => $stmt->errno,
        'error' => $stmt->error
    ]);

    exit;
}

$result = $stmt->get_result();

$document = $result->fetch_assoc();

$stmt->close();

debugLog("DOCUMENT DATA", $document);


// ============================================================
// CEK DOKUMEN
// ============================================================

if (!$document) {

    http_response_code(404);

    debugLog("DOCUMENT ERROR", "Dokumen tidak ditemukan.");

    exit;
}


// ============================================================
// CEK FILE
// ============================================================

debugLog("FILE INFORMATION", [
    'file_key_exists' => !empty($document['file_key']),
    'file_name_original' => $document['file_name_original'],
    'file_size' => $document['file_size'],
    'file_type' => $document['file_type']
]);

if (empty($document['file_key'])) {

    http_response_code(404);

    debugLog("FILE ERROR", "Dokumen belum memiliki file.");

    exit;
}


// ============================================================
// CEK HAK AKSES
// ============================================================

$bolehDownload = false;

debugLog("ACCESS CHECK", [
    'created_by' => $document['created_by'],
    'created_by_type' => gettype($document['created_by']),
    'user_id' => $userId,
    'user_id_type' => gettype($userId)
]);

if ((string) $document['created_by'] === (string) $userId) {

    $bolehDownload = true;
}

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

    if (!$stmtFlow) {

        http_response_code(500);

        debugLog("FLOW SQL ERROR", [
            'errno' => $conn->errno,
            'error' => $conn->error
        ]);

        exit;
    }

    $stmtFlow->bind_param(
        'sss',
        $documentId,
        $userId,
        $userId
    );

    if (!$stmtFlow->execute()) {

        http_response_code(500);

        debugLog("FLOW EXECUTE ERROR", [
            'errno' => $stmtFlow->errno,
            'error' => $stmtFlow->error
        ]);

        exit;
    }

    $resultFlow = $stmtFlow->get_result();

    if ($resultFlow->num_rows > 0) {
        $bolehDownload = true;
    }

    $stmtFlow->close();
}

debugLog("ACCESS RESULT", $bolehDownload ? "DIIZINKAN" : "DITOLAK");

if (!$bolehDownload) {

    http_response_code(403);

    debugLog("ACCESS ERROR", "Tidak memiliki izin download.");

    exit;
}


// ============================================================
// LOAD CLOUDFLARE R2
// ============================================================

try {

    require_once $r2Path;

    debugLog("R2 CONFIG LOADED", "r2-cloudflare.php berhasil dimuat");

} catch (Throwable $e) {

    http_response_code(500);

    debugLog("R2 CONFIG ERROR", [
        'class' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);

    exit;
}


// ============================================================
// CEK R2 CONFIGURATION
// ============================================================

debugLog("R2 CONFIGURATION", [
    'r2_exists' => isset($r2),
    'r2_type' => isset($r2) ? get_class($r2) : null,
    'bucket_exists' => isset($r2Bucket),
    'bucket_name' => isset($r2Bucket) ? $r2Bucket : null
]);

if (!isset($r2) || !is_object($r2)) {

    http_response_code(500);

    debugLog("R2 ERROR", "Objek R2 tidak tersedia.");

    exit;
}

if (empty($r2Bucket)) {

    http_response_code(500);

    debugLog("R2 ERROR", "Nama bucket tidak tersedia.");

    exit;
}


// ============================================================
// BUAT PRESIGNED URL
// ============================================================

try {

    debugLog("R2 STEP 1", "Membuat GetObject command");

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

    debugLog("R2 STEP 2", "GetObject command berhasil");

    $presignedRequest = $r2->createPresignedRequest(
        $command,
        '+10 minutes'
    );

    debugLog("R2 STEP 3", "Presigned URL berhasil dibuat");

    $downloadUrl = (string) $presignedRequest->getUri();

    debugLog("R2 STEP 4", [
        'url_created' => !empty($downloadUrl),
        'url_length' => strlen($downloadUrl)
    ]);

    if (empty($downloadUrl)) {

        throw new RuntimeException("Presigned URL kosong.");
    }

    debugLog("SUCCESS", "Presigned URL berhasil dibuat.");

    /*
     * Untuk debugging, URL tidak ditampilkan karena
     * mengandung tanda tangan akses sementara.
     */

    echo "\nDOWNLOAD URL BERHASIL DIBUAT.\n";
    echo "Proses redirect dinonaktifkan sementara untuk debugging.\n";

    exit;

} catch (Throwable $e) {

    http_response_code(500);

    debugLog("R2 DOWNLOAD ERROR", [
        'class' => get_class($e),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);

    exit;
}
```

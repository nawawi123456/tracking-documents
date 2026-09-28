<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/r2-cloudflare.php';

header(
    'Content-Type: application/json; charset=utf-8'
);

try {

    // ============================================================
    // 1. CEK LOGIN
    // ============================================================

    if (empty($_SESSION['user_id'])) {
        throw new Exception('Anda belum login.');
    }

    $userId = $_SESSION['user_id'];


    // ============================================================
    // 2. CEK DOCUMENT ID
    // ============================================================

    $documentId = trim($_POST['document_id'] ?? '');

    if ($documentId === '') {
        throw new Exception('Document ID tidak ditemukan.');
    }


    // ============================================================
    // 3. CEK FILE
    // ============================================================

    if (!isset($_FILES['file'])) {
        throw new Exception('File belum dipilih.');
    }

    $file = $_FILES['file'];


    // ============================================================
    // 4. CEK ERROR UPLOAD
    // ============================================================

    if ($file['error'] !== UPLOAD_ERR_OK) {

        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi batas upload server.',
            UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi batas form.',
            UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian.',
            UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang dipilih.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file.',
            UPLOAD_ERR_EXTENSION  => 'Upload dihentikan oleh ekstensi PHP.'
        ];

        $message = $uploadErrors[$file['error']]
            ?? 'Upload file gagal.';

        throw new Exception($message);
    }


    // ============================================================
    // 5. BATAS UKURAN FILE
    // ============================================================

    // Maksimal 10 MB
    $maxFileSize = 10 * 1024 * 1024;

    if ($file['size'] <= 0) {
        throw new Exception('File kosong atau tidak valid.');
    }

    if ($file['size'] > $maxFileSize) {
        throw new Exception('Ukuran file maksimal 10 MB.');
    }


    // ============================================================
    // 6. NAMA FILE ASLI
    // ============================================================

    $originalName = basename($file['name']);

    if ($originalName === '') {
        throw new Exception('Nama file tidak valid.');
    }


    // ============================================================
    // 7. EKSTENSI FILE
    // ============================================================

    $extension = strtolower(
        pathinfo($originalName, PATHINFO_EXTENSION)
    );

    $allowedExtensions = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'jpg',
        'jpeg',
        'png'
    ];

    if (!in_array($extension, $allowedExtensions, true)) {

        throw new Exception(
            'Jenis file tidak diperbolehkan. ' .
            'File yang diperbolehkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG.'
        );
    }


    // ============================================================
    // 8. CEK MIME TYPE BERDASARKAN ISI FILE
    // ============================================================

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    if (!$finfo) {
        throw new Exception(
            'Server tidak dapat memeriksa tipe file.'
        );
    }

    $mimeType = finfo_file(
        $finfo,
        $file['tmp_name']
    );

    finfo_close($finfo);


    $allowedMimeTypes = [

        // PDF
        'pdf' => [
            'application/pdf'
        ],

        // Word
        'doc' => [
            'application/msword'
        ],

        'docx' => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],

        // Excel
        'xls' => [
            'application/vnd.ms-excel'
        ],

        'xlsx' => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ],

        // PowerPoint
        'ppt' => [
            'application/vnd.ms-powerpoint'
        ],

        'pptx' => [
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ],

        // Images
        'jpg' => [
            'image/jpeg'
        ],

        'jpeg' => [
            'image/jpeg'
        ],

        'png' => [
            'image/png'
        ]
    ];


    if (
        !isset($allowedMimeTypes[$extension]) ||
        !in_array(
            $mimeType,
            $allowedMimeTypes[$extension],
            true
        )
    ) {

        throw new Exception(
            'Isi file tidak sesuai dengan ekstensi file.'
        );
    }


    // ============================================================
    // 9. CEK DOKUMEN DI DATABASE
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

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        throw new Exception(
            'Database error: ' . $conn->error
        );
    }

    $stmt->bind_param(
        's',
        $documentId
    );

    $stmt->execute();

    $result = $stmt->get_result();

    $document = $result->fetch_assoc();

    $stmt->close();


    if (!$document) {
        throw new Exception(
            'Dokumen tidak ditemukan.'
        );
    }


    // ============================================================
    // 10. TENTUKAN PEMEGANG DOKUMEN TERAKHIR
    // ============================================================

    /*
     * Ambil received_by dari flow terakhir.
     *
     * received_by = user_id pemegang dokumen.
     *
     * Kalau dokumen belum pernah memiliki flow,
     * pembuat dokumen dianggap sebagai pemegang awal.
     */

    $sqlHolder = "
        SELECT
            received_by
        FROM document_flows
        WHERE document_id = ?
          AND received_by IS NOT NULL
          AND received_by <> ''
        ORDER BY tanggal DESC, created_at DESC
        LIMIT 1
    ";

    $stmtHolder = $conn->prepare($sqlHolder);

    if (!$stmtHolder) {

        throw new Exception(
            'Database error saat mencari pemegang dokumen: ' .
            $conn->error
        );
    }

    $stmtHolder->bind_param(
        's',
        $documentId
    );

    $stmtHolder->execute();

    $resultHolder = $stmtHolder->get_result();

    $flow = $resultHolder->fetch_assoc();

    $stmtHolder->close();


    // Kalau belum pernah ada flow,
    // creator dianggap sebagai pemegang awal.
    $currentHolderId = !empty($flow['received_by'])
        ? $flow['received_by']
        : $document['created_by'];


    // ============================================================
    // 11. CEK HAK UPLOAD
    // ============================================================

    /*
     * Yang boleh upload:
     *
     * 1. Admin USER-999
     * 2. Pembuat dokumen
     * 3. Pemegang dokumen terakhir
     */

    $isAdmin = ($userId === 'USER-999');

    $isCreator = (
        $userId === $document['created_by']
    );

    $isCurrentHolder = (
        $userId === $currentHolderId
    );


    if (
        !$isAdmin &&
        !$isCreator &&
        !$isCurrentHolder
    ) {

        http_response_code(403);

        throw new Exception(
            'Anda tidak memiliki izin untuk mengupload file pada dokumen ini.'
        );
    }


    // ============================================================
    // 12. AMBIL NAMA DIVISI PEMEGANG
    // ============================================================

    /*
     * Berdasarkan struktur aplikasi Anda:
     *
     * document_flows.received_by
     *          ↓
     *       user_id
     *          ↓
     * users.nama_lengkap
     *          ↓
     *       nama divisi
     *
     * Contoh:
     *
     * received_by = SDM-001
     * users.nama_lengkap = SDM
     *
     * Maka file R2:
     *
     * SDM/DOCUMENT-ID/random.pdf
     */

    $sqlDivision = "
        SELECT
            nama_lengkap
        FROM users
        WHERE user_id = ?
        LIMIT 1
    ";

    $stmtDivision = $conn->prepare($sqlDivision);

    if (!$stmtDivision) {

        throw new Exception(
            'Database error saat mengambil data divisi: ' .
            $conn->error
        );
    }

    $stmtDivision->bind_param(
        's',
        $currentHolderId
    );

    $stmtDivision->execute();

    $resultDivision = $stmtDivision->get_result();

    $holder = $resultDivision->fetch_assoc();

    $stmtDivision->close();


    if (!$holder) {

        throw new Exception(
            'Data pemegang dokumen tidak ditemukan.'
        );
    }


    $divisi = trim(
        $holder['nama_lengkap'] ?? ''
    );


    if ($divisi === '') {

        throw new Exception(
            'Nama divisi pemegang dokumen tidak ditemukan.'
        );
    }


    // ============================================================
    // 13. AMANKAN NAMA DIVISI UNTUK R2 OBJECT KEY
    // ============================================================

    /*
     * Contoh:
     *
     * "SDM"               → SDM
     * "Keuangan"          → Keuangan
     * "Umum & Perlengkapan" → Umum-Perlengkapan
     */

    $divisi = preg_replace(
        '/[^a-zA-Z0-9_-]+/',
        '-',
        $divisi
    );

    $divisi = trim(
        $divisi,
        '-_'
    );


    if ($divisi === '') {

        throw new Exception(
            'Nama divisi tidak valid untuk penyimpanan file.'
        );
    }


    // ============================================================
    // 14. BUAT NAMA FILE RANDOM
    // ============================================================

    /*
     * Jangan gunakan nama asli sebagai nama object R2.
     */

    $randomName = bin2hex(
        random_bytes(16)
    );


    // ============================================================
    // 15. BUAT R2 OBJECT KEY
    // ============================================================

    /*
     * Struktur:
     *
     * DIVISI/
     *     DOCUMENT_ID/
     *         RANDOM_FILENAME.ext
     *
     * Contoh:
     *
     * SDM/
     *     DOC-001/
     *         8f3a91c2d4e5.pdf
     *
     * Tidak menggunakan folder "documents".
     */

    $fileKey =
        $divisi .
        '/' .
        $documentId .
        '/' .
        $randomName .
        '.' .
        $extension;


    // ============================================================
    // 16. UPLOAD FILE KE CLOUDFLARE R2
    // ============================================================

    try {

        $r2->putObject([

            'Bucket' => $r2Bucket,

            'Key' => $fileKey,

            'SourceFile' => $file['tmp_name'],

            'ContentType' => $mimeType

        ]);

    } catch (Throwable $e) {

        throw new Exception(
            'Gagal mengupload file ke Cloudflare R2: ' .
            $e->getMessage()
        );
    }


    // ============================================================
    // 17. SIMPAN METADATA KE DATABASE
    // ============================================================

    $sqlUpdate = "
        UPDATE documents
        SET
            file_key = ?,
            file_name_original = ?,
            file_size = ?,
            file_type = ?,
            updated_at = NOW()
        WHERE document_id = ?
    ";

    $stmtUpdate = $conn->prepare(
        $sqlUpdate
    );


    if (!$stmtUpdate) {

        /*
         * Database gagal dipersiapkan.
         * Hapus file baru dari R2.
         */

        try {

            $r2->deleteObject([
                'Bucket' => $r2Bucket,
                'Key'    => $fileKey
            ]);

        } catch (Throwable $ignored) {
        }


        throw new Exception(
            'Gagal mempersiapkan update database: ' .
            $conn->error
        );
    }


    $fileSize = (int) $file['size'];


    $stmtUpdate->bind_param(
        'ssiss',
        $fileKey,
        $originalName,
        $fileSize,
        $mimeType,
        $documentId
    );


    if (!$stmtUpdate->execute()) {

        $errorDatabase = $stmtUpdate->error;

        $stmtUpdate->close();


        /*
         * Database gagal.
         * Hapus file baru dari R2.
         */

        try {

            $r2->deleteObject([
                'Bucket' => $r2Bucket,
                'Key'    => $fileKey
            ]);

        } catch (Throwable $ignored) {
        }


        throw new Exception(
            'Gagal menyimpan informasi file ke database: ' .
            $errorDatabase
        );
    }


    $stmtUpdate->close();


    // ============================================================
    // 18. HAPUS FILE LAMA DARI R2
    // ============================================================

    /*
     * Kita hanya menyimpan 1 file aktif untuk setiap dokumen.
     *
     * File baru sudah berhasil:
     * - masuk R2
     * - masuk database
     *
     * Baru kemudian file lama dihapus.
     */

    $oldFileKey = $document['file_key'] ?? '';


    if (
        !empty($oldFileKey) &&
        $oldFileKey !== $fileKey
    ) {

        try {

            $r2->deleteObject([
                'Bucket' => $r2Bucket,
                'Key'    => $oldFileKey
            ]);

        } catch (Throwable $e) {

            /*
             * Jangan membatalkan upload.
             *
             * File baru sudah benar.
             * Database juga sudah benar.
             *
             * Kalau file lama gagal dihapus,
             * file lama hanya menjadi orphan di R2.
             */
        }
    }


    // ============================================================
    // 19. RESPONSE BERHASIL
    // ============================================================

    echo json_encode([

        'success' => true,

        'message' => 'Dokumen berhasil diupload.',

        'data' => [

            'document_id' => $documentId,

            'file_name' => $originalName,

            'file_size' => $fileSize,

            'file_type' => $mimeType,

            'divisi' => $divisi,

            'file_key' => $fileKey

        ]

    ]);


} catch (Throwable $e) {

    // ============================================================
    // ERROR RESPONSE
    // ============================================================

    if (http_response_code() < 400) {
        http_response_code(400);
    }


    echo json_encode([

        'success' => false,

        'message' => $e->getMessage()

    ]);
}
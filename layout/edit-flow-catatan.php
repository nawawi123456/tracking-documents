<?php
require_once 'config.php'; 

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method tidak diizinkan');
    }

    $flow_id = $_POST['flow_id'] ?? '';
    $catatan = $_POST['catatan'] ?? '';
    $user_id = $_SESSION['user_id'] ?? '';
    
    // Validasi user_id ada di session
    if (empty($user_id)) {
        echo json_encode(['success' => false, 'message' => 'User tidak terautentikasi']);
        exit;
    }
    
    if (empty($flow_id)) {
        echo json_encode(['success' => false, 'message' => 'Flow ID tidak valid']);
        exit;
    }
    
    // Cek apakah flow_id valid dan user adalah sent_by
    $check_query = "SELECT sent_by FROM document_flows WHERE flow_id = ?";
    $stmt_check = $conn->prepare($check_query);
    
    if (!$stmt_check) {
        throw new Exception('Prepare statement gagal: ' . $conn->error);
    }
    
    $stmt_check->bind_param("s", $flow_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Flow tidak ditemukan']);
        exit;
    }
    
    $flow_data = $result_check->fetch_assoc();
    
    // Validasi apakah user adalah sent_by
    if ($flow_data['sent_by'] !== $user_id) {
        echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengubah catatan ini']);
        exit;
    }
    
    // Update catatan
    $update_query = "UPDATE document_flows SET catatan = ? WHERE flow_id = ?";
    $stmt_update = $conn->prepare($update_query);
    
    if (!$stmt_update) {
        throw new Exception('Prepare statement gagal: ' . $conn->error);
    }
    
    $stmt_update->bind_param("ss", $catatan, $flow_id);
    
    if ($stmt_update->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Catatan berhasil diubah',
            'catatan' => $catatan
        ]);
    } else {
        throw new Exception('Execute gagal: ' . $stmt_update->error);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
exit;
?>
<?php
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ?view=login');
    exit;
}

// Ambil data user dari database
$user_id = $_SESSION['user_id'];
$query = "SELECT u.*, d.nama_divisi
          FROM users u
          LEFT JOIN divisions d ON u.divisi_id = d.division_id
          WHERE u.user_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Proses update profile
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $divisi_id = mysqli_real_escape_string($conn, $_POST['divisi_id']);
    
    // Cek apakah username sudah digunakan oleh user lain
    $check_query = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "ss", $username, $user_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = 'Username sudah digunakan oleh user lain!';
    } else {
        $update_query = "UPDATE users SET nama_lengkap = ?, username = ?, divisi_id = ? WHERE user_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssss", $nama_lengkap, $username, $divisi_id, $user_id);
        
        $documents = "UPDATE documents set current_divisi_id = '$divisi_id' where penerima = '$user_id'";
        mysqli_query($conn, $documents);
        $flowdocs = "UPDATE document_flows set to_divisi_id = '$divisi_id' where received_by = '$user_id'";
        mysqli_query($conn, $flowdocs);
        $flowdocs2 = "UPDATE document_flows set from_divisi_id = '$divisi_id' where sent_by = '$user_id'";
        mysqli_query($conn, $flowdocs2);

        if (mysqli_stmt_execute($update_stmt)) {
            $_SESSION['nama_lengkap'] = $nama_lengkap;
            $_SESSION['username'] = $username;
            $_SESSION['divisi_id'] = $divisi_id;
            $success_message = 'Profil berhasil diperbarui!';
            
            // Refresh data user
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
        } else {
            $error_message = 'Gagal memperbarui profil!';
        }
    }
}

// Proses ganti password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek password lama (hash verify)
    if (!password_verify($current_password, $user['password'])) {
        $error_message = 'Password lama tidak sesuai!';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'Konfirmasi password tidak cocok!';
    } elseif (strlen($new_password) < 6) {
        $error_message = 'Password minimal 6 karakter!';
    } else {

        // Hash password baru
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_pass_query = "UPDATE users SET password = ? WHERE user_id = ?";
        $update_pass_stmt = mysqli_prepare($conn, $update_pass_query);
        mysqli_stmt_bind_param($update_pass_stmt, "ss", $hashed_new_password, $user_id);

        if (mysqli_stmt_execute($update_pass_stmt)) {
            $success_message = 'Password berhasil diubah!';
            $user['password'] = $hashed_new_password; // refresh value
        } else {
            $error_message = 'Gagal mengubah password!';
        }
    }
}
?>
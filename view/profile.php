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
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    
    // Cek apakah username sudah digunakan oleh user lain
    $check_query = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "ss", $username, $user_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = 'Username sudah digunakan oleh user lain!';
    } else {
        $update_query = "UPDATE users SET nama_lengkap = ?, username = ?, divisi_id = ?, jabatan = ? WHERE user_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "sssss", $nama_lengkap, $username, $divisi_id, $jabatan, $user_id);
        
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
            $_SESSION['jabatan'] = $jabatan;
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
            $user['password'] = $hashed_new_password;
        } else {
            $error_message = 'Gagal mengubah password!';
        }
    }
}
?>

<style>
    :root {
        --primary-color: #6f42ff;
        --secondary-color: #9b4dff;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a35cc, #7d3dcc);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(111, 66, 255, 0.4);
    }
    
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .profile-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 2rem;
        color: white;
        text-align: center;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .profile-avatar i {
        font-size: 4rem;
        color: var(--primary-color);
    }
    
    .info-item {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.3s;
    }
    
    .info-item:hover {
        background: #f8f9fa;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        color: #212529;
        font-size: 1rem;
    }
    
    .section-title {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-control:focus, select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 255, 0.25);
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
    
    .badge-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .badge-jabatan {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.35rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 0.5rem;
        display: inline-block;
    }
</style>

<div class="container mt-4">
    <a href="?" class="btn btn-outline-primary mb-3">
        <i class="ri-arrow-left-line me-1"></i> Kembali Ke Beranda
    </a>
</div>

<div class="container pb-5">
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            <?= htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            <?= htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="ri-user-fill"></i>
                    </div>
                    <h4 class="mb-2"><?= htmlspecialchars($user['nama_lengkap']); ?></h4>
                    <span class="badge badge-custom">
                        <i class="ri-building-line me-1"></i><?= htmlspecialchars($user['nama_divisi']); ?>
                    </span>
                    <?php if (!empty($user['jabatan'])): ?>
                    <div class="badge-jabatan">
                        <i class="ri-user-star-line me-1"></i><?= htmlspecialchars($user['jabatan']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-3">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ri-user-line me-1"></i>Username
                        </div>
                        <div class="info-value"><?= htmlspecialchars($user['username']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ri-fingerprint-line me-1"></i>User ID
                        </div>
                        <div class="info-value"><?= htmlspecialchars($user['user_id']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ri-building-2-line me-1"></i>Divisi ID
                        </div>
                        <div class="info-value"><?= htmlspecialchars($user['divisi_id']); ?></div>
                    </div>
                    <?php if (!empty($user['jabatan'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ri-user-star-line me-1"></i>Jabatan
                        </div>
                        <div class="info-value"><?= htmlspecialchars($user['jabatan']); ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ri-calendar-line me-1"></i>Terdaftar Sejak
                        </div>
                        <div class="info-value">
                            <?= date('d F Y', strtotime($user['created_at'])); ?>
                        </div>
                    </div>
                    <?php if (!empty($user['updated_at'])): ?>
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ri-refresh-line me-1"></i>Terakhir Diupdate
                        </div>
                        <div class="info-value">
                            <?= date('d F Y H:i', strtotime($user['updated_at'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Edit Profile Form -->
            <div class="card profile-card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title">
                        <i class="ri-edit-box-line"></i>
                        Edit Profil
                    </h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">
                                <i class="ri-user-fill me-1"></i>Nama Lengkap
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nama_lengkap" 
                                   name="nama_lengkap" 
                                   value="<?= htmlspecialchars($user['nama_lengkap']); ?>" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="ri-at-line me-1"></i>Username
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   value="<?= htmlspecialchars($user['username']); ?>" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">
                                <i class="ri-user-star-line me-1"></i>Jabatan
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="jabatan" 
                                   name="jabatan" 
                                   value="<?= htmlspecialchars($user['jabatan'] ?? ''); ?>" 
                                   placeholder="Contoh: Staff Admin, Kepala Divisi, dll">
                        </div>
                        
                        <?php 
                        $getdivisi = mysqli_query($conn, "SELECT division_id, nama_divisi FROM divisions WHERE division_id != '" . mysqli_real_escape_string($conn, $user['divisi_id']) . "' ORDER BY division_id ASC");
                        $divisi = mysqli_fetch_all($getdivisi, MYSQLI_ASSOC); 
                        ?>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="ri-building-line me-1"></i>Divisi
                            </label>
                            <select name="divisi_id" 
                                    class="form-control"
                                    style="padding:10px;">
                                <option value="<?= htmlspecialchars($user['divisi_id']); ?>">
                                    <?= htmlspecialchars($user['nama_divisi']); ?>
                                </option>
                                <?php foreach ($divisi as $d): ?>
                                    <option value="<?= $d['division_id']; ?>">
                                        <?= htmlspecialchars($d['nama_divisi']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="card profile-card">
                <div class="card-body p-4">
                    <h5 class="section-title">
                        <i class="ri-lock-password-line"></i>
                        Ganti Password
                    </h5>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="ri-lock-line me-1"></i>Password Lama
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="ri-lock-unlock-line me-1"></i>Password Baru
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password" 
                                   name="new_password" 
                                   minlength="6" 
                                   required>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="ri-shield-check-line me-1"></i>Konfirmasi Password Baru
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   minlength="6" 
                                   required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-primary">
                            <i class="ri-key-line me-1"></i>Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
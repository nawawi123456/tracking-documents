<?php include "layout/profile-handler.php";?>

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
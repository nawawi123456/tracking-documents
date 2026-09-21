<div class="login-card d-flex flex-column align-items-center justify-content-center" 
     style="max-width: 380px; margin: 50px auto; padding: 35px 30px; border: 1px solid #e5e5e5; border-radius: 12px; background:#fff; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

    <div class="logo text-center mb-3">
        <div style="font-size: 60px; color:#0d6efd;">
            <i class="ri-mac-fill"></i>
        </div>
        <h3 style="margin-bottom:5px;">ASN DocuFlow</h3>
        <p class="text-muted" style="margin:0;">Selamat Datang, Silahkan Login</p>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show w-100 mb-3" role="alert">
            <?php 
                $error_msg = '';
                switch($_GET['error']) {
                    case '1': $error_msg = 'Username dan password harus diisi'; break;
                    case '2': $error_msg = 'Username atau password salah'; break;
                    default: $error_msg = 'Terjadi kesalahan, silakan coba lagi';
                }
                echo $error_msg;
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="layout/login-proses.php" method="POST" style="width:100%;">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ri-user-line"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ri-lock-2-line"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100" style="padding:10px 0; font-size:16px;">
            <i class="ri-login-box-line me-1"></i> Login
        </button>
    </form>

    <div class="text-center mt-3">
        <p class="text-muted" style="font-size:12px; margin:0;">Sistem Manajemen Dokumen Terintegrasi</p>
    </div>
    <?php //echo password_hash("123456", PASSWORD_DEFAULT); ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

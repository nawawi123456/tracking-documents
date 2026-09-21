<div class="bg-gradient-primary py-4 mb-4 sticky-top" style="background: linear-gradient(135deg, #6f42ff, #9b4dff);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <div class="logo-container bg-purple rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
                         style="width: 60px; height: 60px; padding: 8px;">
                        <img src="images/LOGO ASN BARU.png"
                             alt="Logo ASN" 
                             style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div>
                        <h2 class="text-white mb-1 fw-bold">ASN DocuFlow</h2>
                        <p class="text-white-50 mb-0">Sistem Manajemen Dokumen Terintegrasi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="dropdown d-inline-block">
                    <button class="btn btn-outline-light" 
                            type="button" 
                            id="dropdownUserMenu" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false"
                            style="position: relative;">
                        <i class="ri-user-line me-1"></i> 
                        <?= $_SESSION['nama_lengkap']; ?>
                        <i class="ri-arrow-down-s-line ms-2"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUserMenu">
                        <li>
                            <div class="dropdown-item-text">
                                <div class="fw-semibold"><?= $_SESSION['username']; ?></div>
                                <small class="text-muted"><i class="ri-building-line me-1"></i><?= $_SESSION['nama_lengkap']; ?></small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?view=profile"><i class="ri-user-settings-line me-2"></i>Profil</a></li>
                        <!-- <li><a class="dropdown-item" href="settings.php"><i class="ri-settings-3-line me-2"></i>Pengaturan</a></li> -->
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="layout/logout.php"><i class="ri-logout-box-line me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

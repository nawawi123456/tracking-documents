<?php
$view_mode = isset($_GET['view_mode']) ? $_GET['view_mode'] : 'card';
?>

<div class="mb-3">
    <a href="?view=document-create" class="btn btn-primary me-2">
        <i class="ri-file-add-fill me-1"></i> Tambah Dokumen
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="" class="d-flex flex-wrap gap-2">
            <input type="hidden" name="view_mode" value="<?= $view_mode ?>">
            <div class="input-group" style="flex:1; min-width:250px;">
                <span class="input-group-text bg-light border-1">
                    <i class="ri-search-line text-primary" style="font-size:18px;"></i>
                </span>

                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari dokumen, nomor..."
                    value="<?= htmlspecialchars($search) ?>"
                    class="form-control border-1"
                    style="padding:10px;"
                >
            </div>

            <!-- STATUS FILTER -->
            <div class="input-group" style="max-width:200px; min-width:140px;">
                <span class="input-group-text bg-light border-1">
                    <i class="ri-checkbox-circle-line text-primary"></i>
                </span>

                <select name="status" class="form-select border-1">
                    <option value="semua" <?= empty($status) || $status == 'semua' ? 'selected' : '' ?>>
                        Semua Status
                    </option>

                    <?php while ($row = mysqli_fetch_assoc($status_result)): ?>
                        <option value="<?= $row['status'] ?>" <?= $status == $row['status'] ? 'selected' : '' ?>>
                            <?= ucfirst($row['status']) ?>
                        </option>
                    <?php endwhile; mysqli_data_seek($status_result, 0); ?>
                </select>
            </div>

            <!-- JENIS FILTER -->
            <div class="input-group" style="max-width:200px; min-width:140px;">
                <span class="input-group-text bg-light border-1">
                    <i class="ri-folder-2-line text-primary"></i>
                </span>

                <select name="jenis" class="form-select border-1">
                    <option value="semua" <?= empty($jenis) || $jenis == 'semua' ? 'selected' : '' ?>>
                        Semua Jenis
                    </option>

                    <?php while ($row = mysqli_fetch_assoc($jenis_result)): ?>
                        <option value="<?= $row['jenis_dokumen'] ?>" <?= $jenis == $row['jenis_dokumen'] ? 'selected' : '' ?>>
                            <?= ucfirst($row['jenis_dokumen']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- TOGGLE VIEW MODE - Card View -->
            <a href="?<?= http_build_query(array_merge($_GET, ['view_mode' => 'card'])) ?>"
               class="btn <?= $view_mode == 'card' ? 'btn-primary' : 'btn-outline-secondary' ?> d-flex align-items-center"
               style="padding:10px 14px;"
               title="Mode Card">
                <i class="ri-layout-grid-line"></i>
            </a>

            <!-- TOGGLE VIEW MODE - Table View -->
            <a href="?<?= http_build_query(array_merge($_GET, ['view_mode' => 'table'])) ?>"
               class="btn <?= $view_mode == 'table' ? 'btn-primary' : 'btn-outline-secondary' ?> d-flex align-items-center"
               style="padding:10px 14px;"
               title="Mode Tabel">
                <i class="ri-table-line"></i>
            </a>

            <!-- RESET FILTER -->
            <?php if (!empty($search) || !empty($status) || !empty($jenis)): ?>
                <a href="?view_mode=<?= $view_mode ?>"
                   class="btn btn-outline-secondary d-flex align-items-center"
                   style="padding:10px 14px;"
                   title="Reset Filter">
                    <i class="ri-refresh-line"></i>
                </a>
            <?php endif; ?>

        </form>

    </div>
</div>

<div class="container">

    <?php if ($view_mode == 'card'): ?>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="row row-cols-lg-3 row-cols-md-2 row-cols-1 g-3">
                <?php while ($home = mysqli_fetch_assoc($result)) : ?>
                    <div class="col">
                        <div class="card card-document border-0 shadow-sm h-100">
                            <div class="card-body py-3">
                            <div class="d-flex align-items-center mb-3">
                                <!-- ICON FILE -->
                                <div class="flex-shrink-0">
                                    <div class="avatar-title rounded-4" style="width: 70px; height: 70px; background: linear-gradient(135deg, #6f42ff, #9b4dff);">
                                        <div class="document-icon">
                                            <i class="ri-survey-fill" style="font-size: 40px;"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fw-semibold mb-1" style="line-height: 1.2;">
                                        <?= htmlspecialchars($home['judul']) ?>
                                    </h6>
                                    <small class="text-muted"><?= htmlspecialchars($home['nomor_dokumen']) ?></small>
                                </div>
                                <div class="d-flex flex-column gap-1 text-end ms-2">
                                    <?php
                                    $status_color = 'primary';
                                    if ($home['status'] == 'Selesai') $status_color = 'success';
                                    if ($home['status'] == 'Diarsipkan') $status_color = 'success';
                                    if ($home['status'] == 'Dikembalikan') $status_color = 'danger';
                                    if ($home['status'] == 'Diproses') $status_color = 'warning';
                                    if ($home['status'] == 'Draft') $status_color = 'dark';
                                    ?>
                                    <span class="badge bg-<?= $status_color ?>-subtle text-<?= $status_color ?> badge-border">
                                        <?= ucfirst($home['status']) ?>
                                    </span>
                                    
                                    <?php
                                    $jenis_color = 'dark';
                                    ?>
                                    <span class="badge bg-<?= $jenis_color ?>-subtle text-<?= $jenis_color ?> badge-border">
                                        <?= ucfirst($home['jenis_dokumen']) ?>
                                    </span>
                                    
                                    <?php
                                    $prioritas_color = 'warning';
                                    if ($home['prioritas'] == 'Urgent') $prioritas_color = 'danger';
                                    if ($home['prioritas'] == 'Tinggi') $prioritas_color = 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $prioritas_color ?>-subtle text-<?= $prioritas_color ?> badge-border">
                                        <?= ucfirst($home['prioritas']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between text-muted small mt-2">
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-line me-1 fs-5 text-primary"></i>
                                    <span><?= htmlspecialchars($home['nama_lengkap'] ?? $home['div_penerima']) ?></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-line me-1 fs-5 text-danger"></i>
                                    <span>
                                        <?= !empty($home['tanggal_kirim']) 
                                            ? date('d M Y', strtotime($home['tanggal_kirim'])) 
                                            : 'Tidak ada tenggat' ?>
                                    </span>
                                </div>
                            </div>

                            <a href="?view=document-detail&id=<?= urlencode($home['document_id']) ?>"
                               class="btn btn-primary btn-sm w-100 mt-3 rounded-pill">
                                <i class="ri-share-forward-line"></i>
                                Lihat Detail
                            </a>
                        </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <div class="row g-3">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="ri-file-search-line fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">Tidak ada dokumen ditemukan</h5>
                        <p class="text-muted small">
                            <?php if (!empty($search) || !empty($status) || !empty($jenis)): ?>
                                Coba ubah filter pencarian Anda
                            <?php else: ?>
                                Belum ada dokumen yang tersedia
                            <?php endif; ?>
                        </p>

                        <?php if (!empty($search) || !empty($status) || !empty($jenis)): ?>
                            <a href="?view_mode=<?= $view_mode ?>" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="ri-refresh-line me-1"></i> Reset Filter
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Dokumen</th>
                                <th>Status</th>
                                <th>Jenis</th>
                                <th>Prioritas</th>
                                <th>Penerima</th>
                                <th>Tanggal</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php mysqli_data_seek($result, 0); // Reset pointer ?>
                                <?php while ($home = mysqli_fetch_assoc($result)) : ?>
                                    <?php
                                    // Status color
                                    $status_color = 'primary';
                                    if ($home['status'] == 'Selesai') $status_color = 'success';
                                    if ($home['status'] == 'Diarsipkan') $status_color = 'success';
                                    if ($home['status'] == 'Dikembalikan') $status_color = 'danger';
                                    if ($home['status'] == 'Diproses') $status_color = 'warning';
                                    if ($home['status'] == 'Draft') $status_color = 'dark';
                                    
                                    // Prioritas color
                                    $prioritas_color = 'warning';
                                    if ($home['prioritas'] == 'Rendah') $prioritas_color = 'primary';
                                    if ($home['prioritas'] == 'Urgent') $prioritas_color = 'danger';
                                    if ($home['prioritas'] == 'Tinggi') $prioritas_color = 'secondary';
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0 me-3">
                                                    <div class="avatar-title rounded" style="background: linear-gradient(135deg, #6f42ff, #9b4dff);">
                                                        <i class="ri-survey-fill fs-5"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($home['judul']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($home['nomor_dokumen']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $status_color ?>-subtle text-<?= $status_color ?> badge-border">
                                                <?= ucfirst($home['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark-subtle text-dark badge-border">
                                                <?= ucfirst($home['jenis_dokumen']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $prioritas_color ?>-subtle text-<?= $prioritas_color ?> badge-border">
                                                <?= ucfirst($home['prioritas']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-user-line me-2 text-primary"></i>
                                                <span><?= htmlspecialchars($home['nama_lengkap'] ?? '-') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-calendar-line me-2 text-danger"></i>
                                                <span>
                                                    <?= !empty($home['tanggal_kirim']) 
                                                        ? date('d M Y', strtotime($home['tanggal_kirim'])) 
                                                        : '-' ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center pe-4">
                                            <a href="?view=document-detail&id=<?= urlencode($home['document_id']) ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line me-1"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="ri-file-search-line fs-1 text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted mb-2">Tidak ada dokumen ditemukan</h5>
                                        <p class="text-muted small">
                                            <?php if (!empty($search) || !empty($status) || !empty($jenis)): ?>
                                                Coba ubah filter pencarian Anda
                                            <?php else: ?>
                                                Belum ada dokumen yang tersedia
                                            <?php endif; ?>
                                        </p>
                                        <?php if (!empty($search) || !empty($status) || !empty($jenis)): ?>
                                            <a href="?view_mode=<?= $view_mode ?>" class="btn btn-outline-primary btn-sm mt-2">
                                                <i class="ri-refresh-line me-1"></i> Reset Filter
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>



<?php if (!empty($totalPages) && $totalPages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item <?= ($p == ($page ?? 1)) ? 'active' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterSelects = document.querySelectorAll('select[name="status"], select[name="jenis"]');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && searchInput.value) {
        searchInput.focus();
    }
});
</script>
<div class="container-fluid mt-3">
    <div class="row align-items-center">
        <div class="col-9">
            <a href="?" class="btn btn-outline-primary align-items-center">
                <i class="ri-arrow-left-line me-1"></i> Kembali Ke Beranda
            </a>
        </div>
        <div class="col-3 text-end">
            <div class="p-2 bg-primary border rounded d-inline-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="ri-user-line me-1 border rounded text-light" style="font-size: 20px;"></i>
                </div>
                <div class="flex-grow-1 ms-2">
                    <h6 class="mb-1 text-light"><?= $_SESSION['nama_lengkap']; ?></h6>
                    <p class="mb-0 text-light"><?= $_SESSION['nama_divisi']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="position-relative mt-3" style="overflow: hidden;">
    <div style="height: 300px; background: linear-gradient(135deg, #6f42ff, #9b4dff); border-radius: 0 0 20px 20px; margin-left: -1.5rem; margin-right: -1.5rem;"></div>
</div>

<div style="margin-top: -300px; position: relative; z-index: 100;" class="px-3">
    <div class="container-fluid">
        <div class="row g-4 align-items-end">
            <div class="col-auto">
                <div style="width: 110px; height: 110px; background: linear-gradient(135deg, #6f42ff, #9b4dff); border: 4px solid white; box-shadow: 0 4px 12px rgba(111, 66, 255, 0.3);" class="rounded-4 d-flex align-items-center justify-content-center">
                    <i class="ri-survey-fill" style="font-size: 60px; color: #fff;"></i>
                </div>
            </div>
            
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1"><?= htmlspecialchars($doc['judul']) ?></h3>
                    <p class="text-white text-opacity-75 mb-2"><?= htmlspecialchars($doc['document_id']) ?></p>
                    <div class="d-flex flex-wrap gap-3 text-white-50">
                        <div>
                            <i class="ri-map-pin-user-line me-1 text-white text-opacity-75"></i>
                            <?= htmlspecialchars($doc['receive']) ?> (<?= htmlspecialchars($doc['nama_divisi']) ?>)
                        </div>
                        <div>
                            <i class="ri-calendar-line me-1 text-white text-opacity-75"></i>
                            <?= !empty($doc['updated_at']) ? date('d M Y', strtotime($doc['updated_at'])) : 'Tidak ada tenggat' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-3 mt-4" style="position: relative; z-index: 50;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex flex-wrap gap-2">
            <?php
            $status_color = 'primary';
            if ($doc['status'] == 'Selesai') $status_color = 'success';
            if ($doc['status'] == 'Diarsipkan') $status_color = 'success';
            if ($doc['status'] == 'Dikembalikan') $status_color = 'danger';
            if ($doc['status'] == 'Diproses') $status_color = 'warning';
            if ($doc['status'] == 'Draft') $status_color = 'dark';
            ?>
            <span class="badge bg-<?= $status_color ?>-subtle text-<?= $status_color ?>" style="border: 1px solid currentColor; padding: 6px 12px;">
                <?= ucfirst($doc['status']) ?>
            </span>
            <?php
            $jenis_color = 'dark';
            ?>
            <span class="badge bg-<?= $jenis_color ?>-subtle text-<?= $jenis_color ?>" style="border: 1px solid currentColor; padding: 6px 12px;">
                <?= ucfirst($doc['jenis_dokumen']) ?>
            </span>

            <?php
            $prioritas_color = 'warning';
            // if ($doc['prioritas'] == 'Rendah') $prioritas_color = 'dark-subtle';
            if ($doc['prioritas'] == 'Tinggi') $prioritas_color = 'secondary';
            if ($doc['prioritas'] == 'Urgent') $prioritas_color = 'danger';
            ?>
            <span class="badge bg-<?= $prioritas_color ?>-subtle text-<?= $prioritas_color ?>" style="border: 1px solid currentColor; padding: 6px 12px;">
                <?= ucfirst($doc['prioritas']) ?>
            </span>
        </div>
        
        <?php if ($bolehAkses): ?>
        <div class="d-flex gap-2">
            <a href="?view=document-edit&id=<?= $doc['document_id'] ?>" class="btn btn-secondary btn-sm">
                <i class="ri-ball-pen-line me-1"></i> Edit Dokumen
            </a>
            <a href="layout/delete-proses.php?id=<?= $doc['document_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus dokumen ini?')">
                <i class="ri-delete-bin-line me-1"></i> Hapus
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Content Grid -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="card-title mb-3">Deskripsi Dokumen</h5>
                        <p class="text-muted">
                            <?= nl2br(htmlspecialchars($doc['deskripsi'] ?: 'Tidak ada deskripsi')) ?>
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <h5 class="card-title mb-3">Informasi Detail</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                    <small class="text-muted d-block mb-1">Pengirim</small>
                                    <strong class="text-dark d-flex align-items-center">
                                        <i class="ri-user-line me-2" style="color: #6f42ff;"></i>
                                        <?= htmlspecialchars($doc['sender']) ?>
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                    <small class="text-muted d-block mb-1">Penerima</small>
                                    <strong class="text-dark d-flex align-items-center">
                                        <i class="ri-user-received-line me-2" style="color: #6f42ff;"></i>
                                        <?= !empty($doc['penerima']) ? htmlspecialchars($doc['receive']) : 'Belum ada penerima' ?>
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                    <small class="text-muted d-block mb-1">Lokasi Saat Ini</small>
                                    <strong class="text-dark d-flex align-items-center">
                                        <i class="ri-building-line me-2" style="color: #6f42ff;"></i>
                                        <?= htmlspecialchars($doc['nama_divisi']) ?>
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                    <small class="text-muted d-block mb-1">Tenggat Waktu</small>
                                    <strong class="text-dark d-flex align-items-center">
                                        <i class="ri-calendar-2-line me-2" style="color: #6f42ff;"></i>
                                        <?= !empty($doc['tanggal_tenggat']) ? date('d F Y', strtotime($doc['tanggal_tenggat'])) : 'Tidak ada tenggat' ?>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div><?php if ($bolehAkses): ?>
                        <h5 class="card-title mb-3">Update Status Dokumen</h5>
                        <form method="post" action="layout/document-update-status.php">
                            <input type="hidden" name="id" value="<?= $doc['document_id'] ?>">
                            <!-- <input type="hidden" name="sender" value="<?= $doc['document_id'] ?>"> -->
                            <div class="row align-items-end g-3">
                                <div class="col-md-8">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <?php 
                                        $statuses = ['Draft', 'Diterima', 'Dikembalikan', 'Diproses', 'Diarsipkan', 'Selesai'];
                                        foreach ($statuses as $status): ?>
                                            <option value="<?= $status ?>" <?= strtolower($doc['status']) === strtolower($status) ? 'selected' : '' ?>>
                                                <?= $status ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </form><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">History Document</h5>
                        <?php if ($bolehAkses): ?>
                        <button class="btn btn-sm" style="background-color: #6f42ff; color: white; border: none;" data-bs-toggle="modal" data-bs-target="#addFlowModal">
                            <i class="ri-add-line"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $flow_query = "SELECT df.*, 
                                d_from.nama_divisi as divisi_asal,
                                d_to.nama_divisi as divisi_tujuan,
                                u_sent.nama_lengkap as pengirim,
                                u_received.nama_lengkap as penerima,
                                df.created_at as waktu
                                FROM document_flows df
                                LEFT JOIN divisions d_from ON df.from_divisi_id = d_from.division_id
                                LEFT JOIN divisions d_to ON df.to_divisi_id = d_to.division_id
                                LEFT JOIN users u_sent ON df.sent_by = u_sent.user_id
                                LEFT JOIN users u_received ON df.received_by = u_received.user_id
                                WHERE df.document_id = ?
                                ORDER BY df.created_at ASC";
                    
                    $stmt_flow = $conn->prepare($flow_query);
                    $stmt_flow->bind_param("s", $doc['document_id']);
                    $stmt_flow->execute();
                    $result_flow = $stmt_flow->get_result();
                    $flows = $result_flow->fetch_all(MYSQLI_ASSOC);
                    $total_flows = count($flows);
                    ?>

                    <?php if (empty($flows)): ?>
                        <div class="text-center py-4">
                            <i class="ri-file-list-3-line" style="font-size: 48px; color: #e9ecef;"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada riwayat perjalanan</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($flows as $index => $flow): ?>
                        <!-- Timeline Item -->
                        <div class="mb-3 position-relative">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <?php
                                    // Icon berdasarkan status atau urutan
                                    $icon = 'ri-arrow-right-circle-line';
                                    $bg_color = 'rgba(13, 110, 253, 0.1)';
                                    $text_color = '#0d6efd';
                                    
                                    if ($index == 0) {
                                        $icon = 'ri-send-plane-line';
                                        $bg_color = 'rgba(40, 167, 69, 0.1)';
                                        $text_color = '#28a745';
                                    } elseif ($index == $total_flows - 1) {
                                        $icon = 'ri-check-double-line';
                                        $bg_color = 'rgba(111, 66, 255, 0.1)';
                                        $text_color = '#6f42ff';
                                    }
                                    ?>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: <?= $bg_color ?>; color: <?= $text_color ?>;">
                                        <i class="<?= $icon ?>"></i>
                                    </div>
                                </div>
                                <div class="flex-fill ms-3" style="min-width: 0;">
                                    <h6 class="mb-1" style="font-size: 14px; line-height: 1.5;">
                                        <?= htmlspecialchars($flow['divisi_asal']) ?> <i class="ri-arrow-right-line mx-1"></i> <?= htmlspecialchars($flow['divisi_tujuan']) ?>
                                    </h6>
                                    <p class="text-muted mb-1 small">
                                        <i class="ri-user-line me-1"></i>
                                        <?= htmlspecialchars($flow['pengirim']) ?>
                                        <?php if (!empty($flow['penerima'])): ?>
                                            <i class="ri-arrow-right-line mx-1"></i> <?= htmlspecialchars($flow['penerima']) ?>
                                        <?php endif; ?>
                                    </p>
                                    <?php if (!empty($flow['status'])): ?>
                                    <p class="text-muted mb-1 small">
                                        <i class="ri-flag-line me-1"></i>
                                        <?= htmlspecialchars($flow['status']) ?>
                                    </p>
                                    <?php endif; ?>
                                    <?php if (!empty($flow['catatan'])): ?>
                                    <p class="text-muted mb-1 small">
                                        <i class="ri-chat-3-line me-1"></i>
                                        <?= htmlspecialchars($flow['catatan']) ?>
                                    </p>
                                    <?php endif; ?>
                                    <p class="text-muted mb-0" style="font-size: 11px;">
                                        <?= date('d M Y H:i', strtotime($flow['waktu'])) ?>
                                    </p>
                                </div>
                            </div>
                            <?php if ($index < $total_flows - 1): ?>
                            <div style="position: absolute; left: 15px; top: 35px; bottom: -12px; width: 2px; background-color: #e9ecef;"></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
     id="addFlowModal"
     tabindex="-1"
     aria-labelledby="addFlowModalLabel"
     aria-hidden="true"
     style="z-index:1055;">

    <div class="modal-dialog modal-dialog-centered"
         style="
            transform: translateY(-30px) scale(0.95);
            transition: all 0.3s ease;
         ">

        <div class="modal-content"
             style="
                border-radius: 12px;
                border: none;
                overflow: visible;
                box-shadow:
                    0 25px 60px rgba(0,0,0,0.35),
                    0 0 0 1px rgba(255,255,255,0.05);
             ">

            <div class="modal-header"
                 style="
                    background: linear-gradient(135deg, #6f42ff, #9b4dff);
                    color: white;
                    border-radius: 12px 12px 0 0;
                    box-shadow: 0 6px 18px rgba(0,0,0,0.25);
                 ">
                <h5 class="modal-title" style="color: #fff;" id="addFlowModalLabel">
                    <i class="ri-add-circle-line me-2"></i> Update Status Dokumen
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <form method="POST" action="layout/add-document-flow.php" id="addFlowForm">
                <div class="modal-body">

                    <input type="hidden" name="document_id" value="<?= $doc['document_id'] ?>">
                    <input type="hidden" name="from_divisi_id" value="<?= $_SESSION['divisi_id'] ?>">
                    <input type="hidden" name="sent_by" value="<?= $_SESSION['user_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-flag-line me-1" style="color:#6f42ff;"></i>
                            Status Dokumen
                        </label>
                        <select name="status" class="form-select" required>
                            <option value="">Pilih Status</option>
                            <option value="Dikirim">Dikirim</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Diarsipkan">Diarsipkan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-building-line me-1" style="color:#6f42ff;"></i>
                            Dari Divisi
                        </label>
                        <input type="text"
                               class="form-control"
                               value="<?= htmlspecialchars($doc['nama_divisi']) ?>"
                               readonly
                               style="background-color:#f8f9fa;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-building-2-line me-1" style="color:#6f42ff;"></i>
                            Ke Divisi <span class="text-danger">*</span>
                        </label>
                        <select name="to_divisi_id" id="to_divisi_id" class="form-select" required>
                            <option value="">Pilih Divisi Tujuan</option>
                            <?php
                            $q = "SELECT division_id, nama_divisi FROM divisions ORDER BY nama_divisi ASC";
                            $s = $conn->prepare($q);
                            $s->execute();
                            $r = $s->get_result();
                            while ($d = $r->fetch_assoc()):
                            ?>
                                <option value="<?= $d['division_id'] ?>">
                                    <?= htmlspecialchars($d['nama_divisi']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-user-received-line me-1" style="color:#6f42ff;"></i>
                            Ditangani Oleh
                        </label>
                        <select name="received_by" id="received_by" class="form-select">
                            <option value="">Pilih User</option>
                            <!-- <?php
                            $q = "SELECT u.user_id user_id, u.nama_lengkap nama_lengkap FROM users u join divisions d on u.divisi_id=d.division_id WHERE division_id != ? ORDER BY nama_lengkap ASC";
                            $s = $conn->prepare($q);
                            $s->bind_param("s", $_SESSION['divisi_id']);
                            $s->execute();
                            $r = $s->get_result();
                            while ($d = $r->fetch_assoc()):
                            ?>
                                <option value="<?= $d['division_id'] ?>">
                                    <?= htmlspecialchars($d['nama_divisi']) ?>
                                </option>
                            <?php endwhile; ?> -->
                        </select>
                        <small class="text-muted">Pilih divisi tujuan terlebih dahulu</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="ri-chat-3-line me-1" style="color:#6f42ff;"></i>
                            Catatan
                        </label>
                        <textarea name="catatan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Tambahkan catatan (opsional)"></textarea>
                    </div>
                </div>

                <div class="modal-footer" style="border-top:1px solid #e9ecef;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Batal
                    </button>
                    <button type="submit"
                            class="btn btn-primary"
                            style="background-color:#6f42ff;border-color:#6f42ff;">
                        <i class="ri-save-line me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="py-4"></div>

<script>
const modal = document.getElementById('addFlowModal');

modal.addEventListener('show.bs.modal', function () {
    setTimeout(() => {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.background = 'rgba(15,15,30,0.75)';
            backdrop.style.backdropFilter = 'blur(6px)';
        }
    }, 50);
});

modal.addEventListener('shown.bs.modal', function () {
    this.querySelector('.modal-dialog')
        .style.transform = 'translateY(0) scale(1)';
});
</script>

<script>
document.getElementById('to_divisi_id').addEventListener('change', function() {
    const divisiId = this.value;
    const userSelect = document.getElementById('received_by');
    
    if (!divisiId) {
        userSelect.innerHTML = '<option value="">Pilih User (Opsional)</option>';
        return;
    }
    
    fetch(`layout/get-users-by-divisi.php?divisi_id=${divisiId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Pilih User (Opsional)</option>';
            data.forEach(user => {
                options += `<option value="${user.user_id}">${user.nama_lengkap}</option>`;
            });
            userSelect.innerHTML = options;
        })
        .catch(error => {
            console.error('Error:', error);
            userSelect.innerHTML = '<option value="">Error loading users</option>';
        });
});
</script>
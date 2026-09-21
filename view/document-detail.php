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
                    <p class="text-white text-opacity-75 mb-2"><?= htmlspecialchars($doc['nomor_documen']) ?></p>
                    <div class="d-flex flex-wrap gap-3 text-white-50">
                        <div>
                            <i class="ri-map-pin-user-line me-1 text-white text-opacity-75"></i>
                            <?= htmlspecialchars($doc['receive']) ?>
                        </div>
                        <div>
                            <i class="ri-calendar-line me-1 text-white text-opacity-75"></i>
                            <?= !empty($doc['tanggal_kirim']) ? date('d M Y', strtotime($doc['tanggal_kirim'])) : 'Tidak ada tanggal' ?>
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
        
        <?php if ($creator): ?>
        <div class="d-flex gap-2">
            <a href="?view=document-edit&id=<?= $doc['document_id'] ?>" class="btn btn-secondary btn-sm">
                <i class="ri-ball-pen-line me-1"></i> Edit Dokumen
            </a><?php if ($accesdelete): ?>
            <a href="layout/delete-proses.php?id=<?= $doc['document_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus dokumen ini?')">
                <i class="ri-delete-bin-line me-1"></i> Hapus
            </a><?php endif; ?>
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
                                        <?= !empty($doc['div_penerima']) ? htmlspecialchars($doc['receive']) : 'Belum ada penerima' ?>
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                    <small class="text-muted d-block mb-1">Lokasi Saat Ini</small>
                                    <strong class="text-dark d-flex align-items-center">
                                        <i class="ri-map-pin-user-line me-2" style="color: #6f42ff;"></i>
                                        <?= htmlspecialchars($doc['nama_penerima']) ?>
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: #f8f9fa;">
                                    <small class="text-muted d-block mb-1">Tanggal</small>
                                    <strong class="text-dark d-flex align-items-center">
                                        <i class="ri-calendar-2-line me-2" style="color: #6f42ff;"></i>
                                        <?= !empty($doc['tanggal_kirim']) ? date('d F Y', strtotime($doc['tanggal_kirim'])) : 'Tidak ada tanggal' ?>
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
                            <div class="row align-items-end g-3">
                                <div class="col-md-8">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <?php 
                                        $statuses = ['Draft', 'Diterima', 'Dikirim', 'Dikembalikan', 'Diproses', 'Diarsipkan', 'Selesai'];
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
                            <button class="btn btn-sm" style="background-color: #6f42ff; color: white; border: none;" 
                                    onclick="openAddFlowModal('<?= $doc['document_id'] ?>')">
                                <i class="ri-add-line"></i>
                            </button>
                        <?php endif; ?>
                        <!-- <?php if ($bolehAkses): ?>
                        <a href="?view=add-flow&id=<?= $doc['document_id'] ?>">
                            <button class="btn btn-sm" style="background-color: #6f42ff; color: white; border: none;">
                                <i class="ri-add-line"></i>
                            </button>
                        </a>
                        <?php endif; ?> -->
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $flow_query = "SELECT df.*,
                                u_sent.nama_lengkap as pengirim,
                                u_received.nama_lengkap as penerima,
                                df.tanggal as waktu
                                FROM document_flows df
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
                        <div class="mb-3 position-relative">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <?php
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
                                        <?= htmlspecialchars($flow['pengirim']) ?> <i class="ri-arrow-right-line mx-1"></i> <?= htmlspecialchars($flow['penerima']) ?>
                                    </h6>
                                    <?php if (!empty($flow['aksi'])): ?>
                                    <p class="text-muted mb-1 small">
                                        <i class="ri-flag-line me-1"></i>
                                        <?= htmlspecialchars($flow['aksi']) ?>
                                    </p>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $canEdit = (
                                        $index === $total_flows - 1 && 
                                        $flow['sent_by'] === $_SESSION['user_id']
                                    );
                                    ?>
                                    
                                    <?php if (!empty($flow['catatan'])): ?>
                                    <div class="d-flex align-items-start gap-2">
                                        <p class="text-muted mb-1 small flex-grow-1" id="catatan-text-<?= $flow['flow_id'] ?>">
                                            <i class="ri-chat-3-line me-1"></i>
                                            <?= htmlspecialchars($flow['catatan']) ?>
                                        </p>
                                        <?php if ($canEdit): ?>
                                        <button class="btn btn-sm btn-link p-0 text-muted" onclick="editCatatan('<?= $flow['flow_id'] ?>', '<?= htmlspecialchars($flow['catatan'], ENT_QUOTES) ?>')">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <?php elseif ($canEdit): ?>
                                    <div class="d-flex align-items-start gap-2">
                                        <p class="text-muted mb-1 small flex-grow-1" id="catatan-text-<?= $flow['flow_id'] ?>">
                                            <i class="ri-chat-3-line me-1"></i>
                                            <em>Belum ada catatan</em>
                                        </p>
                                        <button class="btn btn-sm btn-link p-0 text-muted" onclick="editCatatan('<?= $flow['flow_id'] ?>', '')">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <p class="text-muted mb-0" style="font-size: 11px;">
                                        <?= date('d M Y H:i', strtotime($flow['tanggal'])) ?>
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

<div class="modal fade" id="modalEditCatatan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <div class="modal-header" style="background:#f7f5ff; border-bottom:1px solid #e5dfff;">
                <h5 class="modal-title d-flex align-items-center" style="color:#6f42ff; font-weight:600;">
                    <i class="ri-edit-2-line me-2" style="font-size:20px;"></i>
                    Edit Catatan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit-flow-id">

                <div class="mb-3">
                    <div style="position:relative;">
                        <i class="ri-chat-3-line"
                           style="position:absolute; left:12px; top:6px; font-size:18px; color:#6f42ff;"></i>

                        <textarea 
                            class="form-control"
                            id="edit-catatan"
                            rows="4"
                            placeholder="Tulis catatan tambahan..."
                            style="padding-left:40px; border-radius:8px; border:1px solid #d3c9ff;"
                        ></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="border-top:1px solid #eee;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" onclick="saveCatatan()"
                        style="background-color:#6f42ff; border:none; box-shadow:0 2px 6px rgba(111,66,255,.4);">
                    <i class="ri-save-3-line me-1"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalAddFlow" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f42ff, #9b4dff); color: white; border: none;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:50px; height:50px; background:rgba(255,255,255,0.2); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="ri-add-circle-line" style="font-size:32px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" style="font-weight:600;">Update Status Dokumen</h5>
                        <small>Tambahkan riwayat perjalanan dokumen</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding: 30px;">
                <form id="formAddFlow">
                    <input type="hidden" id="add-document-id" name="document_id">
                    <input type="hidden" id="add-sent-by" name="sent_by" value="<?= $_SESSION['user_id'] ?? '' ?>">
                    <input type="hidden" name="nama_pengirim" value="<?= $doc['nama_penerima'] ?>">

                    <div class="mb-3">
                        <label class="fw-bold">Aksi <span style="color:red;">*</span></label>
                        <div style="position:relative;">
                            <i class="ri-flag-line" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#6f42ff;"></i>
                            <select name="aksi" id="add-aksi" required class="form-select" style="padding-left: 35px;">
                                <option value="">Pilih Aksi</option>
                                <option value="Dikirim">Dikirim</option>
                                <option value="Diterima">Diterima</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Diarsipkan">Diarsipkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Dari Divisi</label>
                        <div style="position:relative;">
                            <i class="ri-user-line" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#6f42ff;"></i>
                            <input type="text" class="form-control" 
                                   value="<?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'User') ?>" 
                                   readonly style="padding-left: 35px; background-color:#f8f9fa;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Ke Divisi <span style="color:red;">*</span></label>
                        <div style="position:relative;">
                            <i class="ri-user-received-line" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#6f42ff;"></i>
                            <select name="received_by" id="add-received-by" required class="form-select" style="padding-left: 35px;">
                                <option value="">Pilih Divisi</option>
                                <?php
                                $q = "SELECT user_id, nama_lengkap
                                    FROM users 
                                    WHERE user_id != ? 
                                    ORDER BY nama_lengkap ASC";
                                $s = $conn->prepare($q);
                                $s->bind_param("s", $_SESSION['user_id']);
                                $s->execute();
                                $r = $s->get_result();
                                
                                $current_divisi = '';
                                while ($d = $r->fetch_assoc()):
                                    if ($current_divisi != $d['divisi']) {
                                        if ($current_divisi != '') echo '</optgroup>';
                                        echo '<optgroup label="' . htmlspecialchars($d['divisi']) . '" style="font-weight:600; color:#6f42ff;">';
                                        $current_divisi = $d['divisi'];
                                    }
                                ?>
                                    <option value="<?= $d['user_id'] ?>">
                                        <?= htmlspecialchars($d['nama_lengkap']) ?>
                                    </option>
                                <?php 
                                endwhile; 
                                if ($current_divisi != '') echo '</optgroup>';
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Tanggal</label>
                        <div style="position:relative;">
                            <i class="ri-calendar-line" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#6f42ff;"></i>
                            <input type="date" name="tanggal" id="add-tanggal" 
                                   value="<?= date('Y-m-d') ?>"
                                   class="form-control" style="padding-left: 35px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Catatan</label>
                        <div style="position:relative;">
                            <i class="ri-chat-3-line" style="position:absolute; left:10px; top:12px; color:#6f42ff;"></i>
                            <textarea name="catatan" id="add-catatan" rows="4" 
                                      placeholder="Tambahkan catatan (opsional)"
                                      class="form-control" style="padding-left: 35px;"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Ditangani Oleh</label>
                        <div style="position:relative;">
                            <i class="ri-user-line" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#6f42ff;"></i>
                            <input type="text" name="nama_penerima" id="add-nama-penerima-input" 
                                   placeholder="Masukkan nama yang menangani..."
                                   class="form-control" style="padding-left: 35px;">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveAddFlow"
                        style="background: linear-gradient(135deg,#6f42ff,#9b4dff); border: none;">
                    <i class="ri-save-3-line me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function editCatatan(flowId, currentCatatan) {
        document.getElementById('edit-flow-id').value = flowId;
        document.getElementById('edit-catatan').value = currentCatatan;
        
        const modal = new bootstrap.Modal(document.getElementById('modalEditCatatan'));
        modal.show();
    }

    function saveCatatan() {
        const flowId = document.getElementById('edit-flow-id').value;
        const catatan = document.getElementById('edit-catatan').value;
        
        // Disable tombol simpan saat proses
        const btnSimpan = event.target;
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        
        fetch('layout/edit-flow-catatan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `flow_id=${encodeURIComponent(flowId)}&catatan=${encodeURIComponent(catatan)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response:', data); // Untuk debugging
            
            if (data.success) {
                // Update tampilan catatan
                const catatanText = document.getElementById('catatan-text-' + flowId);
                if (catatan.trim() === '') {
                    catatanText.innerHTML = '<i class="ri-chat-3-line me-1"></i><em>Belum ada catatan</em>';
                } else {
                    catatanText.innerHTML = '<i class="ri-chat-3-line me-1"></i>' + escapeHtml(catatan);
                }
                
                // Tutup modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditCatatan'));
                modal.hide();
                
                // Tampilkan notifikasi sukses
                alert('Catatan berhasil diubah!');
            } else {
                alert('Error: ' + data.message);
            }
            
            // Enable tombol kembali
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = 'Simpan';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message + '. Silakan periksa console untuk detail.');
            
            // Enable tombol kembali
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = 'Simpan';
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>

<script>
    function openAddFlowModal(documentId) {
        console.log('Opening modal for document:', documentId);
        
        // Set document ID
        document.getElementById('add-document-id').value = documentId;
        
        // Reset form
        document.getElementById('formAddFlow').reset();
        document.getElementById('add-tanggal').value = '<?= date('Y-m-d') ?>';
        
        // Buka modal
        try {
            const modalEl = document.getElementById('modalAddFlow');
            if (!modalEl) {
                console.error('Modal element not found!');
                return;
            }
            
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
            console.log('Modal should be open now');
        } catch (error) {
            console.error('Error opening modal:', error);
            alert('Error membuka modal: ' + error.message);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnSave = document.getElementById('btnSaveAddFlow');
        if (btnSave) {
            btnSave.addEventListener('click', saveAddFlow);
            console.log('Save button listener attached');
        } else {
            console.error('Save button not found!');
        }
    });

    function saveAddFlow() {
        console.log('saveAddFlow called');
        
        const form = document.getElementById('formAddFlow');
        if (!form) {
            console.error('Form not found!');
            return;
        }
        
        // Validasi form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        const btnSimpan = document.getElementById('btnSaveAddFlow');
        const originalText = btnSimpan.innerHTML;
        
        // Disable tombol
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
        
        // Ambil data form
        const formData = new FormData(form);
        
        // Debug
        console.log('Form data:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        // Kirim data
        fetch('layout/add-document-flow.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text(); // Gunakan text() dulu untuk debug
        })
        .then(text => {
            console.log('Raw response:', text);
            
            // Coba parse JSON
            try {
                const data = JSON.parse(text);
                
                if (data.success) {
                    // Tutup modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAddFlow'));
                    if (modal) modal.hide();
                    
                    alert('Status dokumen berhasil ditambahkan!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Terjadi kesalahan'));
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                alert('Response bukan JSON valid. Cek console untuk detail.');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        })
        .finally(() => {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = originalText;
        });
    }
</script>
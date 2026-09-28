<div class="container mt-3">

    <div style="max-width:700px; margin:0 auto;">

        <a href="?view=document-detail&id=<?= $doc['document_id'] ?>" class="docflow-back-link">
            <i class="ri-arrow-left-line" style="font-size:18px; margin-right:6px;"></i>
            Kembali ke Dokumen
        </a>

        <!-- HEADER -->
        <div class="docflow-form-header">
            <div class="docflow-form-header-icon">
                <i class="ri-edit-box-line" style="font-size:40px;"></i>
            </div>

            <div>
                <h4 style="margin:0; font-weight:600;">Edit Dokumen</h4>
                <small>Perbarui informasi dokumen</small>
            </div>
        </div>

        <!-- FORM -->
        <div class="docflow-form-card">

            <?php if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); } ?>
            <form action="layout/edit-proses.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <input type="hidden" name="document_id" value="<?= $doc['document_id'] ?>">

                <!-- NOMOR -->
                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Nomor Dokumen</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-hashtag docflow-field-icon"></i>
                            <input type="text" name="nomor_dokumen"
                                   value="<?= $doc['nomor_documen'] ?>"
                                   class="docflow-field-input">
                        </div>
                    </div>

                </div>

                <!-- JUDUL -->
                <div style="margin-bottom:20px;">
                    <label class="fw-bold">Judul Dokumen</label>
                    <div class="docflow-field-wrap">
                        <i class="ri-file-text-line docflow-field-icon"></i>
                        <input type="text" name="judul"
                               value="<?= $doc['judul'] ?>"
                               class="docflow-field-input">
                    </div>
                </div>

                <!-- DESKRIPSI -->
                <div style="margin-bottom:20px;">
                    <label class="fw-bold">Deskripsi</label>
                    <div class="docflow-field-wrap">
                        <i class="ri-align-left docflow-field-icon--top"></i>
                        <textarea name="deskripsi" rows="4"
                                  class="docflow-field-input"><?= $doc['deskripsi'] ?></textarea>
                    </div>
                </div>

                <!-- JENIS + PRIORITAS -->
                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Jenis Dokumen</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-folder-open-line docflow-field-icon"></i>
                            <select name="jenis_dokumen" class="docflow-field-input">
                                <?php
                                    $jenis = ["Surat Masuk","Surat Keluar","Memo","SPP","Proposal","Laporan","Lainnya"];
                                    foreach ($jenis as $j) {
                                        $sel = $j == $doc['jenis_dokumen'] ? "selected" : "";
                                        echo "<option $sel>$j</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div style="flex:1;">
                        <label class="fw-bold">Prioritas</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-flag-line docflow-field-icon"></i>
                            <select name="prioritas" class="docflow-field-input">
                                <?php
                                    $prio = ["Rendah","Sedang","Tinggi","Urgent"];
                                    foreach ($prio as $p) {
                                        $sel = $p == $doc['prioritas'] ? "selected" : "";
                                        echo "<option $sel>$p</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- TANGGAL -->
                <div style="margin-bottom:25px;">
                    <label class="fw-bold">Tanggal Kirim</label>
                    <div class="docflow-field-wrap">
                        <i class="ri-calendar-line docflow-field-icon"></i>
                        <input type="date" name="tanggal_kirim"
                               value="<?= date('Y-m-d', strtotime($doc['tanggal_kirim'])) ?>"
                               class="docflow-field-input">
                    </div>
                </div>

                <!-- BUTTON -->
                <div style="display:flex; justify-content:space-between;">

                    <a href="?view=document-detail&id=<?= $doc['document_id'] ?>" class="docflow-btn-outline">
                        Batal
                    </a>

                    <button type="submit" class="docflow-btn-submit">
                        <i class="ri-save-3-line"></i> Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
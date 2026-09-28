<div class="container mt-3">

    <div style="max-width:700px; margin:0 auto;">

        <a href="?" class="docflow-back-link">
            <i class="ri-arrow-left-line" style="font-size:18px; margin-right:6px;"></i>
            Kembali Beranda
        </a>

        <!-- HEADER -->
        <div class="docflow-form-header">
            <div class="docflow-form-header-icon">
                <i class="ri-file-edit-line" style="font-size:32px;"></i>
            </div>

            <div>
                <h4 style="margin:0; font-weight:600;">Buat Dokumen Baru</h4>
                <small>Isi form di bawah untuk membuat dokumen</small>
            </div>
        </div>

        <!-- FORM BODY -->
        <div class="docflow-form-card">

            <?php if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); } ?>
            <form action="layout/create-proses.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <!-- NOMOR + BARCODE -->
                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Nomor Dokumen</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-hashtag docflow-field-icon"></i>
                            <input type="text" name="nomor_dokumen" placeholder="Masukkan nomor dokumen..."
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
                               placeholder="Masukkan judul dokumen..."
                               class="docflow-field-input">
                    </div>
                </div>

                <!-- DESKRIPSI -->
                <div style="margin-bottom:20px;">
                    <label class="fw-bold">Deskripsi</label>
                    <div class="docflow-field-wrap">
                        <i class="ri-align-left docflow-field-icon--top"></i>
                        <textarea name="deskripsi" rows="4"
                                  placeholder="Masukkan deskripsi dokumen..."
                                  class="docflow-field-input"></textarea>
                    </div>
                </div>

                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Jenis Dokumen</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-folder-open-line docflow-field-icon"></i>
                            <select name="jenis_dokumen" class="docflow-field-input">
                                <option>Surat Masuk</option>
                                <option>Surat Keluar</option>
                                <option>Memo</option>
                                <option>SPP</option>
                                <option>Proposal</option>
                                <option>Laporan</option>
                                <option>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div style="flex:1;">
                        <label class="fw-bold">Prioritas</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-flag-line docflow-field-icon"></i>
                            <select name="prioritas" class="docflow-field-input">
                                <option>Sedang</option>
                                <option>Tinggi</option>
                                <option>Rendah</option>
                                <option>Urgent</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- PENGIRIM & PENERIMA -->
                <?php $getUsers = mysqli_query($conn, "SELECT user_id, nama_lengkap FROM users ORDER BY nama_lengkap ASC");
                $users = mysqli_fetch_all($getUsers, MYSQLI_ASSOC);
                ?>

                <div style="display:flex; gap:20px; margin-bottom:20px;">
                    <div style="flex:1;">
                        <label class="fw-bold">Divisi Pengirim</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-building-2-line docflow-field-icon"></i>
                            <input type="text" 
                                   value="<?= $_SESSION['nama_lengkap']; ?>"
                                   readonly
                                   class="docflow-field-input">
                            <input type="hidden" name="pengirim" value="<?= $_SESSION['user_id']; ?>">
                        </div>
                    </div>

                    <div style="flex:1;">
                        <label class="fw-bold">Divisi Penerima</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-building-2-line docflow-field-icon"></i>
                            <select name="penerima" class="docflow-field-input">
                                <option value="">-- Pilih penerima --</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['user_id']; ?>">
                                        <?= htmlspecialchars($u['nama_lengkap']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:20px; margin-bottom:20px;">
                    <div style="flex:1;">
                        <label class="fw-bold">Nama Pengirim</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-user-line docflow-field-icon"></i>
                            <input type="text" name="nama_pengirim"
                               placeholder="Masukkan nama pengirim..."
                               class="docflow-field-input">
                        </div>
                    </div>

                    <div style="flex:1;">
                        <label class="fw-bold">Nama Penerima</label>
                        <div class="docflow-field-wrap">
                            <i class="ri-user-received-line docflow-field-icon"></i>
                            <input type="text" name="nama_penerima"
                               placeholder="Masukkan nama penerima..."
                               class="docflow-field-input">
                        </div>
                    </div>
                </div>

                <!-- TANGGAL -->
                <div style="margin-bottom:25px;">
                    <label class="fw-bold">Tanggal Kirim</label>
                    <div class="docflow-field-wrap">
                        <i class="ri-calendar-line docflow-field-icon"></i>
                        <input type="date" name="tanggal_kirim" class="docflow-field-input">
                    </div>
                </div>

                <!-- BUTTON -->
                <div style="display:flex; justify-content:space-between; align-items:center;">

                    <a href="?view=home" class="docflow-btn-outline">
                        Batal
                    </a>

                    <button type="submit" class="docflow-btn-submit">
                        <i class="ri-check-line"></i> Buat Dokumen
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
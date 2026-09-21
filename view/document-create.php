<div class="container mt-3">

    <div style="max-width:700px; margin:0 auto;">

        <a href="?"
           class="d-inline-flex align-items-center"
           style="text-decoration:none; color:#555; font-size:15px; margin-bottom:15px;">
            <i class="ri-arrow-left-line" style="font-size:18px; margin-right:6px;"></i>
            Kembali Beranda
        </a>

        <!-- HEADER -->
        <div style="
            background: linear-gradient(135deg, #6f42ff, #9b4dff);
            padding:25px;
            border-radius:12px 12px 0 0;
            color:white;
            display:flex;
            align-items:center;
            gap:15px;
        ">
            <div style="
                width:60px; height:60px;
                background:rgba(255,255,255,0.2);
                border-radius:10px;
                display:flex; align-items:center; justify-content:center;
            ">
                <i class="ri-file-edit-line" style="font-size:32px;"></i>
            </div>

            <div>
                <h4 style="margin:0; font-weight:600;">Buat Dokumen Baru</h4>
                <small>Isi form di bawah untuk membuat dokumen</small>
            </div>
        </div>

        <!-- FORM BODY -->
        <div style="
            background:white;
            padding:30px;
            border-radius:0 0 12px 12px;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
        ">

            <?php if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); } ?>
            <form action="layout/create-proses.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <!-- NOMOR + BARCODE -->
                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Nomor Dokumen</label>
                        <div style="position:relative;">
                            <i class="ri-hashtag text-primary" 
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <input type="text" name="nomor_dokumen" placeholder="Masukkan nomor dokumen..."
                                   style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                        </div>
                    </div>

                </div>

                <!-- JUDUL -->
                <div style="margin-bottom:20px;">
                    <label class="fw-bold">Judul Dokumen</label>
                    <div style="position:relative;">
                        <i class="ri-file-text-line text-primary" 
                           style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                        <input type="text" name="judul"
                               placeholder="Masukkan judul dokumen..."
                               style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                </div>\

                <!-- DESKRIPSI -->
                <div style="margin-bottom:20px;">
                    <label class="fw-bold">Deskripsi</label>
                    <div style="position:relative;">
                        <i class="ri-align-left text-primary" 
                           style="position:absolute; left:10px; top:15px; color:#777;"></i>
                        <textarea name="deskripsi" rows="4"
                                  placeholder="Masukkan deskripsi dokumen..."
                                  style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;"></textarea>
                    </div>
                </div>

                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Jenis Dokumen</label>
                        <div style="position:relative;">
                            <i class="ri-folder-open-line text-primary" 
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <select name="jenis_dokumen"
                                    style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
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
                        <div style="position:relative;">
                            <i class="ri-flag-line text-primary" 
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <select name="prioritas"
                                    style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
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
                        <div style="position:relative;">
                            <i class="ri-building-2-line text-primary" 
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <input type="text" 
                                   value="<?= $_SESSION['nama_lengkap']; ?>"
                                   readonly
                                   style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                            <input type="hidden" name="pengirim" value="<?= $_SESSION['user_id']; ?>">
                        </div>
                    </div>

                    <div style="flex:1;">
                        <label class="fw-bold">Divisi Penerima</label>
                        <div style="position:relative;">
                            <i class="ri-building-2-line text-primary"
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <select name="penerima"
                                    style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
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
                        <div style="position:relative;">
                            <i class="ri-user-line text-primary" 
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <input type="text" name="nama_pengirim"
                               placeholder="Masukkan nama pengirim..."
                               style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                        </div>
                    </div>

                    <div style="flex:1;">
                        <label class="fw-bold">Nama Penerima</label>
                        <div style="position:relative;">
                            <i class="ri-user-received-line text-primary"
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <input type="text" name="nama_penerima"
                               placeholder="Masukkan nama penerima..."
                               style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                        </div>
                    </div>
                </div>

                <!-- TANGGAL -->
                <div style="margin-bottom:25px;">
                    <label class="fw-bold">Tanggal Kirim</label>
                    <div style="position:relative;">
                        <i class="ri-calendar-line text-primary"
                           style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                        <input type="date" name="tanggal_kirim"
                               style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                </div>

                <!-- BUTTON -->
                <div style="display:flex; justify-content:space-between; align-items:center;">

                    <a href="?view=home"
                       style="
                           padding:10px 20px;
                           border:1px solid #6f42ff;
                           color:#6f42ff;
                           text-decoration:none;
                           border-radius:6px;
                           font-weight:500;
                       ">
                        Batal
                    </a>

                    <button type="submit"
                            style="
                                padding:10px 24px;
                                background:linear-gradient(135deg,#6f42ff,#9b4dff);
                                color:white;
                                border:none;
                                border-radius:6px;
                                font-weight:500;
                                display:flex;
                                align-items:center;
                                gap:6px;
                            ">
                        <i class="ri-check-line"></i> Buat Dokumen
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
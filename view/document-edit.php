<div class="container mt-3">

    <div style="max-width:700px; margin:0 auto;">

        <a href="?view=document-detail&id=<?= $doc['document_id'] ?>" 
           class="d-inline-flex align-items-center" 
           style="text-decoration:none; color:#555; font-size:15px; margin-bottom:15px;">
            <i class="ri-arrow-left-line" style="font-size:18px; margin-right:6px;"></i>
            Kembali ke Dokumen
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
                <i class="ri-edit-box-line" style="font-size:40px;"></i>
            </div>

            <div>
                <h4 style="margin:0; font-weight:600;">Edit Dokumen</h4>
                <small>Perbarui informasi dokumen</small>
            </div>
        </div>

        <!-- FORM -->
        <div style="
            background:white;
            padding:30px;
            border-radius:0 0 12px 12px;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
        ">

            <form action="layout/edit-proses.php" method="POST">

                <input type="hidden" name="document_id" value="<?= $doc['document_id'] ?>">

                <!-- NOMOR + BARCODE -->
                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Nomor Dokumen</label>
                        <div style="position:relative;">
                            <i class="ri-hashtag text-primary"
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <input type="text" name="nomor_dokumen"
                                   value="<?= $doc['nomor_dokumen'] ?>"
                                   style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                        </div>
                    </div>

                    <div style="flex:1;">
                        <label class="fw-bold">Kode Barcode</label>
                        <div style="position:relative;">
                            <i class="ri-barcode-box-line text-primary"
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <input type="text" name="kode_barcode"
                                   value="<?= $doc['kode_barcode'] ?>"
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
                               value="<?= $doc['judul'] ?>"
                               style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                </div>

                <!-- DESKRIPSI -->
                <div style="margin-bottom:20px;">
                    <label class="fw-bold">Deskripsi</label>
                    <div style="position:relative;">
                        <i class="ri-align-left text-primary"
                           style="position:absolute; left:10px; top:12px; color:#777;"></i>
                        <textarea name="deskripsi" rows="4"
                                  style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;"><?= $doc['deskripsi'] ?></textarea>
                    </div>
                </div>

                <!-- JENIS + PRIORITAS -->
                <div style="display:flex; gap:20px; margin-bottom:20px;">

                    <div style="flex:1;">
                        <label class="fw-bold">Jenis Dokumen</label>
                        <div style="position:relative;">
                            <i class="ri-folder-open-line text-primary"
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <select name="jenis_dokumen"
                                    style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
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
                        <div style="position:relative;">
                            <i class="ri-flag-line text-primary"
                               style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                            <select name="prioritas"
                                    style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
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
                    <label class="fw-bold">Tanggal Tenggat</label>
                    <div style="position:relative;">
                        <i class="ri-calendar-line text-primary"
                           style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#777;"></i>
                        <input type="date" name="tanggal_tenggat"
                               value="<?= $doc['tanggal_tenggat'] ?>"
                               style="width:100%; padding:10px 10px 10px 35px; border-radius:6px; border:1px solid #ddd;">
                    </div>
                </div>

                <!-- BUTTON -->
                <div style="display:flex; justify-content:space-between;">

                    <a href="?view=document-detail&id=<?= $doc['document_id'] ?>"
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
                        <i class="ri-save-3-line"></i> Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

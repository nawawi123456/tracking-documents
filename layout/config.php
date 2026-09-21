<?php
$host = 'localhost';
$username = 'root'; // sesuaikan
$password = ''; // sesuaikan
$database = 'docs'; // sesuaikan

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('BASE_URL', '/magang/documen3/');

// $host = 'localhost';
// $username = 'u1634226_ptasn_trackdoc';
// $password = 'nK_rk?ibZ@.zb9x%';
// $database = 'u1634226_trackingdoc';

// $conn = mysqli_connect($host, $username, $password, $database);

// if (!$conn) {
//     die("Koneksi database gagal: " . mysqli_connect_error());
// }

// mysqli_set_charset($conn, 'utf8mb4');

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
// define('BASE_URL', '/magang/documen3/');

?>
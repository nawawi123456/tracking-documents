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

?>
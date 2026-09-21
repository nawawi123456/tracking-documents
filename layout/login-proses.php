<?php
// session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '?view=login');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    header('Location: ' . BASE_URL . '?view=login&error=1');
    exit;
}

$username = mysqli_real_escape_string($conn, $username);

$query = "SELECT *
          FROM users
          WHERE username = '$username'";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: ' . '../?view=login&error=2');
    exit;
}

$user = mysqli_fetch_assoc($result);
if (password_verify($password, $user['password'])) {

    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

    header('Location: ' . '../');
    exit;

} else {
    header('Location: ' . '../?view=login&error=2');
    exit;
}

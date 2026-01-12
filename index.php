<?php // include 'layout/index-proses.php'; ?>
<?php //include 'layout/detail-proses.php'; ?>

<?php
session_start();
require_once 'layout/config.php'; 

$view = $_GET['view'] ?? 'home';

if ($view !== 'login') {
    require_once 'layout/auth-check.php';
}

if ($view === 'home') {
    require_once 'layout/home-proses.php';
    include 'view/header.php';
}

if ($view === 'document-detail') {
    require_once 'layout/detail-proses.php';

    $bolehAkses = isset($doc['penerima']) 
        && $_SESSION['user_id'] === $doc['penerima'];
}

if ($view === 'document-edit') {
    require_once 'layout/detail-proses.php';
}

if (!preg_match('/^[a-zA-Z0-9_-]+$/', $view)) {
    $view = 'home';
}

$file = "view/$view.php";
if (!file_exists($file)) {
    $file = "view/home.php";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ASN DocuFlow</title>
    <?php include 'layout/head-css.php'; ?>
</head>
<body>

<main>
    <?php include $file; ?>
</main>


<!-- Bootstrap JS -->
 <script src="layout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

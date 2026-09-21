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

    if ($_SESSION['user_id'] === 'USER-999') {
        $creator = 1;
        $accesdelete = 1;
        $bolehAkses = 1;
    } else {
        $creator = isset($doc['created_by']) 
            && $_SESSION['user_id'] === $doc['created_by'];

        $accesdelete = isset($doc['created_by'], $doc['status']) &&
            $_SESSION['user_id'] === $doc['created_by'] &&
            strtolower($doc['status']) === 'draft';

        $bolehAkses = isset($doc['div_penerima']) 
            && $_SESSION['user_id'] === $doc['div_penerima'];
    }
}

if ($view === 'document-edit') {
    require_once 'layout/detail-proses.php';
}

if ($view === 'add-flow') {
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


<!-- JAVASCRIPT -->
<script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="libs/simplebar/simplebar.min.js"></script>
<script src="libs/node-waves/waves.min.js"></script>
<script src="libs/feather-icons/feather.min.js"></script>
<script src="js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="js/plugins.js"></script>
<!-- App js -->
<script src="js/app.js"></script>

<!-- Your custom scripts -->
<script src="layout.js"></script>

</body>
</html>

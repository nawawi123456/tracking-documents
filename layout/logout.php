<?php
session_start();
require_once 'config.php'; 
// Hapus semua session
session_unset();
session_destroy();

// Redirect ke login via index.php
header('Location: ' . '../?view=login');
exit;

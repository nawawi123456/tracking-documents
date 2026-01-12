<?php
session_start();
require 'config.php';

$id = $_POST['id'];
$status = $_POST['status'];

$q1 = mysqli_query($conn, "UPDATE documents SET status='$status', update_doc=NOW() WHERE document_id='$id'");
// $q2 = mysqli_query($conn, "UPDATE document_flows SET status='$status' WHERE document_id='$id'");

if ($q1) {
    header("Location: ../?view=document-detail&id=$id");
    exit;
} else {
    echo "Gagal update: " . mysqli_error($conn);
}

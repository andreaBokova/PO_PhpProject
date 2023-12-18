<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login-form.php");
}

include_once "../parts/head.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sale_id'])) {
    $saleId = $_POST['sale_id'];

    $result = $db->deleteSale($saleId);

    if ($result) {
        header("Location: sales.php?status=3");
        exit;
    } else {
        header("Location: sales.php?status=4");
        exit;
    }
} else {
    header("Location: sales.php");
    exit;
}


?>
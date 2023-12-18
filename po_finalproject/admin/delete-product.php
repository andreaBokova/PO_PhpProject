<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login-form.php");
}

include_once "../parts/head.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    $result = $db->deleteProduct($productId);

    if ($result) {
        header("Location: products.php?status=3");
        exit;
    } else {
        header("Location: products.php?status=4");
        exit;
    }

} else {
    header("Location: products.php");
    exit;
}

?>
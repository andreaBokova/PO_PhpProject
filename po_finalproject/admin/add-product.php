<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login-form.php");
}

include_once "../parts/head.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset(
            $_POST['name'],
            $_POST['brand'],
            $_POST['gender'],
            $_POST['type'],
            $_POST['material'],
            $_POST['weight_in_grams'],
            $_POST['description'],
            $_POST['selling_price'],
            $_POST['units_in_stock'],
            $_POST['image_url'],
            $_POST['account_id']
        )
    ) {
        $result = $db->addProduct(
            $_POST['name'],
            $_POST['brand'],
            $_POST['gender'],
            $_POST['type'],
            $_POST['material'],
            $_POST['weight_in_grams'],
            $_POST['description'],
            $_POST['selling_price'],
            $_POST['units_in_stock'],
            $_POST['image_url'],
            $_POST['account_id']
        );

        if ($result) {
            header("Location: products.php?status=1");
            exit();
        } else {
            header("Location: products.php?status=2");
            exit();
        }
    }
}

header("Location: products.php");
exit();


?>
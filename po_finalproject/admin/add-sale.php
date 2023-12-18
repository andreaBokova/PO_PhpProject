<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login-form.php");
}

include_once "../parts/head.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['date'], $_POST['account_id'], $_POST['productContainer'])) {
        $date = $_POST['date'];
        $accountId = $_POST['account_id'];

        // Create an array to store product details
        $products = [];

        // Loop through each product in the container
        foreach ($_POST['productContainer'] as $productNumber) {
            // Check if necessary parameters are set for each product
            if (isset($_POST['product' . $productNumber], $_POST['quantity' . $productNumber])) {
                // Extract product details
                $productName = $_POST['product' . $productNumber];
                $quantity = $_POST['quantity' . $productNumber];
                $price = $_POST['price' . $productNumber];

                // Add product details to the array
                $products[] = [
                    'name' => $productName,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

        }

        // Call your addSale method here with $date, $accountId, and $products
        $result = $db->addSale($date, $accountId, $products);

        if ($result) {
            header("Location: sales.php?status=1");
            exit();
        } else {
            header("Location: sales.php?status=2");
            exit();
        }
    }
}

header("Location: sales.php");
exit();

?>



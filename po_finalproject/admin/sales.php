<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login-form.php");
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    echo "User ID not found in the session.";
}

$pageTitle = "Sales";
include_once "../parts/head.php";


if (isset($_GET['status'])) {
    if ($_GET['status'] == 1) {
        echo "<br><p style='color: green'>Sale added</p><br>";
    } elseif ($_GET['status'] == 2) {
        echo "<br><p style='color: red'>Sale not added(product/s low in stock)</p><br>";
    } elseif ($_GET['status'] == 3) {
        echo "<br><p style='color: green'>Sale deleted</p><br>";
    } elseif ($_GET['status'] == 4) {
        echo "<br><p style='color: red'>Sale not deleted</p><br>";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<body id="reportsPage" class="bg02">
    <div class="" id="home">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    include_once "../parts/navigation.php";
                    ?>
                </div>
            </div>
            <div class="row tm-content-row tm-mt-big">
                <div class="col-xl-12 col-lg-12 tm-md-12 tm-sm-12 tm-col">
                    <div class="bg-white tm-block h-100">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <h2 class="tm-block-title d-inline-block">Products</h2>
                            </div>
                            <div class="col-md-4 col-sm-12 text-right">
                                <a href="add-sale-form.php" class="btn btn-small btn-primary">Add New Sale</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped tm-table-striped-even mt-3">
                                <thead>
                                    <tr class="tm-bg-gray">
                              
                                        <th scope="col">Sale ID</th>
                                        <th scope="col" class="text-center">Date</th>
                                        <th scope="col" class="text-center">Sale Details</th>
                                        <th scope="col" class="text-center">Order total($)</th>
                                        <th scope="col" class="text-center"></th>

                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                   
                                    $products = $db->getProducts($userId);
                                    $sales =  $db->getSales($userId);


                                    foreach ($sales as $sale) {

                                        echo "<tr>";
                                        echo "<td class='tm-product-name'>" . $sale['id'] . "</td>" .
                                            "<td class='text-center'>" . $sale['date'] . "</td>";

                                        $order_total = 0;
                                        if (!empty($sale['products'])) {
                                            echo  "<td class='text-center'>";
                                            foreach ($sale['products'] as $product) {

                                                $order_total += $product['quantity'] * $product['selling_price'];
                                                echo  $product['quantity'] . " x " . $product['product_name']  . "(" . $product['selling_price'] . "$)" .  "<br>";
                                            }
                                            echo  "</td>";
                                        }

                                        echo "<td class='text-center'>" . $order_total . "</td>";
                                        echo "<td><form method='post' action='delete-sale.php'>
                <input type='hidden' name='sale_id' value='" . $sale['id'] . "'>
                <button type='submit' style='border:none; background-color:transparent'  onclick=\"return confirm('Are you sure you want to delete this sale?')\">
                <i class='fas fa-trash-alt tm-trash-icon'></i>
                </button></form></td>";
                                        echo "</tr>";
                                    }


                                    ?>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "../parts/footer.php"; ?>
    </div>
</body>

</html>
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

$pageTitle = "Products";
include_once "../parts/head.php";


if (isset($_GET['status'])) {
    if ($_GET['status'] == 1) {
        echo "<br><p style='color: green'>Product added</p><br>";
    } elseif ($_GET['status'] == 2) {
        echo "<br><p style='color: red'>Product not added</p><br>";
    } elseif ($_GET['status'] == 3) {
        echo "<br><p style='color: green'>Product deleted</p><br>";
    } elseif ($_GET['status'] == 4) {
        echo "<br><p style='color: red'>Product not deleted</p><br>";
    } elseif ($_GET['status'] == 4) {
        echo "<br><p style='color: red'>Product edited</p><br>";
    } elseif ($_GET['status'] == 4) {
        echo "<br><p style='color: red'>Product not edited</p><br>";
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
                <div class="col-xl-8 col-lg-12 tm-md-12 tm-sm-12 tm-col">
                    <div class="bg-white tm-block h-100">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <h2 class="tm-block-title d-inline-block">Products</h2>
                            </div>
                            <div class="col-md-4 col-sm-12 text-right">
                                <a href="add-product-form.php" class="btn btn-small btn-primary">Add New Product</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped tm-table-striped-even mt-3">
                                <thead>
                                    <tr class="tm-bg-gray">
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Brand</th>
                                        <th scope="col" class="text-center">Gender</th>
                                        <th scope="col" class="text-center">Selling Price($)</th>
                                        <th scope="col" class="text-center">Units In Stock</th>
                                        <th scope="col" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                    $itemsPerPage = 3; // Set the number of items per page
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page or set default to 1

                                    $products = $db->getLimitProducts($page, $itemsPerPage, $userId);

                                    foreach ($products as $product) {
                                        $productId = $product['id'];
                                        echo "<tr onclick=\"window.location.href='edit-product-form.php?product_id=$productId';\">";
                                        echo "<td class='tm-product-name'>" . $product['name'] . "</td>" .
                                            "<td class='text-center'>" . $product['brand'] . "</td>" .
                                            "<td class='text-center'>" . $product['gender'] . "</td>" .
                                            "<td class='text-center'>" . $product['selling_price'] . "</td>" .
                                            "<td class='text-center'>" . $product['units_in_stock'] . "</td>" .
                                            "<td>
                                            <form method='post' action='delete-product.php'>
                                            <input type='hidden' name='product_id' value='" . $productId . "'>
                                            <button type='submit' style='border:none; background-color:transparent'  onclick=\"return confirm('Are you sure you want to delete this product?')\">
                                            <i class='fas fa-trash-alt tm-trash-icon'></i>
                                            </button></form></td>";
                                        echo "</tr>";
                                    }

                                    // Display pagination links
                                    $totalProducts = $db->getTotalProductsCount($userId);
                                    $totalPages = ceil($totalProducts / $itemsPerPage);

                                    echo "Page";
                                    echo "&nbsp&nbsp&nbsp";
                                    echo "<nav aria-label='Page navigation' class='d-inline-block'>";
                                    echo "<ul class='pagination tm-pagination'>";
                                    for ($i = 1; $i <= $totalPages; $i++) {
                                        echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'>";
                                        echo "<a class='page-link' href='products.php?page=$i'>$i</a>";
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                    echo "</nav>";

                                    ?>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12 tm-md-12 tm-sm-12 tm-col">
                    <div class="bg-white tm-block h-100" style="max-height: 600px; overflow-y: auto;">
                        <h2 class="tm-block-title d-inline-block"></h2>
                        <table class="table table-hover table-striped mt-3">
                            <thead>Brand Overview</thead>
                            <tbody>

                                <?php
                                $brands = $db->getBrands($userId);
                                foreach ($brands as $brand) {
                                    echo "<tr>";
                                    echo "<td>" . $brand . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "../parts/footer.php"; ?>
    </div>
</body>

</html>
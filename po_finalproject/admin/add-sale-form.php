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

$pageTitle = "Add Sale";
include_once "../parts/head.php";
?>

<!DOCTYPE html>
<html lang="en">

<body class="bg02">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php include_once "../parts/navigation.php"; ?>
            </div>
        </div>
        <?php include_once "../parts/head.php"; ?>
        <div class="row tm-mt-big justify-content-center">
            <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Sale</h2>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-7 col-md-12">
                        <form action="add-sale.php" method="post" class="tm-edit-product-form">
                            <label for="date" class="col-form-label">Date:</label>
                            <input type="date" name="date" required class="form-control validate mb-3">
                            <div id="productContainer">
                            </div>
                            <button type="button" onclick="addProduct()" class="btn btn-primary mb-3">Add Product</button>

                            <script>
                                function addProduct() {
                                    var container = document.getElementById("productContainer");
                                    var productNumber = container.children.length + 1;

                                    var productFields = document.createElement("div");

                                    productFields.innerHTML = `
                                    <div class="row mb-3">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5">
                <label for="product${productNumber}" class="col-form-label">Product ${productNumber}:</label>
                <select   id="product${productNumber}"  name="product${productNumber}" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" style="padding: 0; padding-left: 10px;text-align:left" required>
                <option value="" disabled selected>Select product</option>
                    <?php
                    $products = $db->getProducts($userId);
                    foreach ($products as $product) {
                        if ($product['units_in_stock'] >= 1) {
                            echo "<option value='" . $product['name'] . "' data-in-stock='" . $product['units_in_stock'] . "'>" . $product['name'] . "</option>";
                        }
                    }
                    ?>

                </select>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5">
                    <label for="quantity${productNumber}" class="col-form-label">Quantity:</label>
                    <input type="number" name="quantity${productNumber}" min="1"  required class="form-control validate">
                </div>
            </div>
            <input type="hidden" name="productContainer[]" value="${productNumber}">`;


                                    container.appendChild(productFields);
                                }
                            </script>


                            <div class="input-group mb-3">
                                <input id="account_id" name="account_id" value="<?php echo $userId ?>" type="hidden" class="form-control validate" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="ml-auto">
                                    <input type="submit" name="submit" value="Add Sale" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "../parts/footer.php"; ?>
    </div>
</body>
</html>
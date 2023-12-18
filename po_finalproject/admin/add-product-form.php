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

$pageTitle = "Add Product";
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
        <div class="row tm-mt-big justify-content-center">
            <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Product</h2>
                        </div>
                    </div>
                    <div class="row mt-4 tm-edit-product-row">
                        <div class="col-xl-12 col-lg-7 col-md-12">
                            <form action="add-product.php" method="post" class="tm-edit-product-form">
                                <div class="input-group mb-3">
                                    <label for="name" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">
                                        Name
                                    </label>
                                    <input id="name" name="name" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="brand" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Brand</label>
                                    <input id="brand" name="brand" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="gender" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Gender</label>
                                    <select id="gender" name="gender" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" style="padding: 0; padding-left: 10px;text-align:left" required>
                                        <option value="" disabled selected>Select gender</option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="type" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Type
                                    </label>
                                    <select id="type" name="type" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" style="padding: 0; padding-left: 10px;text-align:left" required>
                                        <option value="" disabled selected>Select type</option>
                                        <option value="analogue">analogue</option>
                                        <option value="digital">digital</option>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="material" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Material
                                    </label>
                                    <select id="material" name="material" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" style="padding: 0; padding-left: 10px;text-align:left" required>
                                        <option value="" disabled selected>Select material</option>
                                        <option value="Sterling Silver">Sterling Silver</option>
                                        <option value="Stainless Steel">Stainless Steel</option>
                                        <option value="Titanium">Titanium</option>
                                        <option value="Gold">Gold</option>
                                        <option value="Plastic">Plastic</option>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="weight_in_grams" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Weight(g)
                                    </label>
                                    <input id="weight_in_grams" name="weight_in_grams" type="number" step="any" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="description" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 mb-2">Description</label>
                                    <textarea name="description" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" rows="3" required></textarea>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="selling_price" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Selling Price($)
                                    </label>
                                    <input id="selling_price" name="selling_price" type="number" step="0.01" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="units_in_stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Units In Stock
                                    </label>
                                    <input id="units_in_stock" name="units_in_stock" type="number" step="1" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                </div>
                                <div class="input-group mb-3">
                                    <label for="image_url" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">
                                        Image URL
                                    </label>
                                    <input id="image_url" name="image_url" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7">
                                </div>
                                <div class="input-group mb-3">
                                    <input id="account_id" name="account_id" value="<?php echo $userId ?>" type="hidden" class="form-control validate col-xl-9 col-lg-8 col-md-7 col-sm-7" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="ml-auto col-xl-6 col-lg-8 col-md-8 col-sm-7 pl-0">
                                        <input type="submit" name="submit" value="Add" required>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once "../parts/footer.php"; ?>
    </div>
</body>

</html>
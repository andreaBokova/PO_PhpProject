<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login-form.php");
}
$pageTitle = "Edit Product";
include_once "../parts/head.php";
$productId = isset($_GET['product_id']) ? $_GET['product_id'] : null;
$product = $db->getProductById($productId);

?>

<!DOCTYPE html>
<html lang="en">
<body class="bg02">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php
                include_once "../parts/navigation.php";
                ?>
            </div>
        </div>
        <div class="row tm-mt-big">
            <div class="tm-col tm-col-small">
            </div>
            <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Edit Product</h2>
                        </div>
                    </div>
                    <div class="row mt-4 tm-edit-product-row">
                        <div class="col-xl-7 col-lg-7 col-md-12">
                            <form action="edit-product.php" method="post" class="tm-edit-product-form">
                                <div class="input-group mb-3">
                                    <label for="name" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">
                                        Name
                                    </label>
                                    <input value="<?php echo $product['id']; ?>" id="id" name="id" type="hidden" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                                    <input value="<?php echo $product['name']; ?>" id="name" name="name" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                </div>

                                <div class="input-group mb-3">
                                    <label for="brand" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Brand</label>
                                    <input id="brand" name="brand" value="<?php echo $product['brand']; ?>" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>

                                    <div class="input-group mb-3" style="margin-top:20px;">
                                        <label for="gender" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Gender</label>
                                        <select id="gender" name="gender" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" style="padding: 0;margin-top:20px; padding-left: 10px;text-align:left" required>

                                            <?php
                                            $types = ['M', 'F'];

                                            foreach ($types as $option) {
                                                echo '<option value="' . $option . '"';
                                                echo ($product['type'] === $option) ? ' selected' : '';
                                                echo '>' . $option . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="type" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Type
                                        </label>
                                        <select id="type" name="type" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" style="padding: 0; padding-left: 10px;text-align:left" required>

                                            <?php
                                            $types = ['Analogue', 'Digital'];

                                            foreach ($types as $option) {
                                                echo '<option value="' . $option . '"';
                                                echo ($product['type'] === $option) ? ' selected' : '';
                                                echo '>' . $option . '</option>';
                                            }
                                            ?>

                                        </select>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="material" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Material
                                        </label>
                                        <select id="material" name="material" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" style="padding: 0; padding-left: 10px;text-align:left" required>

                                            <?php
                                            $types = ['Sterling Silver', 'Stainless Steel', 'Titanium', 'Gold', 'Plastic'];

                                            foreach ($types as $option) {
                                                echo '<option value="' . $option . '"';
                                                echo ($product['type'] === $option) ? ' selected' : '';
                                                echo '>' . $option . '</option>';
                                            }

                                            ?>

                                        </select>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="weight_in_grams" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Weight(g)
                                        </label>
                                        <input value="<?php echo $product['weight_in_grams']; ?>" type="number" step="any" id="weight_in_grams" name="weight_in_grams" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="description" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 mb-2">Description</label>
                                        <textarea name="description" id="description" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" rows="3" placeholder="Product Description" required><?php echo $product['description']; ?></textarea>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="selling_price" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Selling Price($)
                                        </label>
                                        <input value="<?php echo $product['selling_price']; ?>" id="selling_price" name="selling_price" type="number" step="0.01" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="units_in_stock" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Units In Stock
                                        </label>
                                        <input value="<?php echo $product['units_in_stock']; ?>" type="number" step="1" id="units_in_stock" name="units_in_stock" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="image_url" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Image URL
                                        </label>
                                        <input value="<?php echo $product['image_url']; ?>" id="image_url" name="image_url" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7">
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="ml-auto col-xl-8 col-lg-8 col-md-8 col-sm-7 pl-0">
                                            <button type="submit" class="btn btn-primary">Edit
                                            </button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 mx-auto mb-4">

                        <?php
                        $imageUrl = $product['image_url'];
                        $defaultImageUrl = 'https://cicpac.digitellinc.com/assets/images/image_placeholder.jpg';

                        if ($imageUrl) {

                            echo '<img src="' . $imageUrl . '" alt="Product Image" class="img-fluid mx-auto d-block">';
                        } else {

                            echo '<img src="' . $defaultImageUrl . '" alt="Default Image" class="img-fluid mx-auto d-block">';
                        }
                        ?>

                    </div>
                </div>
            </div>
            <?php include_once "../parts/footer.php"; ?>
        </div>
    </div>
</body>

</html>
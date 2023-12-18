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

$pageTitle = "Account";
include_once "../parts/head.php";

$account = $db->getAccountById($userId);


if (isset($_GET['status'])) {
    if ($_GET['status'] == 1) {
        echo "<br><p style='color: green'>Account edited</p><br>";
    } elseif ($_GET['status'] == 2) {
        echo "<br><p style='color: red'>Account not edited</p><br>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<body class="bg03">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php include_once "../parts/navigation.php"; ?>
            </div>
        </div>
        <div class="row tm-content-row tm-mt-big">
            <div class="tm-col tm-col-small">
            </div>
            <div class="tm-col tm-col-big">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title">Edit Account</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="edit-account.php" method="post" class="tm-signup-form">
                                <div class="form-group">
                                    <label for="email">Account Email</label>
                                    <input value="<?php echo $account['login'] ?>" id="email" name="username" type="email" class="form-control validate" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input placeholder="*****" id="password" name="password" type="password" class="form-control validate" required>
                                </div>
                                <div class="form-group">
                                    <label for="password2">Re-enter Password</label>
                                    <input placeholder="*****" id="password2" name="password2" type="password" class="form-control validate" required>
                                </div>
                                <div class="form-group">
                                    <label for="profile_pic_url">Profile Picture URL</label>
                                    <input value="<?php echo $account['profile_pic_url'] ?>" id="profile_pic_url" name="profile_pic_url" type="text" class="form-control validate">
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <button type="submit" class="btn btn-primary">Edit
                                        </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tm-col tm-col-small">
        <div class="bg-white tm-block">

            <?php
            $profilePicUrl = $account['profile_pic_url'];
            $defaultImageUrl = 'https://cicpac.digitellinc.com/assets/images/image_placeholder.jpg';

            if ($profilePicUrl) {
                // If not null, display the profile picture
                echo '<img src="' . $profilePicUrl . '" alt="Profile Image" class="img-fluid">';
            } else {
                // If null, display the default image
                echo '<img src="' . $defaultImageUrl . '" alt="Default Image" class="img-fluid">';
            }
            ?>

            <br><br>
            <h2 style="font-size:15px;"><?php echo $account['login'] ?></h2>
        </div>
    </div>
    </div>
    <?php include_once "../parts/footer.php"; ?>
    </div>
</body>

</html>
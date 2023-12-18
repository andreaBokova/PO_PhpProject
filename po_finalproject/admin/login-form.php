
<?php
session_start();

$pageTitle = "Login";
include_once "../parts/head.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userID = $db->checkCredentials($username, $password);

    if ($userID) {

        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $userID;

        header("Location: index.php");
        echo "<p style='color: green'>Login successful!</p><br>";
    } else {
        echo "<p style='color: red'>Authentication failed</p><br>";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<body class="bg03">
    <div class="container">
        <div class="row tm-mt-big">
            <div class="col-12 mx-auto tm-login-col">
                <div class="bg-white tm-block">
                    <div class="row">
                        <div class="col-12 text-center">
                            <i class="fas fa-3x fa-tachometer-alt tm-site-icon text-center"></i>
                            <h2 class="tm-block-title mt-3">Welcome</h2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="login-form.php" method="post" class="tm-login-form">
                                <div class="input-group">


                                    <label for="username" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Username</label>
                                    <input name="username" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" id="username" required>
                                </div>
                                <div class="input-group mt-3">
                                    <label for="password" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Password</label>
                                    <input name="password" type="password" class="form-control validate" id="password" required>
                                </div>
                                <div class="input-group mt-3">
                                    <input type="submit" name="login" value="Login" class="btn btn-primary d-inline-block mx-auto">
                                </div>
                                <div class="input-group mt-3">
                                    <p><em>Don't have an account? <a href="sign-up-form.php">Sign up</a></em></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
<?php

$pageTitle = "Sign Up";
include_once "../parts/head.php";

if (isset($_POST['sign_up'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        echo "<p style='color: red'>Passwords do not match.</p><br>";
    } else {
        $result = $db->addUser($username, $password);
        if ($result === true) {
            echo "<p style='color: green'>User created successfully.</p><br>";
            header("Location: login-form.php");
        } else {
            echo "<p style='color: red'>Error: Unable to create user account.</p><br>";
        }
    }
}

if (isset($_GET['error'])) {
    echo "<p style='color: red'>Error</p><br>";
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
                            <h2 class="tm-block-title mt-3">Create Account</h2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="sign-up-form.php" method="post" class="tm-login-form">
                                <div class="input-group">
                                    <label for="username" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Username</label>
                                    <input name="username" type="email" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7" id="username" required>
                                </div>
                                <div class="input-group mt-3">
                                    <label for="password" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Password</label>
                                    <input name="password" type="password" class="form-control validate" id="password" required>
                                </div>
                                <div class="input-group mt-3">
                                    <label for="password2" class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-form-label">Repeat Password</label>
                                    <input name="password2" type="password" class="form-control validate" id="password2" required>
                                </div>
                                <div class="input-group mt-3">
                                    <input type="submit" name="sign_up" value="Sign up" class="btn btn-primary d-inline-block mx-auto">
                                </div>
                                <div class="input-group mt-3">
                                    <p><em>Already have an account? <a href="login-form.php">Login</a></em></p>
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
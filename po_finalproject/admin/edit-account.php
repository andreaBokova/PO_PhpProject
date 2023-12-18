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


include_once "../parts/head.php";




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset(
            $_POST['username'],
            $_POST['password'],
            $_POST['password2'],
            $_POST['profile_pic_url']
        )

    ) {
       
        $result = $db->editAccount($userId, $_POST['username'], $_POST['password'], $_POST['profile_pic_url']);

        if ($result) {

            header("Location: account.php?status=1");
            exit();
        } else {

            header("Location: account.php?status=2");
            exit();
        }
    }
}

header("Location: account.php");
exit();

?>
<?php 
include_once "../lib/DB.php";

use DASHBOARD\lib\DB;

$db = new DB("localhost", 3306, "root", "", "eshop_db");

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo isset($pageTitle) ? $pageTitle : 'Admin Dashboard Template'; ?></title>
    <!--
    Template 2108 Dashboard
	http://www.tooplate.com/view/2108-dashboard
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600">
    <link rel="stylesheet" href="../css/fontawesome.min.css">
    <link rel="stylesheet" href="../css/fullcalendar.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/tooplate.css">
</head>
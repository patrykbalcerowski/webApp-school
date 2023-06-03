<!DOCTYPE HTML>
<html lang="pl-PL">
<head>
    <?php
    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
        header("location: index.php");
        exit();
    }
    require_once "functions.php";
    if(!$hasPermission){
        header("location: dashboard.php");
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Strona główna</title>
</head>

<body class="dashboardbody">
<div class="container">
    <div class="includedFileContainer" id="includedFileContainer">
        <?php include 'dashboardmenu.php'; ?>
    </div>
</div>
</body>
</html>

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
<div class="zamowienia-content">
    <?php
    $orderlist = pg_query($link, "SELECT o.\"orderID\", u.\"firstname\",u.\"lastname\"  FROM orders as o 
                                        join \"users\" as u on o.\"userID\"=u.\"userID\"
                                        join \"Customers\" as c on c.\"companyID\"=u.\"companyID\"
                                        join ordertodetails as otd on otd.\"orderID\"=o.\"orderID\"
                                        join \"orderDetails\"  as od on od.\"orderDetailsID\"=otd.\"orderDetailsID\"  
                                        WHERE c.\"companyID\" = '$companyID';");
    $i=0;
    while ($row = pg_fetch_assoc($orderlist)) {

        echo "<div class='zamowienie'>";
        echo "<h2 style='display: inline-block'>Numer zamówienia: " . $row['orderID'] . " " ."</h2>";
        echo "<p style='display: inline-block;margin-left: 10px'>Zamawiający: " . $row['firstname'] . " ". $row['lastname'] ."</p>";
        echo "</div>";
        $i++;
    }
    if($i==0){
        echo "<div class='zamowienie' style='padding: 20px'>
        Brak zamówień w systemie.
</div>";
    }
    ?>

</div>
</body>
<style>
    .zamowienia-content{
        padding-top: 250px;
        width: 100%;
        height: 80%;

    }
    .zamowienie{
        width: 92%;
        height:auto;
        background-color: #cfcfcf;
        margin: 10px 100px 10px 100px;
    }
</style>
</html>

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
    <style>
        .zamowienia-content {
            padding-top: 50px;
            width: 80%;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .zamowienie {
            width: 30%;
            background-color: #f2f2f2;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .zamowienie h2 {
            margin: 0;
            font-size: 18px;
        }

        .zamowienie p {
            margin: 5px 0;
            font-size: 14px;
        }

        .show-details-button {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            padding: 5px;
            background-color: #4CAF50;
            font-size: medium;
            font-weight: bolder;
            color: white;
        }
    </style>
</head>

<body class="dashboardbody">
<div class="container">
    <div class="includedFileContainer" id="includedFileContainer">
        <?php include 'dashboardmenu.php'; ?>
    </div>
</div>
<div class="zamowienia-content">
    <?php
    $orders = pg_query($link, "SELECT o.\"orderID\", u.\"firstname\",u.\"lastname\", o.\"invoiceID\"  FROM orders as o 
                                        join \"users\" as u on o.\"userID\"=u.\"userID\"
                                        join \"Customers\" as c on c.\"companyID\"=u.\"companyID\"
                                        WHERE c.\"companyID\" = '$companyID';");

    $orderdetails = pg_query($link, "SELECT o.\"orderID\", u.\"firstname\",u.\"lastname\", od.\"unitPrice\", od.\"unitCount\"  FROM orders as o 
                                        join \"users\" as u on o.\"userID\"=u.\"userID\"
                                        join \"Customers\" as c on c.\"companyID\"=u.\"companyID\"
                                        join ordertodetails as otd on otd.\"orderID\"=o.\"orderID\"
                                        join \"orderDetails\"  as od on od.\"orderDetailsID\"=otd.\"orderDetailsID\"  
                                        WHERE c.\"companyID\" = '$companyID';");
    $i=0;
    while ($row = pg_fetch_assoc($orders)) {
        $orderID = $row['orderID'];
        echo "<div class='zamowienie'>";
        echo "<h2>Numer zamówienia: " . $orderID . "</h2>";
        echo "<p>Zamawiający: " . $row['firstname'] . " " . $row['lastname'] . "</p>";
        echo "<p>Numer faktury: " . $row['invoiceID'] . "</p>";
        echo "<a href='szczegoly_zamowienia.php?orderID=$orderID' class='show-details-button'>Zobacz szczegóły</a>";
        echo "</div>";
        $i++;
    }
    if ($i == 0) {
        echo "<div class='zamowienie' style='text-align: center; padding: 20px'>Brak zamówień w systemie.</div>";
    }
    ?>
</div>
</body>
</html>

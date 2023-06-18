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
    $orderID = $_GET['orderID'];
    $order = pg_query($link, "SELECT c.\"companyID\" FROM orders as o 
                                        join \"users\" as u on o.\"userID\"=u.\"userID\"
                                        join \"Customers\" as c on c.\"companyID\"=u.\"companyID\"
                                        WHERE o.\"orderID\" = '$orderID';");
    $companyorderID = pg_fetch_result($order, 0, 0);
    if ($companyID != $companyorderID) {
        header("location: dashboard.php");
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Szczegóły zamówienia</title>
    <style>
        .zamowienia-content {
            padding-top: 250px;
            width: 100%;
            margin: 0 auto 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .zamowienie {
            width: 80%;
            background-color: #f2f2f2;
            padding: 10px;

            margin: 0 auto 10px auto;
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

        .zamowienie table {
            width: 100%;
            border-collapse: collapse;
        }

        .zamowienie th,
        .zamowienie td {
            padding: 5px;
            border: 1px solid #ddd;
            text-align: left;
        }
         .cena-calosc p{
            font-size: 24px;
             font-family: Arial, sans-serif;
             font-weight: bold;
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
    $order = pg_query($link, "SELECT o.\"orderID\", u.\"firstname\", u.\"lastname\", o.\"invoiceID\", o.\"orderDate\" FROM orders as o 
                                        join \"users\" as u on o.\"userID\"=u.\"userID\"
                                        join \"Customers\" as c on c.\"companyID\"=u.\"companyID\"
                                        WHERE o.\"orderID\" = '$orderID';");
    $orderDetails = pg_query($link, "SELECT p.\"productName\", p.\"unitPrize\", od.\"unitCount\" FROM \"orderDetails\" as od
                                             JOIN products as p ON p.\"productCode\" = od.\"productCode\"
                                            join ordertodetails as otd on otd.\"orderDetailsID\"=od.\"orderDetailsID\"
                                            Join orders as o on o.\"orderID\"=otd.\"orderID\"
                                             WHERE o.\"orderID\" = '$orderID';");

    $row = pg_fetch_assoc($order);

    echo "<div class='zamowienie'>";
    echo "<h2>Numer zamówienia: " . $row['orderID'] . "</h2>";
    echo "<p>Zamawiający: " . $row['firstname'] . " " . $row['lastname'] . "</p>";
    echo "<p>Numer faktury: " . $row['invoiceID'] . "</p>";
    echo "<p>Data zamówienia: " . $row['orderDate'] . "</p>";

    echo "<h3>Szczegóły zamówienia:</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<th>Nazwa produktu</th>";
    echo "<th>Cena jednostkowa</th>";
    echo "<th>Ilość</th>";
    echo "</tr>";
    $allCost = 0;
    while ($detailsRow = pg_fetch_assoc($orderDetails)) {
        echo "<tr>";
        echo "<td>" . $detailsRow['productName'] . "</td>";
        echo "<td>" . $detailsRow['unitPrize'] . " zł</td>";
        echo "<td>" . $detailsRow['unitCount'] . "</td>";
        echo "</tr>";
        $allCost += ($detailsRow['unitPrize'] * $detailsRow['unitCount']);
    }

    echo "</table>";
    echo "<div class='cena-calosc'><p>Całkowity koszt: $allCost zł</p></div>";
    echo "</div>";

    ?>
</div>

</body>
</html>


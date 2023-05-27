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
    <div id="includedFileContainer">
    <?php include 'dashboardmenu.php'; ?>
    </div>
    <div class="strona">
        <div class="products">
            <?php
            $i = 0;
            echo "<div class='row'>";
            while ($row = pg_fetch_assoc($products)) {
                if ($i % 4 == 0 && $i != 0) {
                    echo "</div><div class='row'>";
                }
                echo "<div class='product'>";
                echo "<h2 class='product' style='width: 100%'>" . $row['productName'] . "</h2>";
                echo "<div class='zdjecia' style='width: 300px;height: 300px'><img class='produkty' src='pictures/". $row['img'] . "' alt='Product Image'></div>";
                echo "<div class='cena'>
    <p class='product'>Cena: " . $row['unitPrize'] . " zł</p>
    <p class='product'>Dostępna ilość: " . $row['onStock'] . "</p>
</div>";
                echo "<button class='cartinproduct' style='height:45px' onclick='addToCart(" . $row['productCode'] . ")'>
    <i class='fa fa-cart-plus' aria-hidden='true' style='font-size: 30px;vertical-align:18px; margin-bottom: 20px'></i>
</button>";

                echo "</div>";
                $i++;
            }
            echo "</div>";
            ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="scripts.js"></script>

</body>

</html>
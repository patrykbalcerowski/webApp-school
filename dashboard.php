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


    <div class="naglowek">
        <div class="info">
            <p class="naglowekInfo"><i class="fa fa-user-o" aria-hidden="true"
                                       style="font-size:24px;padding-right:5px; color: orangered"></i>
                Handlowiec: <?php echo $staffName, " ", $staffsurName ?></p>
            <p style="display: inline-block; float: left; color: white">|</p>
            <p class="naglowekInfo"><i class="fa fa-address-book-o" aria-hidden="true"
                                       style="font-size:24px;padding-right:5px; color: orangered"></i> Adres
                e-mail: <?php echo $staffemail ?></p>
            <p style="display: inline-block; float: left; color: white">|</p>
            <p class="naglowekInfo"><i class="fa fa-phone"
                                       style="font-size:24px;padding-right:5px;color: orangered"></i>
                Telefon: <?php echo $staffphoneNumber ?></p>
        </div>
        <div class="trescnaglowek">
            <div class="logo">
                <a href="dashboard.php">
                    <img src="pictures/logo.png" alt="Logo" ">
                </a>

            </div>
            <div class="wyszukiwarka">
                <input class="search__input" type="text" placeholder="Wyszukaj"><i class="fa fa-search"
                                                                                   aria-hidden="true"
                                                                                   style="color: orangered"></i>
            </div>
            <div class="koszyk">
                <i class="fa fa-shopping-cart" aria-hidden="true" style="color: orangered;font-size: 45px"></i>
                <div style="display: inline-block">
                    <p><?php echo "$shopCartCount" ?></p>
                    <p style="margin-top: -20px">Koszyk</p>
                </div>
                <div class="koszyk-content">
                    <div class="products-cart">
                        <?php
                        $i = 0;
                        while ($row = pg_fetch_assoc($shopCart)) {
                            echo "<div class='product-cart'>";
                            echo "<img class='produkty-cart' src='pictures/". $row['img'] . "' alt='Product Image'>";
                            echo "<div class='product-details'>";
                            echo "<h2>" . $row['productName'] . "</h2>";
                            echo "<div class='cena-cart'>";
                            echo "<p>Cena: " . $row['unitPrize'] . " zł</p>";
                            echo "<div class='cena-ilosc' >";
                            echo "<p>Ilość: " . $row['unitCount'] . "</p>";
                            echo "<p style='paddingn-right: auto;padding-left: 60px;margin-top: -20px'>Wartość: " . ($row['unitCount'] * $row['unitPrize']) . "</p>";
                            echo "
<button class='cartinproduct' style='height:45px' onclick='removeFromCart(" . $row['cartID'] . ")'>
        <i class='fa fa-times' style='font-size: 24px;margin-left: 50px;margin-top: -20px' aria-hidden='true'></i>
    </button>

";
                            echo "</div>";
                            echo "</div>";

                            echo "</div>";
                            echo "</div>";
                            $i++;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <i class="fa fa-user-circle" aria-hidden="true" style="font-size: 45px;color: orangered; padding-left: 60px;display: inline-block"></i>
            <div class="klient" style="display: inline-block">

                <div style="display: inline-block">
                    <p style="padding-left: 5px; display: inline-block;margin: auto">
                        <?php echo $firstname, " "?></p>
                    <p style="padding-left: 5px; margin: auto; display: block">
                        <?php echo $lastname?></p>
                </div>

            </div>

        </div>
    </div>
    <div class="strona">
        <div class="navbar">
            <ul>
                <li style="display: inline"><a href="#">Strona główna</a></li>
                <li style="display: inline"><a href="#">Zamówienia</a></li>
                <li style="display: inline"><a href="#">Kategorie</a></li>
                <li style="display: inline"><a href="#">Informacje</a></li>
                <li style="display: inline"><a href="#">Pomoc</a></li>
            </ul>
        </div>
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

        <div class="product">

        </div>
    </div>

</div>
    <script>
        function addToCart(productCode) {

            $.ajax({
                url: 'add_to_cart.php',
                type: 'POST',
                data: { productCode: productCode },
                success: function(response) {
                    console.log(response);
                    window.location.reload(true);
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);

                }
            });
        }
    </script><
    <script>
        function removeFromCart(cartID) {
            console.log(cartID);

            $.ajax({
                url: 'remove_from_cart.php',
                type: 'POST',
                data: { cartID: cartID },
                success: function(response) {
                    console.log(response);
                    window.location.reload(true);
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);

                }
            });
        }
    </script>
</body>

</html>
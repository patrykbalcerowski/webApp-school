<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("location: index.php");
    exit();
}
require_once "functions.php";
?>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
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
                <img src="pictures/logo.png" alt="Logo">
            </a>
        </div>
        <div class="wyszukiwarka">
            <input id="search-input" class="search__input" type="text" placeholder="Wyszukaj">
            <i id="search-button" class="fa fa-search" aria-hidden="true" style="color: orangered; cursor: pointer;"></i>
        </div>
        <div class="koszyk">
            <i class="fa fa-shopping-cart" aria-hidden="true" style="color: orangered;font-size: 45px"></i>
            <div style="display: inline-block">
                <p><?php echo "$shopCartCount" ?></p>
                <p style="margin-top: -20px">Koszyk</p>
            </div>
            <div id="message-container"></div>
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
                        echo "<p style='padding-left: 60px;margin-top: -20px'>Wartość: " . ($row['unitCount'] * $row['unitPrize']) . "</p>";
                        echo "<button class='cartinproduct' style='height:45px' onclick='removeFromCart(" . $row['cartID'] . ")'>
                                <i class='fa fa-times' style='font-size: 24px;margin-left: 50px;margin-top: -20px' aria-hidden='true'></i>
                              </button>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        $i++;
                    }
                    ?>
                </div>
                <div class="koszyk-stopka">
                    <button class="koszyk-stopka">Złóż zamówienie</button>
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
<div class="navbar">
    <ul>
        <li style="display: inline"><a href="#">Strona główna</a></li>
        <li style="display: inline"><a href="#">Zamówienia</a></li>
        <li style="display: inline"><a href="#">Kategorie</a></li>
        <li style="display: inline"><a href="#">Informacje</a></li>
        <li style="display: inline"><a href="#">Pomoc</a></li>
    </ul>
</div>

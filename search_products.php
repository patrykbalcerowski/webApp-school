<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = $_GET['query'];
    $query = strtolower($query);
    $query = pg_escape_string($query);

    $sql = "SELECT * FROM products WHERE LOWER(\"productName\") LIKE '%' || $1 || '%'";
    $params = array($query);
    $searchResults = pg_query_params($link, $sql, $params);

    if (pg_num_rows($searchResults)>0) {
        // Display the search results
        while ($row = pg_fetch_assoc($searchResults)) {
            echo "<div class='product'>";
            echo "<h2 class='product' style='width: 100%'>" . $row['productName'] . "</h2>";
            echo "<div class='zdjecia' style='width: 300px;height: 300px'><img class='produkty' src='pictures/" . $row['img'] . "' alt='Product Image'></div>";
            echo "<div class='cena'>
                    <p class='product'>Cena: " . $row['unitPrize'] . " zł</p>
                    <p class='product'>Dostępna ilość: " . $row['onStock'] . "</p>
                </div>";
            echo "<button class='cartinproduct' style='height:45px' onclick='addToCart(" . $row['productCode'] . ")'>
                    <i class='fa fa-cart-plus' aria-hidden='true' style='font-size: 30px;vertical-align:18px; margin-bottom: 20px'></i>
                </button>";
            echo "</div>";
        }
    } else {
        echo "<p style='font-size: 24px;font-family: 'Copperplate Gothic Bold', monospace'>Nie znaleziono wyników</p>";
    }
}
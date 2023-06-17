<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['query'])) {
        // Wyszukiwanie (query)
        $query = $_GET['query'];
        $query = strtolower($query);
        $query = pg_escape_string($query);

        $sql = "SELECT * FROM products WHERE LOWER(\"productName\") LIKE '%' || $1 || '%'";
        $params = array($query);
        $searchResults = pg_query_params($link, $sql, $params);

        if (pg_num_rows($searchResults) > 0) {
            // Wyświetlanie wyników wyszukiwania
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
    } elseif (isset($_GET['categoryId'])) {
        // Kategoria
        $categoryId = $_GET['categoryId'];

        // Wykonaj zapytanie do bazy danych, aby pobrać produkty dla określonej kategorii
        $sql = "SELECT * FROM products as p join \"subsubCategories\" as ssc on p.\"subsubcategoryID\"=ssc.\"subsubcategoryID\" join \"subCategories\" sc on ssc.\"subcategoryID\"=sc.\"subcategoryID\" join \"categories\" as c on c.\"categoryID\"=sc.\"categoryID\"  WHERE c.\"categoryID\" = $1";
        $params = array($categoryId);
        $categoryResults = pg_query_params($link, $sql, $params);

        if (pg_num_rows($categoryResults) > 0) {
            // Wyświetl produkty dla danej kategorii
            while ($row = pg_fetch_assoc($categoryResults)) {
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
            echo "<p style='font-size: 24px;font-family: 'Copperplate Gothic Bold', monospace'>Nie znaleziono produktów w tej kategorii</p>";
        }
    }elseif (isset($_GET['subcategoryId'])) {
        // Kategoria
        $subcategoryId = $_GET['subcategoryId'];

        // Wykonaj zapytanie do bazy danych, aby pobrać produkty dla określonej kategorii
        $sql = "SELECT * FROM products as p join \"subsubCategories\" as ssc on p.\"subsubcategoryID\"=ssc.\"subsubcategoryID\" join \"subCategories\" sc on ssc.\"subcategoryID\"=sc.\"subcategoryID\"  WHERE sc.\"subcategoryID\" = $1";
        $params = array($subcategoryId);
        $categoryResults = pg_query_params($link, $sql, $params);

        if (pg_num_rows($categoryResults) > 0) {
            // Wyświetl produkty dla danej kategorii
            while ($row = pg_fetch_assoc($categoryResults)) {
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
            echo "<p style='font-size: 24px;font-family: 'Copperplate Gothic Bold', monospace'>Nie znaleziono produktów w tej kategorii</p>";
        }
    }
    elseif (isset($_GET['subsubcategoryId'])) {
        // Kategoria
        $subsubcategoryId = $_GET['subsubcategoryId'];

        // Wykonaj zapytanie do bazy danych, aby pobrać produkty dla określonej kategorii
        $sql = "SELECT * FROM products WHERE \"subsubcategoryID\" = $1";
        $params = array($subsubcategoryId);
        $categoryResults = pg_query_params($link, $sql, $params);

        if (pg_num_rows($categoryResults) > 0) {
            // Wyświetl produkty dla danej kategorii
            while ($row = pg_fetch_assoc($categoryResults)) {
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
            echo "<p style='font-size: 24px;font-family: 'Copperplate Gothic Bold', monospace'>Nie znaleziono produktów w tej kategorii</p>";
        }
    }
}

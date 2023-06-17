<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("location: index.php");
    exit();
}
require_once "functions.php";
require_once "config.php";
$result = pg_query($link, "SELECT \"isOwner\",\"isAdministrator\"  FROM users WHERE \"userID\" = '$userID';");
$hasPermission = pg_fetch_result($result,0,0);
$isAdmin = pg_fetch_result($result,0,1);

?>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="scripts.js"></script>
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
            <input id="search-input2" type="submit" hidden />
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
                        $allValue += ($row['unitCount'] * $row['unitPrize']);
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
                    <p style="font-weight: bold;">Suma: <?php echo "$allValue"; ?> zł</p>
                    <button class="koszyk-stopka" onclick="placeOrder()">Złóż zamówienie</button>
                </div>
            </div>
        </div>

        <div class="klient" style="display: inline-block">
            <i class="fa fa-user-circle" aria-hidden="true" style="font-size: 45px;color: orangered; padding-left: 60px;display: inline-block"></i>
            <div style="display: inline-block">
                <p style="padding-left: 5px; display: inline-block;margin: auto">
                    <?php echo $firstname, " "?></p>
                <p style="padding-left: 5px; margin: auto; display: block">
                    <?php echo $lastname?></p>
            </div>
            <div class="klient-content">
                <ul style="padding-left: 10px;flex-direction: column;align-items: center" class="menu">
                    <li style="padding: 0 10px 0 0;"><a href="profile.php">Moje konto</a></li>
                    <?php if ($hasPermission=="t") {
                        echo"<li style='padding: 0 10px 0 0'><a href='manage.php'>Zarządzaj firmą</a></li>";
                    }
                        ?>
                        <?php if ($isAdmin=="t") {
                           echo " <li style='padding: 0 10px 0 0'><a href='admin.php'>Panel administratora</a></li>";
                        }
                             ?>


                    <li style="padding: 0 10px 0 0;"><a href="logout.php">Wyloguj</a></li>
                </ul>
            </div>
        </div>

    </div>
</div>
<div class="navbar">
    <ul>
        <li style="display: inline"><a href="dashboard.php">Strona główna</a></li>
        <li style="display: inline"><a href="orders.php">Zamówienia</a></li>
        <div class="mymenu">
        <li style="display: inline"><a href="#">Kategorie</a></li>
            <div class="categories-menu">
                <ul class="categories-list" style="padding: 0"></ul>
            </div>
        </div>

        <li style="display: inline"><a href="#">Informacje</a></li>
        <li style="display: inline"><a href="#">Pomoc</a></li>
    </ul>
</div>
<style>
    .categories-menu {
        position: relative;
        width: auto;
        height: auto;
    }


    .categories-list {
        display: none;
        width: 200px;
        height: 300px;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #f6f6f6;
        max-height: 500px;


        /* Additional styling properties */
    }
     .mymenu:hover .categories-list{display: block}



    .categories-list li {
        /* Additional styling properties */
    }

    .submenu {
        display: none;
        position: absolute;
        top: 0;
        left: 100%;
        background-color: white;
        /* Additional styling properties */
    }

    .categories-list li:hover .submenu {
        display: block;
    }


    .submenu li {
        /* Additional styling properties */
    }
</style>
<script>
    $(document).ready(function() {
        // Pobieranie kategorii z bazy danych
        $.ajax({
            url: 'get_categories.php',
            type: 'GET',
            success: function(response) {
                var categories = JSON.parse(response);
                displayCategories(categories);
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });

        // Funkcja wyświetlająca kategorie w menu
        function displayCategories(categories) {
            var menu = $('.categories-list');
            categories.forEach(function(category) {
                var li = $('<li style="padding: 10px 0 10px 0; margin: 10px 0 10px 0"></li>').appendTo(menu);
                var a = $('<a style="padding: 0;margin: 0"></a>').attr('href', '#').text(category.name).appendTo(li);
                a.click(function(e) {
                    e.preventDefault();
                    searchProducts1(category.id);
                });

                // Obsługa zdarzenia najechania na kategorię
                li.mouseenter(function() {
                    // Pobieranie podkategorii z bazy danych
                    $.ajax({
                        url: 'get_subcategories.php',
                        type: 'GET',
                        data: { categoryId: category.id },
                        success: function(response) {
                            var subcategories = JSON.parse(response);
                            displaySubcategories(subcategories, li);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error: ' + error);
                        }
                    });
                });

// Obsługa zdarzenia opuszczenia kategorii
                li.mouseleave(function() {
                    li.find('.submenu').hide();

                });
            });
        }

        // Funkcja wyświetlająca podkategorie w menu
        function displaySubcategories(subcategories, parentLi) {
            var submenu = $('<ul style="background-color: #f6f6f6; width: 200px;height:300px;padding: 10px 0 10px 0;margin: 10px 0 10px 0"></ul>').addClass('submenu').appendTo(parentLi);

            subcategories.forEach(function(subcategory) {
                var li = $('<li style="padding: 10px 0px 10px 0px;margin: 10px 0 10px 0"></li>').appendTo(submenu);
                var a = $('<a style="padding: 0; margin: 0"></a>').attr('href', '#').text(subcategory.name).appendTo(li);
                a.click(function(e) {
                    e.preventDefault();
                    searchProducts2(subcategory.id);
                });

                // Obsługa zdarzenia najechania na podkategorię

                a.mouseenter(function() {
                    // Pobieranie podpodkategorii z bazy danych
                    $.ajax({
                        url: 'get_subsubcategories.php',
                        type: 'GET',
                        data: { subcategoryId: subcategory.id },
                        success: function(response) {
                            var subsubcategories = JSON.parse(response);
                            displaySubsubcategories(subsubcategories, li);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error: ' + error);
                        }
                    });
                });

                // Obsługa zdarzenia opuszczenia podkategorii
                li.mouseleave(function() {
                    li.find('.submenu').remove();
                });
            });
        }

        // Funkcja wyświetlająca podpodkategorie w menu
        function displaySubsubcategories(subsubcategories, parentLi) {
            var submenu = $('<ul style="background-color: #f6f6f6;width: 200px;height: 300px ;padding: 10px 0 10px 0;margin: 10px 0 10px 0"></ul>').addClass('submenu').appendTo(parentLi);

            subsubcategories.forEach(function(subsubcategory) {
                var li = $('<li style="padding: 10px 0 10px 0;margin: 10px 0 10px 0"></li>').appendTo(submenu);
                var a = $('<a style="padding: 0; margin: 0"></a>').attr('href', '#').text(subsubcategory.name).appendTo(li);

                // Obsługa zdarzenia kliknięcia na podpodkategorię
                a.click(function(e) {
                    e.preventDefault();
                    searchProducts3(subsubcategory.id);
                });

                // Obsługa zdarzenia opuszczenia podpodkategorii
                li.mouseleave(function() {
                    li.find('.submenu').remove();
                });
            });
        }

        // Funkcja wyszukująca produkty z danej kategorii
        function searchProducts1(categoryId) {
            $.ajax({
                url: 'search_products.php',
                type: 'GET',
                data: { categoryId: categoryId },
                success: function(response) {
                    // Handle the search results
                    var productsContainer = document.querySelector('.products');
                    productsContainer.innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }
        function searchProducts2(subcategoryId) {
            $.ajax({
                url: 'search_products.php',
                type: 'GET',
                data: { subcategoryId: subcategoryId },
                success: function(response) {
                    // Handle the search results
                    var productsContainer = document.querySelector('.products');
                    productsContainer.innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }
        function searchProducts3(subsubcategoryId) {
            $.ajax({
                url: 'search_products.php',
                type: 'GET',
                data: { subsubcategoryId: subsubcategoryId },
                success: function(response) {
                    // Handle the search results
                    var productsContainer = document.querySelector('.products');
                    productsContainer.innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }
    });
</script>


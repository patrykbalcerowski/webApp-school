<?php
require_once "config.php";
session_start();

$productId = $_POST['productCode'];
$userId = $_SESSION['userID'];
$quantity=1;
if(isset($_POST['quantity']) && $_POST['quantity']>0){
    $quantity=$_POST['quantity'];
}


$existingProductQuery = pg_query($link, "SELECT * FROM \"shoppingCart\" WHERE \"productCode\" = '$productId' AND \"userID\" = $userId");
$existingProduct = pg_fetch_assoc($existingProductQuery);

$howManyOnStock = pg_query($link, "SELECT \"onStock\" FROM \"products\" WHERE \"productCode\" = '$productId'");
$onStock = pg_fetch_result($howManyOnStock,0,0);
if ($existingProduct) {
    $newUnitCount = $existingProduct['unitCount'] + $quantity;
    if($onStock<$newUnitCount){
        http_response_code(400);
        echo "Brak tylu na stanie";
        return;
    }
    $updateQuery = pg_query($link, "UPDATE \"shoppingCart\" SET \"unitCount\" = $newUnitCount WHERE \"productCode\" = '$productId' AND \"userID\" = $userId");

    if ($updateQuery) {
        echo 'Dodano do koszyka';
    } else {
        http_response_code(400);
        echo 'Błąd przy dodawaniu do koszyka';
    }
} else {
    if($onStock<$quantity){
        http_response_code(400);
        echo "Brak tylu na stanie";
        return;
    }
    $insertQuery = pg_query($link, "INSERT INTO \"shoppingCart\" (\"productCode\", \"unitCount\", \"userID\") VALUES ('$productId', $quantity, $userId)");

    if ($insertQuery) {
        echo 'Produkt dodany pomyślnie';
    } else {
        http_response_code(400);
        echo 'Błąd przy dodawaniu do koszyka';
    }
}

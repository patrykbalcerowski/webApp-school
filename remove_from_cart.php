<?php
require_once "config.php";
session_start();

$cartID = $_POST['cartID'];
$userId = $_SESSION['userID'];


$existingProductQuery = pg_query($link, "SELECT * FROM \"shoppingCart\" WHERE \"cartID\" = '$cartID';");
$existingProduct = pg_fetch_assoc($existingProductQuery);

if ($existingProduct) {
    $deleteQuery = pg_query($link, "DELETE FROM \"shoppingCart\" WHERE \"cartID\" = '$cartID';");

    if ($deleteQuery) {
        echo 'Product removed from cart successfully';
    } else {
        echo 'Error removing product from cart';
    }
} else {
    echo 'Product not found in cart';
}
?>
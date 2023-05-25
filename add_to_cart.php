<?php
require_once "config.php";
session_start();

$productId = $_POST['productCode'];
$userId = $_SESSION['userID'];


$existingProductQuery = pg_query($link, "SELECT * FROM \"shoppingCart\" WHERE \"productCode\" = '$productId' AND \"userID\" = $userId");
$existingProduct = pg_fetch_assoc($existingProductQuery);

if ($existingProduct) {

    $newUnitCount = $existingProduct['unitCount'] + 1;
    $updateQuery = pg_query($link, "UPDATE \"shoppingCart\" SET \"unitCount\" = $newUnitCount WHERE \"productCode\" = '$productId' AND \"userID\" = $userId");

    if ($updateQuery) {
        echo 'Unit count updated successfully';
    } else {
        echo 'Error updating unit count';
    }
} else {

    $insertQuery = pg_query($link, "INSERT INTO \"shoppingCart\" (\"productCode\", \"unitCount\", \"userID\") VALUES ('$productId', 1, $userId)");

    if ($insertQuery) {
        echo 'Product added to cart successfully';
    } else {
        echo 'Error adding product to cart';
    }
}
?>
<?php
require_once "config.php";

if (!$link){
    echo "Error connection";
}
$userID = $_SESSION['userID'];
$result = pg_query($link, "SELECT \"companyID\",firstname,lastname,\"isOwner\",\"isAdministrator\"  FROM users WHERE \"userID\" = '$userID';");
$companyID = pg_fetch_result($result,0,0);
$firstname = pg_fetch_result($result,0,1);
$lastname = pg_fetch_result($result,0,2);
$hasPermission = pg_fetch_result($result,0,3);
$isAdmin = pg_fetch_result($result,0,4);
$result = pg_query($link, "SELECT \"firstName\", \"lastName\", \"phoneNumber\", \"email\" FROM staff as s JOIN \"Customers\" as c on s.\"staffID\"=c.\"staffID\"  WHERE \"companyID\" = '$companyID';");
$staffName = pg_fetch_result($result,0,0);
$staffsurName = pg_fetch_result($result,0,1);
$staffphoneNumber = pg_fetch_result($result,0,2);
$staffemail = pg_fetch_result($result,0,3);
$shopCart = pg_query($link, "SELECT \"productName\", \"unitPrize\", \"img\", \"unitCount\", \"cartID\" FROM products as p JOIN \"shoppingCart\" as s on p.\"productCode\"=s.\"productCode\" JOIN \"users\" as u on s.\"userID\"=u.\"userID\"  WHERE s.\"userID\" = '$userID';");
$row = pg_query($link, "SELECT COUNT(\"cartID\") as num FROM products as p JOIN \"shoppingCart\" as s on p.\"productCode\"=s.\"productCode\" JOIN \"users\" as u on s.\"userID\"=u.\"userID\"  WHERE s.\"userID\" = '$userID';");
$shopCartCount = pg_fetch_result($row,0,0);
$products = pg_query($link,"SELECT * FROM products;");
$orderlist = pg_query($link, "SELECT *  FROM orders WHERE \"userID\" = '$userID';");




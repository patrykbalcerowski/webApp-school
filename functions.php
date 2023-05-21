<?php
require_once "config.php";

if (!$link){
    echo "Error connection";
}
$userID = $_SESSION['userID'];
$result = pg_query($link, "SELECT \"companyID\",firstname,lastname FROM users WHERE \"userID\" = '$userID';");
$companyID = pg_fetch_result($result,0,0);
$firstname = pg_fetch_result($result,0,1);
$lastname = pg_fetch_result($result,0,2);
$result = pg_query($link, "SELECT \"firstName\", \"lastName\", \"phoneNumber\", \"email\" FROM staff as s JOIN \"Customers\" as c on s.\"staffID\"=c.\"staffID\"  WHERE \"companyID\" = '$companyID';");
$staffName = pg_fetch_result($result,0,0);
$staffsurName = pg_fetch_result($result,0,1);
$staffphoneNumber = pg_fetch_result($result,0,2);
$staffemail = pg_fetch_result($result,0,3);
$result = pg_query($link, "SELECT TOP 30 \"productName\", \"unitPrize\", \"img\" FROM products as p JOIN \"shoppingCart\" as s on p.\"productCode\"=s.\"productCode\" JOIN \"users\" as u on s.\"userID\"=u.\"userID\"  WHERE \"userID\" = '$userID';");
$products = pg_query($link,"SELECT * FROM products LIMIT 30;");

//if(!$result){
//    $response = array('success' => false);
//    $_SESSION['loggedin']=false;
//    echo "Niepoprawne dane";
//    return;
//}
//if(pg_num_rows($result)==0){
//    $response = array('success' => false);
//    $_SESSION['loggedin']=false;
//    echo "Błędna nazwa użytkownika lub ID";
//    return;
//}
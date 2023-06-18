<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
    header("location: index.php");
    exit();
}


require_once "functions.php";

$deliveryType = $_POST['deliveryType'];
$companyName = $_POST['companyName'];
$street = $_POST['street'];
$postalCode = $_POST['postalCode'];
$city = $_POST['city'];
$newAddressCheckbox = $_POST['newAddressCheckbox'];
$shippingAddress = $_POST['shippingAddress'];
$courier = $_POST['courier'];


if ($deliveryType === "customer" && $newAddressCheckbox) {

    $addressQuery = 'INSERT INTO "shippingAddresses" ("Street", "Code","City","companyName")
                     VALUES ($1,$2,$3,$4) RETURNING "addressID"';
    $insertUserParams = array($street, $postalCode, $city, $companyName);
    $addressResult = pg_query_params($link, $addressQuery, $insertUserParams);
    $addressID = pg_fetch_result($addressResult,0,0);
    if ($addressID) {

        $customerToAddressQuery = 'INSERT INTO "CustomerToAddress" ("shippingAddressID", "companyID")
                     VALUES ($1,$2);';
        $insertUserParams = array($addressID, $companyID);
        $customertoadd = pg_query_params($link, $customerToAddressQuery, $insertUserParams);

        $invoiceQuery = 'INSERT INTO "invoice" ("companyID", "price")
                     VALUES ($1,$2) RETURNING "invoiceID"';
        $insertUserParams = array($companyID, 100);
        $invoiceResult = pg_query_params($link, $invoiceQuery, $insertUserParams);
        $invoiceID = pg_fetch_result($invoiceResult,0,0);

        $currentDate = date('Y-m-d');

        $orderQuery = 'INSERT INTO "orders" ("userID", "invoiceID","addressID","orderDate")
                     VALUES ($1,$2,$3,$4) RETURNING "orderID"';
        $insertUserParams = array($userID,$invoiceID,$addressID,$currentDate);
        $orderResult = pg_query_params($link, $orderQuery, $insertUserParams);
        $orderId = pg_fetch_result($orderResult,0,0);

        if ($orderId) {

            $userId = $_SESSION['userID'];
            $shopCartItems = pg_query($link, "SELECT p.\"productCode\", \"unitPrize\", \"img\", \"unitCount\", \"cartID\" FROM products as p JOIN \"shoppingCart\" as s on p.\"productCode\"=s.\"productCode\" JOIN \"users\" as u on s.\"userID\"=u.\"userID\"  WHERE s.\"userID\" = '$userID';");
            if ($shopCartItems && pg_num_rows($shopCartItems) > 0) {

                while ($row = pg_fetch_assoc($shopCartItems)) {
                    $productCode = $row['productCode'];
                    $unitCount = $row['unitCount'];


                    $orderDetailsQuery = "INSERT INTO \"orderDetails\" (\"productCode\", \"unitCount\") 
                                          VALUES ('$productCode','$unitCount')";
                    $orderDetailsResult = pg_query($link, $orderDetailsQuery);

                    $orderDetailsQuery = 'INSERT INTO "orderDetails" ("productCode", "unitCount")
                     VALUES ($1,$2) RETURNING "orderDetailsID"';
                    $insertUserParams = array($productCode, $unitCount);
                    $orderDetailsResult = pg_query_params($link, $orderDetailsQuery, $insertUserParams);
                    $orderDetailsId = pg_fetch_result($orderDetailsResult,0,0);
                    if ($orderDetailsId) {



                        $orderToOrderDetailsQuery = "INSERT INTO ordertodetails (\"orderID\", \"orderDetailsID\") 
                                                     VALUES ('$orderId','$orderDetailsId')";
                        $orderToOrderDetailsResult = pg_query($link, $orderToOrderDetailsQuery);
                        if (!$orderToOrderDetailsResult) {

                            echo "Błąd sszczegółów zamówienia";
                            exit();
                        }
                    } else {

                        echo "Błąd sszczegółów zamówienia.";
                        exit();
                    }
                }


                $clearCartQuery = "DELETE FROM \"shoppingCart\" WHERE \"userID\" = '$userId'";
                $clearCartResult = pg_query($link, $clearCartQuery);
                if (!$clearCartResult) {

                    echo "Błąd dodawania produktów.";
                    exit();
                }



                http_response_code(200);
                echo "Zamówienia złożone poprawnie. Numer: " . $orderId;
            } else {

                echo "Błąd dodawania produktów.";
                exit();
            }
        } else {

            echo "Bład składania zamówienia.";
            exit();
        }
    } else {

        echo "Błąd adresu.";
        exit();
    }
}else if($deliveryType=="customer"){

    $addressQuery = 'INSERT INTO "shippingAddresses" ("Street", "Code","City","companyName")
                     VALUES ($1,$2,$3,$4) RETURNING "addressID"';
    $insertUserParams = array($street, $postalCode, $city, $companyName);
    $addressResult = pg_query_params($link, $addressQuery, $insertUserParams);
    $addressID = pg_fetch_result($addressResult,0,0);
    if ($addressID) {
        $invoiceQuery = 'INSERT INTO "invoice" ("companyID", "price")
                     VALUES ($1,$2) RETURNING "invoiceID"';
        $insertUserParams = array($companyID, 100);
        $invoiceResult = pg_query_params($link, $invoiceQuery, $insertUserParams);
        $invoiceID = pg_fetch_result($invoiceResult,0,0);


        $currentDate = date('Y-m-d');
        $orderQuery = 'INSERT INTO "orders" ("userID", "invoiceID", "addressID", "orderDate")
               VALUES ($1, $2, $3, $4) RETURNING "orderID"';
        $insertUserParams = array($userID, $invoiceID, $addressID, $currentDate);
        $orderResult = pg_query_params($link, $orderQuery, $insertUserParams);
        $orderId = pg_fetch_result($orderResult,0,0);

        if ($orderId) {

            $userId = $_SESSION['userID'];
            $shopCartItems = pg_query($link, "SELECT p.\"productCode\", \"unitPrize\", \"img\", \"unitCount\", \"cartID\" FROM products as p JOIN \"shoppingCart\" as s on p.\"productCode\"=s.\"productCode\" JOIN \"users\" as u on s.\"userID\"=u.\"userID\"  WHERE s.\"userID\" = '$userID';");
            if ($shopCartItems && pg_num_rows($shopCartItems) > 0) {

                while ($row = pg_fetch_assoc($shopCartItems)) {
                    $productCode = $row['productCode'];
                    $unitCount = $row['unitCount'];


                    $orderDetailsQuery = "INSERT INTO \"orderDetails\" (\"productCode\", \"unitCount\") 
                                          VALUES ('$productCode','$unitCount')";
                    $orderDetailsResult = pg_query($link, $orderDetailsQuery);

                    $orderDetailsQuery = 'INSERT INTO "orderDetails" ("productCode", "unitCount")
                     VALUES ($1,$2) RETURNING "orderDetailsID"';
                    $insertUserParams = array($productCode, $unitCount);
                    $orderDetailsResult = pg_query_params($link, $orderDetailsQuery, $insertUserParams);
                    $orderDetailsId = pg_fetch_result($orderDetailsResult,0,0);
                    if ($orderDetailsId) {



                        $orderToOrderDetailsQuery = "INSERT INTO ordertodetails (\"orderID\", \"orderDetailsID\") 
                                                     VALUES ('$orderId','$orderDetailsId')";
                        $orderToOrderDetailsResult = pg_query($link, $orderToOrderDetailsQuery);
                        if (!$orderToOrderDetailsResult) {

                            echo "Błąd sszczegółów zamówienia";
                            exit();
                        }
                    } else {

                        echo "Błąd sszczegółów zamówienia.";
                        exit();
                    }
                }
                $clearCartQuery = "DELETE FROM \"shoppingCart\" WHERE \"userID\" = '$userId'";
                $clearCartResult = pg_query($link, $clearCartQuery);
                if (!$clearCartResult) {

                    echo "Błąd dodawania produktów.";
                    exit();
                }
                http_response_code(200);
                echo "Zamówienia złożone poprawnie. Numer: " . $orderId;
            } else {

                echo "Błąd dodawania produktów.";
                exit();
            }
        } else {

            echo "Bład składania zamówienia.";
            exit();
        }
    } else {

        echo "Błąd adresu.";
        exit();
    }

} else if($deliveryType="company") {
    $addressID = $shippingAddress;
    if ($addressID) {
        $invoiceQuery = 'INSERT INTO "invoice" ("companyID", "price")
                     VALUES ($1,$2) RETURNING "invoiceID"';
        $insertUserParams = array($companyID, 100);
        $invoiceResult = pg_query_params($link, $invoiceQuery, $insertUserParams);
        $invoiceID = pg_fetch_result($invoiceResult,0,0);
        $currentDate = date('Y-m-d');
        $orderQuery = 'INSERT INTO "orders" ("userID", "invoiceID","addressID","orderDate")
                     VALUES ($1,$2,$3,$4) RETURNING "orderID"';
        $insertUserParams = array($userID,$invoiceID,$addressID,$currentDate);
        $orderResult = pg_query_params($link, $orderQuery, $insertUserParams);
        $orderId = pg_fetch_result($orderResult,0,0);

        if ($orderId) {

            $userId = $_SESSION['userID'];
            $shopCartItems = pg_query($link, "SELECT p.\"productCode\", \"unitPrize\", \"img\", \"unitCount\", \"cartID\" FROM products as p JOIN \"shoppingCart\" as s on p.\"productCode\"=s.\"productCode\" JOIN \"users\" as u on s.\"userID\"=u.\"userID\"  WHERE s.\"userID\" = '$userID';");
            if ($shopCartItems && pg_num_rows($shopCartItems) > 0) {

                while ($row = pg_fetch_assoc($shopCartItems)) {
                    $productCode = $row['productCode'];
                    $unitCount = $row['unitCount'];


                    $orderDetailsQuery = "INSERT INTO \"orderDetails\" (\"productCode\", \"unitCount\") 
                                          VALUES ('$productCode','$unitCount')";
                    $orderDetailsResult = pg_query($link, $orderDetailsQuery);

                    $orderDetailsQuery = 'INSERT INTO "orderDetails" ("productCode", "unitCount")
                     VALUES ($1,$2) RETURNING "orderDetailsID"';
                    $insertUserParams = array($productCode, $unitCount);
                    $orderDetailsResult = pg_query_params($link, $orderDetailsQuery, $insertUserParams);
                    $orderDetailsId = pg_fetch_result($orderDetailsResult,0,0);
                    if ($orderDetailsId) {



                        $orderToOrderDetailsQuery = "INSERT INTO ordertodetails (\"orderID\", \"orderDetailsID\") 
                                                     VALUES ('$orderId','$orderDetailsId')";
                        $orderToOrderDetailsResult = pg_query($link, $orderToOrderDetailsQuery);
                        if (!$orderToOrderDetailsResult) {

                            echo "Błąd sszczegółów zamówienia";
                            exit();
                        }
                    } else {

                        echo "Błąd sszczegółów zamówienia.";
                        exit();
                    }
                }

                $clearCartQuery = "DELETE FROM \"shoppingCart\" WHERE \"userID\" = '$userId'";
                $clearCartResult = pg_query($link, $clearCartQuery);
                if (!$clearCartResult) {

                    echo "Błąd dodawania produktów.";
                    exit();
                }

                http_response_code(200);
                echo "Zamówienia złożone poprawnie. Numer: " . $orderId;
            } else {

                echo "Błąd dodawania produktów.";
                exit();
            }
        } else {

            echo "Bład składania zamówienia.";
            exit();
        }
    } else {

        echo "Błąd adresu.";
        exit();
    }

}else{
    exit();
}

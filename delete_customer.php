<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyID = $_POST['companyID'];

    $deleteCustomerQuery = "DELETE FROM \"Customers\" WHERE \"companyID\" = $1";
    $deleteCustomerParams = array($companyID);
    $deleteCustomerResult = pg_query_params($link, $deleteCustomerQuery, $deleteCustomerParams);

    if ($deleteCustomerResult) {
        http_response_code(200);
        echo "Usunięto klienta";
    } else {
        echo "Wystąpił błąd przy usuwaniu klienta";
    }
}
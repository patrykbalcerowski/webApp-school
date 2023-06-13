<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName = $_POST['companyName'];
    $companyNIP = $_POST['companyNIP'];
    $companyStreet = $_POST['companyStreet'];
    $companyPostalCode = $_POST['companyPostalCode'];
    $companyCity = $_POST['companyCity'];
    $salespersonID = $_POST['salespersonID'];

    // Insert the customer into the database
    $insertCustomerQuery = "INSERT INTO \"Customers\" (\"companyName\", \"NIP\", \"street\", \"postalCode\", \"city\", \"staffID\",\"payLimit\")
                            VALUES ($1, $2, $3, $4, $5, $6, $7)";
    $insertCustomerParams = array($companyName, $companyNIP, $companyStreet, $companyPostalCode, $companyCity, $salespersonID, 2000);
    $insertCustomerResult = pg_query_params($link, $insertCustomerQuery, $insertCustomerParams);

    if ($insertCustomerResult) {
        echo "Dodano klienta";
    } else {
        echo "Wystąpił błąd przy dodawaniu klienta";
    }
}


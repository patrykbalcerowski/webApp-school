<?php
require_once "config.php";

// Pobieranie przesłanego parametru categoryId
$categoryId = $_GET['categoryId'];

// Zapytanie do bazy danych w celu pobrania podkategorii dla określonej kategorii
$sql = "SELECT * FROM \"subCategories\" WHERE \"categoryID\" = $categoryId";
$result = pg_query($link, $sql);

$subcategories = array();
while ($row = pg_fetch_assoc($result)) {
    $subcategories[] = array(
        'id' => $row['subcategoryID'],
        'name' => $row['subcategoryName']
    );
}

echo json_encode($subcategories);
?>
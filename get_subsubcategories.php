<?php
require_once "config.php";

// Pobieranie przesłanego parametru categoryId
$subcategoryId = $_GET['subcategoryId'];

// Zapytanie do bazy danych w celu pobrania podkategorii dla określonej kategorii
$sql = "SELECT * FROM \"subsubCategories\" WHERE \"subcategoryID\" = $subcategoryId";
$result = pg_query($link, $sql);

$subcategories = array();
while ($row = pg_fetch_assoc($result)) {
    $subcategories[] = array(
        'id' => $row['subsubcategoryID'],
        'name' => $row['subsubcategoryName']
    );
}

echo json_encode($subcategories);
?>

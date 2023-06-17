<?php
require_once "config.php";

$sql = "SELECT * FROM categories";
$result = pg_query($link, $sql);

$categories = array();
while ($row = pg_fetch_assoc($result)) {
    $categories[] = array(
        'id' => $row['categoryID'],
        'name' => $row['categoryName']
    );
}

echo json_encode($categories);


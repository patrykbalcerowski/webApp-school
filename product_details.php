<!DOCTYPE html>
<html>
<head>
    <?php
    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] === false) {
        header("location: index.php");
        exit();
    }
    require_once "functions.php";
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Szczegóły produktu</title>
</head>
<body class="dashboardbody">
<div class="dashboard-menu">
    <?php include 'dashboardmenu.php'; ?>
</div>
<div class="details-container">
    <div class="product-details-more">
        <?php
        require_once "config.php";

        $productCode = $_GET['productCode'];

        $result = pg_query($link,"SELECT * FROM products WHERE \"productCode\" = $productCode;");

        if (pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            ?>
            <div class="product-image">
                <img class="product-image" src="<?php echo "pictures/".$row['img']; ?>" alt="Product Image">
            </div>
            <div class="product-info">
                <h2  class="product-name"><?php echo $row['productName']; ?></h2>
                <p>Cena: <?php echo $row['unitPrize']." zł"; ?></p>
                <p>Kod produktu: <?php echo $productCode; ?></p>
                <p>W magazynie: <?php echo $row['onStock']; ?></p>
                <p><?php echo $row['description']; ?></p>
            </div>
            <p style="vertical-align: -200px;display: inline-block;font-size: 17px">Ilość</p>
            <input style="vertical-align: -200px; padding: 20px" type="number" name="quantity" id="quantity" min="1" max="<?php echo $row['onStock']; ?>" value="1">
            <button class="add-to-cart">DODAJ DO KOSZYKA</button>
            <?php
        } else {
            echo "<p>Product not found.</p>";
        }
        ?>
    </div>

</div>
<style>
    .details-container{
        display: inline-block;
        height: 70vh;
        width: 90vw;
    }
    .product-image {
        width: 400px;
        height: 400px;
        display: inline-block;
        float: left;
    }

    .product-info {
        display: inline-block;
        width: 400px;
        padding-left: 200px;
        padding-top: 100px;
        float: left;
    }

    .product-details-more {
        display: inline-block;
        width: 100%;
        padding-top: 100px;
    }
    h2.product-name {
        font-family: Arial, sans-serif;
        font-size: 24px;
        font-weight: bold;
        color: #333333;
        text-align: center;
        margin-top: 20px;
        margin-bottom: 10px;
    }
    button.add-to-cart{
        vertical-align: -200px;
        padding: 20px;
        font-weight: bold;
        font-size: 14px;
    }
</style>
</body>
</html>
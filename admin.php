
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
    require_once "config.php";
    if(!$isAdmin){
        header("location: dashboard.php");
    }
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
<div>
    <div class="custom-select" style="width:200px;">
        <p>Wybierz firmę</p>
        <select>
            <?php
            $i=0;
                $customers = pg_query($link,"SELECT * FROM \"Customers\";");
                  while ($customer = pg_fetch_assoc($customers)) {
                    echo "<option value='" . $i. "'>".$customer['companyName']."</option>";
                    $i++;
                  }
            ?>
        </select>
    </div>
    <button class="admin-panel" id="change-password">ZMIEŃ HASŁO UŻYTKOWNIKA</button>
    <button class="admin-panel" id="add-client">DODAJ KLIENTA</button>
    <button class="admin-panel" id="del-client">USUŃ KLIENTA</button>

</div>
<style>
    .admin-panel{
        padding: 12px;
        display: block;
        margin: 0 auto 0 auto;
        font-family: "Copperplate Gothic Bold", monospace;
        font-weight: bolder;
    }
    .custom-select{
        margin: 0 auto 10px auto;
        font-family: "Copperplate Gothic Bold", monospace;
        font-weight: bolder;
    }
     select.custom-select{
         width: 200px;
         height: 80px;
         padding: 12px;

     }
    </style>
</html>
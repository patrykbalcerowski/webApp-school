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
    if ($hasPermission=="f") {
        header("location: dashboard.php");
    }
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Panel administrowania - Właściciel firmy</title>
</head>
<body class="dashboardbody">
<div class="dashboard-menu">
    <?php include 'dashboardmenu.php'; ?>
</div>
<div style="display: flex; flex-direction: row;flex-grow: 2">
    <div style="flex: 2;">
        <div class="login-index" style="height: auto;width: auto; margin: 10px">
            <p style="font-family: 'Copperplate Gothic Bold', monospace;font-size: larger;font-weight: bolder;">Dodaj użytkownika</p>
            <label>Username</label>
            <input type="text" id="username">
            <label>Password</label>
            <input type="password" id="password">
            <div style="width: 200px; display: inline-block">
                <label>Is Owner</label>
                <input type="checkbox" id="isOwner">
            </div>
            <div style="width: 200px;display: inline-block">
                <label>Is Administrator</label>
                <input type="checkbox" id="isAdministrator">
            </div>
            <label>E-mail</label>
            <input type="text" id="email">
            <label>Phone</label>
            <input type="text" id="phone">
            <input type="hidden" id="companyID" value="<?php echo $companyID ?>">
            <label>First Name</label>
            <input type="text" id="firstName">
            <label>Last Name</label>
            <input type="text" id="lastName">
            <button class="admin-panel" style="margin: 0 auto;" id="addUserBtn">DODAJ UŻYTKOWNIKA</button>
        </div>
    </div>

    <div style="flex: 1;">
        <div style="display: flex; flex-direction: column;">
            <div>
                <div class="login-index" style="height: auto; width: auto; margin: 10px">
                    <p style="font-family: 'Copperplate Gothic Bold', monospace;font-size: larger;font-weight: bolder;">Usuń użytkownika</p>
                    <label>Wybierz użytkownika:</label>
                    <select class="custom-select" id="userSelect">
                        <?php
                        $users = pg_query($link,"SELECT \"userID\", \"username\"  FROM users WHERE \"companyID\" = $companyID;");
                        while ($row = pg_fetch_assoc($users)) {
                            echo "<option value='" . $row['userID']. "'>" . $row['username']."</option>";
                        }
                        ?>
                    </select>
                    <button class="admin-panel" style="margin: 0 auto;" id="deleteUserBtn">USUŃ UŻYTKOWNIKA</button>
                </div>
            </div>
        </div>
    </div>
    <div style="flex: 1;">
        <div class="login-index" style="height: auto; width: auto; margin: 10px">
            <p style="font-family: 'Copperplate Gothic Bold', monospace;font-size: larger;font-weight: bolder;">Zmień hasło użytkownika</p>
            <label>Wybierz użytkownika:</label>
            <select class="custom-select" id="userSelect">
                <?php
                $users = pg_query($link,"SELECT \"userID\",\"username\"  FROM users as u join \"Customers\" as c on c.\"companyID\"=u.\"companyID\"
                                           where u.\"companyID\" = '$companyID';");
                while ($row = pg_fetch_assoc($users)) {
                    echo "<option value='" . $row['userID']. "'>" . " Użytkownik: " . $row['username']."</option>";
                }
                ?>
            </select>
            <label>Nowe hasło:</label>
            <input type="password" id="newPassword">
            <label>Powtórz nowe hasło:</label>
            <input type="password" id="confirmPassword">
            <button class="admin-panel" style="margin: 0 auto;" id="changePasswordBtn">ZMIEŃ HASŁO</button>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).on('click', '#addUserBtn', function() {
        var username = $('#username').val();
        var password = $('#password').val();
        var isOwner = $('#isOwner').is(':checked');
        var isAdministrator = $('#isAdministrator').is(':checked');
        var email = $('#email').val();
        var phone = $('#phone').val();
        var companyID = $('#companyID').val();
        var firstName = $('#firstName').val();
        var lastName = $('#lastName').val();

        $.ajax({
            url: 'add_user.php',
            type: 'POST',
            data: {
                username: username,
                password: password,
                isOwner: isOwner,
                isAdministrator: isAdministrator,
                email: email,
                phone: phone,
                companyID: companyID,
                firstName: firstName,
                lastName: lastName
            },
            success: function(response) {
                console.log(response);
                alert("Dodano użytkownika!");
            },
            error: function(response) {
                alert("Taki użytkownik już istnieje!");
            }
        });
    });

    $(document).on('click', '#deleteUserBtn', function() {
        var userID = $('#userSelect').val();

        $.ajax({
            url: 'delete_user.php',
            type: 'POST',
            data: {
                userID: userID
            },
            success: function(response) {
                console.log(response);
                alert("Usunięto użytkownika!");
            },
            error: function(response) {
                alert("Wystąpił błąd podczas usuwania użytkownika!");
            }
        });
    });

    $(document).on('click', '#changePasswordBtn', function() {
        var userID = $('#userSelect').val();
        var newPassword = $('#newPassword').val();
        var confirmPassword = $('#confirmPassword').val();

        $.ajax({
            url: 'change_password.php',
            type: 'POST',
            data: {
                userID: userID,
                newPassword: newPassword,
                confirmPassword: confirmPassword
            },
            success: function(response) {
                console.log(response);
                alert("Hasło zostało zmienione");
            }
        });
    });
</script>

<style>
    .admin-panel {
        padding: 12px;
        display: block;
        font-family: "Copperplate Gothic Bold", monospace;
        font-weight: bolder;

    }
    button{
        padding: 5px;
        background-color: #4CAF50;
        font-size: medium;
        font-weight: bolder;
        color: white;
    }


    .custom-select {
        position: relative;
        font-family: Arial, sans-serif;
        margin: 0 auto 10px auto;
        font-weight: bolder;
        padding: 10px;
    }
</style>
</html>

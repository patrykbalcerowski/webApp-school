<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: dashboard.php");
    exit();
}

require_once "config.php";

if (!$link) {
    echo "Error connection";
} else {
    $login = $_POST['username'];
    $login = substr($login, 0, 15);
    $pass = $_POST['password'];
    $pass = substr($pass, 0, 20);
    $id = $_POST['id'];
    $pass_hash = sha1(md5($pass . $pass));
    $result = pg_query_params($link, "SELECT \"userID\", username, password FROM users WHERE \"companyID\" = $1 AND username = $2", array($id, $login));

    if (!$result) {
        $response = array('success' => false);
        $_SESSION['loggedin'] = false;
        echo "Niepoprawne dane";
        return;
    }

    if (pg_num_rows($result) == 0) {
        $response = array('success' => false);
        $_SESSION['loggedin'] = false;
        echo "Błędna nazwa użytkownika lub ID";
        return;
    }

    $hashedPassword = pg_fetch_result($result, 0, 2);
    if (!password_verify($pass, $hashedPassword)) {
        $response = array('success' => false);
        $_SESSION['loggedin'] = false;
        echo "Niepoprawne hasło";
        return;
    }

    $_SESSION['loggedin'] = true;
    $_SESSION['userID'] = pg_fetch_result($result, 0, 0);

    $response = array('success' => true);
    echo json_encode($response);
}

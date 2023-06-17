<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];



    if ($newPassword === $confirmPassword) {

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);


        $updatePasswordQuery = "UPDATE users SET password = $1 WHERE \"userID\" = $2";
        $updatePasswordParams = array($hashedPassword, $userID);
        $updatePasswordResult = pg_query_params($link, $updatePasswordQuery, $updatePasswordParams);

        if ($updatePasswordResult) {
            http_response_code(200);
            echo "Haslo zostalo zmienione poprawnie";
        } else {
            echo "Wystapil blad";
        }
    } else {
        echo "Wpisane hasło różni się";
    }
}
?>

<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $isOwner = isset($_POST['isOwner']) ? $_POST['isOwner'] : false;
    $isAdministrator = isset($_POST['isAdministrator']) ? $_POST['isAdministrator'] : false;
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $companyID = $_POST['companyID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    // Sprawdzenie istnienia użytkownika o podanym loginie
    $checkUserQuery = 'SELECT COUNT(*) FROM users as u join "Customers" as c on u."companyID"=c."companyID" WHERE c."companyID"= $1 AND username = $2';
    $checkUserParams = array($companyID,$username);
    $checkUserResult = pg_query_params($link, $checkUserQuery, $checkUserParams);

    if ($checkUserResult) {
        $userCount = pg_fetch_result($checkUserResult, 0, 0);

        if ($userCount > 0) {
            http_response_code(400);
            echo "Użytkownik o podanym loginie już istnieje";
            return;
        }

        $insertContactQuery = 'INSERT INTO contacts (email, tel)
                               VALUES ($1, $2)
                               RETURNING "contactID"';
        $insertContactParams = array($email, $phone);
        $insertContactResult = pg_query_params($link, $insertContactQuery, $insertContactParams);

        if ($insertContactResult) {
            $contactID = pg_fetch_result($insertContactResult, 0, 0);

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertUserQuery = 'INSERT INTO users (username, "password", "isOwner", "isAdministrator", "contactID", "companyID", "firstname", lastName)
                                VALUES ($1, $2, $3, $4, $5, $6, $7, $8)';
            $insertUserParams = array($username, $hashedPassword, $isOwner, $isAdministrator, $contactID, $companyID, $firstName, $lastName);
            $insertUserResult = pg_query_params($link, $insertUserQuery, $insertUserParams);

            if ($insertUserResult) {
                http_response_code(200);
                echo "Dodano użytkownika";
            } else {
                echo "Wystąpił błąd przy dodawaniu użytkownika";
            }
        } else {
            echo "Wystąpił błąd przy dodawaniu kontaktu";
        }
    } else {
        echo "Wystąpił błąd przy sprawdzaniu użytkownika";
    }
}


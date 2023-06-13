<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $isOwner = $_POST['isOwner'];
    $isAdministrator = $_POST['isAdministrator'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $companyID = $_POST['companyID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

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
            echo "Dodano użytkownika";
        } else {
            echo "Wystąpił błąd przy dodawaniu użytkownika";
        }
    } else {
        echo "Wystąpił błąd przy dodawaniu kontaktu";
    }
}
?>

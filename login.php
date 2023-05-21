<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to dashboard page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit();
}
require_once "config.php";
if (!$link){
    echo "Error connection";
} else {
    $login = $_POST['username'];
    $login = substr($login, 0, 15);
    $pass = $_POST['password'];
    $pass = substr($pass, 0, 20);
    $id = $_POST['id'];
    $pass_hash = sha1(md5($pass.$pass));
    $result = pg_query($link, "SELECT \"userID\" , username FROM users WHERE \"companyID\" = '$id' AND username='$login' AND password='$pass';");
    if(!$result){
        $response = array('success' => false);
        $_SESSION['loggedin']=false;
        echo "Niepoprawne dane";
        return;
    }
    if(pg_num_rows($result)==0){
        $response = array('success' => false);
        $_SESSION['loggedin']=false;
        echo "Błędna nazwa użytkownika lub ID";
        return;
    }
    $_SESSION['loggedin']=true;
    $_SESSION['userID'] = pg_fetch_result($result,0,0);
    $response = array('success' => true);
    echo json_encode($response);
}
?>
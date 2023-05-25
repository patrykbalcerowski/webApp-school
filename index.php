<!DOCTYPE html>
<html>
<head>
  <title>Logowanie</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="index-body">
<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']===true){
    header("location: dashboard.php");
    exit();
}
?>
<div class="login-logo"  style="display: block">
<p>Hurtownia u Patryka</p>
</div>
<div class="login-index" style="display:block;">
  <h1>Login</h1>
  <form id="login-form" method="POST">
    <label>ID Firmy:</label>
    <input  class="login-index" type="text" name="id" min="1" max="500" id="id" required>
    <label>Nazwa użytkownika:</label>
    <input  class="login-index" type="text" name="username" id="username" required>
    <label>Hasło:</label>
    <input class="login-index" type="password" name="password" id="password" required>
    <button type="submit" name="login">Login</button>
  </form>
  <p>Don't have an account? <a href="register.html">Register here</a></p>
</div>
<script>
  $(document).ready(function() {
    $('#login-form').submit(function(event) {
      event.preventDefault();

      var username = $('#username').val();
      var password = $('#password').val();
      var id = $('#id').val();

      $.ajax({
        type: 'POST',
        url: 'login.php',
        data: { username: username, password: password,id: id },
        dataType: 'json',
        success: function(response) {
          if (response.success) {

            window.location.href = 'dashboard.php';
          } else {

            alert('Invalid username or password.');
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        }
      });
    });
  });
</script>
</body>
</html>
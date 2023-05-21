<!DOCTYPE html>
<html>
<head>
  <title>Logowanie</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
<?php
session_start();
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']===true){
    header("location: dashboard.php");
    exit();
}
?>
<div class="login">
  <h1>Login</h1>
  <form id="login-form" method="POST">
    <label>ID Firmy:</label>
    <input  class="logininput" type="text" name="id" min="1" max="500" id="id" required>
    <label>Username:</label>
    <input  class="logininput" type="text" name="username" id="username" required>
    <label>Password:</label>
    <input class="logininput" type="password" name="password" id="password" required>
    <button type="submit" name="login">Login</button>
  </form>
  <p>Don't have an account? <a href="register.html">Register here</a></p>
</div>
<script>
  $(document).ready(function() {
    $('#login-form').submit(function(event) {
      event.preventDefault(); // Prevent default form submission

      // Get form data
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
            // Login successful
            window.location.href = 'dashboard.php';
          } else {
            // Login failed
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
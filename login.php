<?php
   include "includes/config.php";
   session_start();
   $error='';
   if($_SERVER["REQUEST_METHOD"] == "POST") {
   

      $myusername = mysqli_real_escape_string($mysql, $_POST['username']);
      $mypassword = mysqli_real_escape_string($mysql, $_POST['password']); 

      $sql = "SELECT * FROM users WHERE username = '$myusername' and password = '$mypassword'";

      $result = mysqli_query($mysql,$sql);      
      $row = mysqli_num_rows($result);      
      $count = mysqli_num_rows($result);

      if($count == 1) {
	  
         $_SESSION['login_user'] = $myusername;
         header("location: dashboard.php?page=1");
      } else {
         $error = "Your password is incorrect!";
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="includes/css/style.css">
</head>
<body>
  <div class="login-form-box">
    <p class="login_title">Login</p>
    <form action="" method="post">
      <div class="form-group">
      <input type="text" class="form-control" name="username" id="username" placeholder="Username"><br>
      <input type="password" class="form-control" name="password" id="password" placeholder="Password"><br>
      <p><b><?php echo $error ?></b></p>
      <input type="submit" class="btn btn-primary" name="login_btn" id="login_btn" value="Login">
      </div>
    </form>
  </div>
</body>
</html>
<?php
   include "includes/config.php";
   $error='';
   if($_SERVER["REQUEST_METHOD"] == "POST") {
   

      $smtph = mysqli_real_escape_string($mysql, $_POST['smtph']);
      $smtpu = mysqli_real_escape_string($mysql, $_POST['smtpu']); 
      $smtppw = mysqli_real_escape_string($mysql, $_POST['smtppw']); 

      $sql = "UPDATE users SET smtp_host = '$smtph', smtp_username = '$smtpu', smtp_password = '$smtppw' WHERE username = '$login_session'";
      $result = mysqli_query($mysql,$sql);      
 
      if ($result) {
        if (mysqli_affected_rows($mysql) > 0) {
            $error = "Settings updated successfully!";
        } else {
            $error = "No changes were made.";
        }
    } else {
        $error = "Error while updating settings: " . mysqli_error($mysql);
    }
}

// Fetch the current settings
$sql = "SELECT smtp_host, smtp_username, smtp_password FROM users WHERE username = '$login_session'";
$result = mysqli_query($mysql, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '
            <div class="login-form-box">
            <p class="login_title">SMTP Settings</p>
            <form action="" method="post">
                <div class="form-row">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">SMTP Host:</span>
                        <input type="text" class="form-control" name="smtph" class="form-control custom-btn" value="' . $row["smtp_host"] . '" >
                    </div>
                   <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">SMTP Username:</span>
                        <input type="text" class="form-control" name="smtpu" class="form-control custom-btn" value="' . $row["smtp_username"] . '" >
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">SMTP Password:</span>
                        <input type="password" class="form-control" name="smtppw" class="form-control custom-btn" value="' . $row["smtp_password"] . '" >
                    </div>
                    <div>
                        <p><b>' . $error . '</b></p>
                        <input type="submit" class="btn btn-primary custom-btn" name="login_btn" id="login_btn" value="Update Settings">
                    </div>
                </div>
            </form>
            </div>
            ';
        }
    }
?>
</body>
</html>
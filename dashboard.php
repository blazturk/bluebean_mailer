<?php include "includes/session.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bluebean Mailer - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="includes/css/style.css">
</head>
<body>
<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="dashboard.php?page=1" class="nav-link px-2 link-dark">Mail Sending</a></li>
        <li><a href="dashboard.php?page=2" class="nav-link px-2 link-dark">SMTP Settings</a></li>
        <li><a href="dashboard.php?page=3" class="nav-link px-2 link-dark">Group Settings</a></li>
      </ul>
      <div class="col-md-3 text-end">
        <a href="includes/logout.php"><button type="button" class="btn btn-outline-primary me-2">Logout</button></a>
      </div>
    </header>
        <div class="px-4 py-5 my-5 text-center">
            <?php 
              $PageNum = $_GET['page'];
              if ($PageNum == 2) {
                  include("includes/smtp_settings.php");
              } else {
                include("includes/send.php");
              }
            ?>
        </div>
</body>
</html>
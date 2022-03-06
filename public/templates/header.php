<?php
require_once(dirname(__FILE__)."/../../common.php");
maybe_session_start();
verify_logged_in();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>COMP 5531 Database App</title>
    <link rel="stylesheet" href="css/bootstrap.css">
  </head>

  <body>
    <h1>COMP 5531 Database App</h1>
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">CGA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse me-auto" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Courses</a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="#">Course Edit Page </a> </li>
                <li><a class="dropdown-item" href="#">Course 1 </a></li>
                <li><a class="dropdown-item" href="#">Direct Messages</a></li>
                <li><a class="dropdown-item" href="#">Teams</a></li>
                <li><a class="dropdown-item" href="#">Meetings</a></li> 
                <li><a class="dropdown-item" href="marked_entities.php">Marked Entities</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="account_settings.php">Account Settings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Email</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="instructors_list.php">Instructors</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <?php
    if (isset($_SESSION["error_message"]) && $_SESSION["error_message"]!="") {
        echo $_SESSION["error_message"];
    }

    if (isset($_SESSION["current_user"])) {
        $name = get_users_name();
        echo "<p>You are logged in as {$name}. <a href=\"logout.php\">Logout</a></p>";
    } else {
        echo "<p>You are not logged in. <a href=\"login.php\">Login</a></p>";
    }
    ?>

    <script src="js/bootstrap.bundle.js"></script>
  </body>
</html>
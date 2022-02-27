<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
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
        <div class="collapse navbar-collapse me-auto" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            <a class="nav-link" href="#">Courses</a>
            <a class="nav-link" href="#">Account Settings</a>
            <a class="nav-link" href="#">Email</a>
            <a class="nav-link" href="instructors_list.php">Instructors</a>
          </div>
        </div>
      </div>
    </nav>
    

    <?php
    require_once (dirname(__FILE__)."/../../common.php");

    if(isset($_SESSION["error_message"]) && $_SESSION["error_message"]!="") { echo $_SESSION["error_message"]; }

    maybe_session_start();
    if (isset($_SESSION["current_user"])) {
      echo "<p>You are logged in. <a href=\"logout.php\">Logout</a></p>";
    } else {
      echo "<p>You are not logged in. <a href=\"login.php\">Login</a></p>";
    }
    ?>



    <script src="js/bootstrap.bundle.js"></script>
    

  </body>
</html>
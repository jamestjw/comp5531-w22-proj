<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>COMP 5531 Database App</title>

    <link rel="stylesheet" href="css/style.css" />
  </head>

  <body>
    <h1>COMP 5531 Database App</h1>
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

  </body>
</html>
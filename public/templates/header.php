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

    <link rel="stylesheet" href="css/header.css">

    <title>COMP 5531 Database App</title>
  </head>

  <body>
    <h1>COMP 5531 Database App</h1>
    <ul class="topnav">
      <li class="navelem"><a class="active" href="index.php">Home</a></li>
      <li class="dropdown">
        <button class="dropbtn">Courses</button>
        <div class="dropdown-content">
          <a href="#">Course List</a>
          <a href="#">Marked Entities</a>
          <a href="discussions.php">Discussions</a>
          <a href="meetings.php">Meetings</a>
        </div>
      </li>
      <li class="navelem"><a href="account_settings.php">Account Settings</a></li>
      <li class="navelem"><a href="#email">Email</a></li>
      <li class="navelem"><a href="instructors_list.php">Instructors</a></li>

      <?php
        if (is_logged_in()) {
            $current_role = get_current_role(); ?>
          <li class="dropdown">
            <button class="dropbtn">Change Role</button>
            <div class="dropdown-content">
              <?php
                foreach (current_user_possible_roles() as $role) { ?>
                  <a class="role-option dropdown-item<?php echo(($current_role == $role) ? ' active' : ''); ?>" href="#">
                    <?php echo ucwords($role) ?>
                  </a>
                <?php } ?>
            </div>
          </li>
        <?php
        }
        ?>
    </ul>
    
    <?php
    if (isset($_SESSION["error_message"]) && $_SESSION["error_message"]!="") {
        echo $_SESSION["error_message"];
    }

    if (is_logged_in()) {
        $name = get_users_name();
        echo "<p>You are logged in as {$name}. <a href=\"logout.php\">Logout</a></p>";
    } else {
        echo "<p>You are not logged in. <a href=\"login.php\">Login</a></p>";
    }
    ?>
    

  </body>

  
</html>
<script src = "../js/navbar.js"></script>

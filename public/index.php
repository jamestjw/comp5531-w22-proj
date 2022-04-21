<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<link rel="stylesheet" href="css/index.css">
<?php
require_once "../common.php";

try {
    $latest_announcement = Announcement::getLast();
} catch (PDOException $error) {
    echo  $error->getMessage();
}

$user_role = get_current_role();
?>


<?php include "templates/header.php"; ?>
<div class="announcement">
<?php if (isset($latest_announcement)) {?>
  <h3>Latest Announcement from administrator: </h3> <br>
  <h4> <?php echo $latest_announcement->announcement_text ?></h4>
  <p> Posted: <?php echo $latest_announcement->created_at ?> </p>
<?php } else {?>
<h2>No announcement from admin</h2>
<?php }?>
</div>
<hr>


<h2> Home </h2>

<?php if($user_role == "admin") { ?>
  <ul>
  <li>
    <a href="view_users.php"><strong>List all users</strong></a>
  </li>
</ul>
<?php }?>


<?php include "templates/footer.php"; ?>
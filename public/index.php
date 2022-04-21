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

<?php if (isset($latest_announcement)) {?>
  <h2>Latest Announcement: </h2>
  <h4> <?php echo $latest_announcement->announcement_text ?></h4>
  <p> Posted: <?php echo $latest_announcement->created_at ?> </p>
<?php } else {?>
<h2>No announcement from admin</h2>
<?php }?>

<hr>


<h2> Landing page </h2>

<?php if($user_role == "admin") { ?>
  <ul>
  <li>
    <a href="view_users.php"><strong>View</strong></a> - List all users
  </li>

  <li>
    <a href="course_list.php"><strong>View and Create</strong></a> - View and Add Courses
  </li>
</ul>
<?php } ?>


<?php include "templates/footer.php"; ?>
<?php 
require_once "../common.php"; 

try {
    $latest_notice = Notice::getLast();
    
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>


<?php include "templates/header.php"; ?>

<?php if($latest_notice != null)
{?>
  <h2>Latest Notice: </h2>
  <h4> <?php echo $latest_notice["notice"] ?></h4>
  <h6> Posted: <?php echo $latest_notice["created_at"] ?> </h6>
<?php } else {?>
<h2>No notice from admin</h2>
<?php }?>


<h2> Landing page </h2>

<ul>
  <li>
    <a href="create_user.php"><strong>Create</strong></a> - add a user
  </li>
  <li>
    <a href="view_users.php"><strong>View</strong></a> - list all users
  </li>
  <li>
    <a href="discussions.php"><strong>View</strong></a> - list all discussions
  </li> 


  <!-- Remove this when marked entity files can be correctly linked to marked entities-->
  <li>
    <a href="marked_entity_files.php?marked_entity_id=1"><strong>View</strong></a> - list all marked entity files
  </li>
  <li>
    <a href="course_list.php"><strong>View and Create</strong></a> - View and Add Courses
  </li>
</ul>

<?php include "templates/footer.php"; ?>
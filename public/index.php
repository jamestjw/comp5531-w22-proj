<?php include "templates/header.php"; ?>
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
</ul>

<?php include "templates/footer.php"; ?>
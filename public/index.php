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

  <h3>As the administrator you may: </h3>
  <ul>
    <li>
      <h4>Manage users</h4>
      View all users here: 
      <a href="view_users.php"><strong>All users</strong></a>
    </li>
    <li>
      <h4>Announcements</h4>
      View all system wide announcements
      <br>Create System wide announcements
    </li><br>
    <li>
      <h4>Teams</h4>
      View teams for all lectures 
    </li><br>
    <li>
      <h4>Account settings</h4>
      Change the Admin account settings
    </li><br>
    <li>
      <h4>Emails</h4>
      View your internal emails 
      <br>Send emails internally to other users
    </li><br>
    <li>
      <h4>Instructors</h4>
      View all all Instructors
      <br> Create new Instructors
      <br> Assign instructors to lectures
    </li><br>
    <li>
    <li>
      <h4>Meetings</h4>
      View all meetings created by students
    </li><br>
    <li>
      <h4> Courses, Lectures and Sections</h4>
      View a list of all courses, lectures and sections
      <br>Create courses, lectures and sections
      <br>Delete courses lectures and sections
    </li><br>
    <li>
      <h4>Students</h4>
      Upload student lists to specific sections through the course lecture pages
    </li><br>
    <li>
      <h4>Marked Entities</h4>
      View all marked entities for lectures
    </li><br>
    <li>
      <h4>Discussions</h4>
      View all lecture discussion pages
      <br> Create messages in all lecture discussion pages
      <br> Reply to messages in all lecture discussion pages
      <br> Create polls in lecture discussion pages 
    </li>
    <li>
      <h4>TAs</h4>
      Create TAs and automatically assign them to a course section 
      <br> Assign existing TAs to a course section that has no TA
    </li>
  </ul>

<?php } elseif ($user_role == "instructor") { ?>

  <h3>As an instructor you may: </h3>

  <li>
      <h4>Announcements</h4>
      View all system wide announcements
    </li><br>
    <li>
      <h4>Teams</h4>
      View teams for your lectures 
      <br>Create new teams for your lectures
      <br>Update teams for your lectures
    </li><br>
    <li>
      <h4>Account settings</h4>
      Change your account settings
    </li><br>
    <li>
      <h4>emails</h4>
      View your internal emails 
      <br>Send emails internally to other users
    </li><br>
    <li>
      <h4>Meetings</h4>
      View all meetings created by students in your lectures
    </li><br>
    <li>
      <h4> Courses, Lectures and Sections</h4>
      View the lecture pages for your assigned lectures
    </li><br>
    <li>
      <h4>Students</h4>
      Upload student lists to specific sections through the course lecture pages of the lectures you are assigned to
    </li><br>
    <li>
      <h4>Marked Entities</h4>
      View all marked entities for your lectures
      <br>Create marked entities for your lectures
      <br>Update marked entities for your lectures
      <br>Comment on students uploaded files for your lectures' marked entities
      <br>Create Discussions related to marked entities visible only to TAs and instructors  
      <br>Reply to messages on marked entity Discussions
      <br>Create polls in marked entiy discussion messages
      <br>Comment on marked entity discussion messages from students
    </li><br>
    <li>
      <h4>Discussions</h4>
      View lecture discussion pages for your lectures
      <br>Create messages in your lecture discussion pages
      <br>Reply to messages in your lecture discussion pages
      <br>Create polls in your lecture discussion pages 
      <br>Comment on messages in your lecture discussion pages
    </li>
    <li>
      <h4>TAs</h4>
      Create TAs and automatically assign them to a course sections associated to your lectures
      <br> Assign existing TAs to a course section associated to your lectures that has no TA
    </li>
  </ul>
  <?php } elseif ($user_role == "ta") { ?>
    <h3>As a TA you may: </h3>

<li>
    <h4>Announcements</h4>
    View all system wide announcements
  </li><br>
  <li>
    <h4>Teams</h4>
    View teams for your lectures associated to your assigned sections
  </li><br>
  <li>
    <h4>Account settings</h4>
    Change your account settings
  </li><br>
  <li>
    <h4>Emails</h4>
    View your internal emails 
    <br>Send emails internally to other users
  </li><br>
  <li>
    <h4>Meetings</h4>
    View all meetings created by students in lectures associated with your sections
  </li><br>
  <li>
    <h4> Courses, Lectures and Sections</h4>
    View the lecture pages for lectures associated to your assigned sections
  </li><br>
  <li>
    <h4>Students</h4>
    View all students registered in lectures associated to your course sections
  </li><br>
  <li>
    <h4>Marked Entities</h4>
    View all marked entities for your lectures
    <br>Comment on students uploaded files for your lectures' marked entities
    <br>Create Discussions related to marked entities visible only to TAs and instructors  
    <br>Reply to messages on marked entity Discussions
    <br>Create polls in marked entity discussion messages
    <br>Comment on marked entity discussion messages from students
  </li><br>
  <li>
    <h4>Discussions</h4>
    View lecture discussion pages for your lectures
    <br>Create messages in your lecture discussion pages
    <br>Reply to messages in your lecture discussion pages
    <br>Create polls in your lecture discussion pages 
    <br>Comment on messages in your lecture discussion pages
  </li>
</ul>
  <?php } else { ?>
    <h3>As a student you may: </h3>

<li>
    <h4>Announcements</h4>
    View all system wide announcements
  </li><br>
  <li>
    <h4>Teams</h4>
    View a list of all teams you are a member of
  </li><br>
  <li>
    <h4>Account settings</h4>
    Change your account settings
  </li><br>
  <li>
    <h4>emails</h4>
    View your internal emails 
    <br>Send emails internally to other users
  </li><br>
  <li>
    <h4>Meetings</h4>
    View all meetings created by students in your teams
    <br>Start and end meetings
    <br>Write down meetings minutes while meeting is ongoing 
  </li><br>
  <li>
    <h4> Courses, Lectures and Sections</h4>
    View the lecture pages for lectures whose section you are enrolled in
  </li><br>
  <li>
    <h4>Students</h4>
    View all students registered in lectures associated to course sections you are enrolled in
  </li><br>
  <li>
    <h4>Marked Entities</h4>
    View all marked entities for your lectures associated to course sections you are enrolled in
    <br>Upload files to marked entities
    <br>Create Discussions related to marked entities 
    <br>Reply to messages on marked entity Discussions
    <br>Create polls in marked entity discussion messages
  </li><br>
  <li>
    <h4>Discussions</h4>
    View lecture discussion pages for your lectures
    <br>Create messages in your lecture discussion pages
    <br>Reply to messages in your lecture discussion pages
    <br>Create polls in your lecture discussion pages 
  </li>
</ul>
  <?php } ?>


<?php include "templates/footer.php"; ?>
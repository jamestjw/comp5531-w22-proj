<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<?php if (get_current_role() == "admin") { ?>
<?php

require_once "../modules/models/user.php";
require_once "../common.php";

try {
    $instructors = User::where(array('is_instructor' => '1'));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $course_section = CourseSection::getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $course_assignment = CourseSectionInstructor::/*includes(["user", "course_section"]) ->*/getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

if (isset($_POST['submit'])) {
    $user = new User();
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->is_admin = 0;
    $user->is_instructor = 1;
    $user->password_digest = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $user->save();
        $create_success = true;
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

if (isset($_POST['section_submit']) && CourseSectionInstructor::where(array("section_id" => $_POST['section_selection'], "user_id" => $_POST['instructor_selection'])) == null) {
    $section_instructor = new CourseSectionInstructor();
    $section_instructor->section_id = $_POST['section_selection'];
    $section_instructor->user_id = $_POST['instructor_selection'];
    

    try {
        $section_instructor->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
    header("refresh: 1");
}

?>



<?php
if ($instructors && count($instructors)) { ?>
        <h2>Instructors</h2>

    <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($instructors as $row) { ?>
            <tr>
                <td><?php echo escape($row->id); ?></td>
                <td><?php echo escape($row->first_name); ?></td>
                <td><?php echo escape($row->last_name); ?></td>
                <td><?php echo escape($row->email); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No instructors found.</blockquote>
    <?php }
?> 

<?php if (isset($_POST['submit']) && $create_success) { ?>
    <blockquote><?php echo $_POST['first_name']; ?> successfully added.</blockquote>
    <?php header("refresh: 1")?>
<?php } ?>

<h2>Add instructor</h2>

<form method="post">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <label for="email">Email Address</label>
    <input type="text" name="email" id="email">
    <input type="submit" name="submit" value="Submit">
</form>

<br><br>

<h2>Courses assigned to Instructors</h2>
<?php if ($course_assignment && count($course_assignment)) { ?>
    <table>
            <thead>
                <tr>
                    <th>Course Section</th>
                    <th>Instructor</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($course_assignment as $row) {?>
            <tr>
                <td><?php echo $row->course_section->course_section_name; ?></td>
                <td><?php echo $row->user->get_full_name(); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else{ ?>
    <blockquote>No instructors assigned to courses.</blockquote>
<?php } ?>

<h2>Assign Instructors to courses</h2>
<?php if($course_section && $instructors) { ?>
    <form method="post">
        Instructor: 
        <select Name="instructor_selection" id="instructor_selection">
            <option value="">----Select----</option>
        <?php foreach($instructors as $row) { ?>
            <option value="<?php echo $row->id; ?>">
            <?php echo $row->get_full_name(); ?>
            </option>
        <?php } ?>
        </select>
        
        <!-- TO DO look into having more significant names for course sections? or get course name as well? -->
        Course Section: 
        <select Name="section_selection" id="section_selection">
            <option value="">----Select----</option>
        <?php foreach($course_section as $row) { ?>
            <option value="<?php echo $row->id; ?>">
            <?php echo $row->course_section_name; ?>
            </option>
        <?php } ?>
        </select>

        <input type="submit" name="section_submit" value="Submit">
    </form>
<?php } else if (!$instructors) { ?>
    <blockquote> No instructors in database </blockquote>
<?php } else { ?>
    <blockquote> No course section in database </blockquote>
<?php } ?>

<?php } else {?>
    <h2>You do not have the credentials to view this page.</h2>
<?php }?>
 
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
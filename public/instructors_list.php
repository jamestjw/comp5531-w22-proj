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
    $course_offering = CourseOffering::getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $course_assignment = CourseOfferingInstructor::getAll();
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

if (isset($_POST['offering_submit'])) {
    $offering_instructor = new CourseOfferingInstructor();
    $offering_instructor->offering_id = $_POST['offering_selection'];
    $offering_instructor->user_id = $_POST['instructor_selection'];
    

    try {
        $offering_instructor->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
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
                    <th>Course Offering</th>
                    <th>Instructor</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($course_assignment as $row) { 
            //$inst = User::find_by(array('id' => $row->user_id));
            //$off = CourseOffering::find_by(array('id' => $row->offering_id));

            //$inst_name = $inst.get_full_name();
            //$off_name = $off->course_offering_name;
            ?>
            <tr>
                <td><?php echo $row->course_offering->course_offering_name; ?></td>
                <td><?php echo $row->user->first_name." ".$row->user->last_name; ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else{ ?>
    <blockquote>No instructors assigned to courses.</blockquote>
<?php } ?>

<h2>Assign Instructors to courses</h2>
<?php if($course_offering && $instructors) { ?>
    <form method="post">
        Instructor: 
        <select Name="instructor_selection" id="instructor_selection">
            <option value="">----Select----</option>
        <?php foreach($instructors as $row) { ?>
            <option value="<?php echo $row->id; ?>">
            <?php echo $row->first_name." ".$row->last_name; ?>
            </option>
        <?php } ?>
        </select>
        
        <!-- TO DO look into having more significant names for course offerings? or get course name as well? -->
        Course Offering: 
        <select Name="offering_selection" id="offering_selection">
            <option value="">----Select----</option>
        <?php foreach($course_offering as $row) { ?>
            <option value="<?php echo $row->id; ?>">
            <?php echo $row->course_offering_name; ?>
            </option>
        <?php } ?>
        </select>

        <input type="submit" name="offering_submit" value="Submit">
    </form>
<?php } else if (!$instructors) { ?>
    <blockquote> No instructors in database </blockquote>
<?php } else { ?>
    <blockquote> No course offering in database </blockquote>
<?php } ?>

<?php } else {?>
    <h2>You do not have the credentials to view this page.</h2>
<?php }?>
 
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
<?php
// James Juan Whei Tan - 40161156
// Zachary Jones - 40203969
// Andréanne Chartrand-Beaudry - 29605991
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>
<link rel="stylesheet" href="css/instructors.css">

<?php if (get_current_role() == "admin") { ?>
<?php

require_once "../modules/models/user.php";
require_once "../common.php";

try {
    $instructors = User::where_raw_sql("roles & 2");
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $lecture = Lecture::includes('course')->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $course_assignment = LectureInstructor::includes('user')->includes(['lecture' => 'course'])->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

$assigned_lectures = array_map(fn($a) => $a->lecture, $course_assignment);

$unassigned_lectures = array_udiff($lecture, $assigned_lectures, fn($lec_a, $lec_b) => $lec_a->id <=> $lec_b->id );

if (isset($_POST['submit'])) {
    $user = new User();
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->set_role("instructor");
    $user->password_digest = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $user->save();
        $create_success = true;
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

if (isset($_POST['lecture_submit']) && LectureInstructor::where(array("lecture_id" => $_POST['lecture_selection'], "user_id" => $_POST['instructor_selection'])) == null) {
    $lecture_instructor = new LectureInstructor();
    $lecture_instructor->lecture_id = $_POST['lecture_selection'];
    $lecture_instructor->user_id = $_POST['instructor_selection'];
    

    try {
        $lecture_instructor->save();
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
    header("refresh: 1");
}

if (isset($_POST['delete_association']) && LectureInstructor::find_by(array('lecture_id' => $_POST['lecture_id'], 'user_id' => $_POST['instructor_id'])) != null) {
    LectureInstructor::find_by(array('lecture_id' => $_POST['lecture_id'], 'user_id' => $_POST['instructor_id']))->deleteWhere('user_id', 'lecture_id');
    header("refresh: 1");
}

?>



<?php
if ($instructors && count($instructors)) { ?>
        <h2>Instructors</h2>

    <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email Address</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($instructors as $row) { ?>
            <tr>
                <td><?php echo escape($row->first_name); ?></td>
                <td><?php echo escape($row->last_name); ?></td>
                <td><?php echo escape($row->email); ?></td>
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
    <input type="text" name="first_name" id="first_name" required>
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" required>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>
    <label for="email">Email Address</label>
    <input type="text" name="email" id="email" required>
    <input type="submit" name="submit" value="Submit">
</form>

<br><br>

<h2>Courses assigned to Instructors</h2>
<?php if ($course_assignment && count($course_assignment)) { ?>
    <table>
            <thead>
                <tr>
                    <th>Course Lecture</th>
                    <th>Instructor</th>
                    <th>Created At</th>
                    <th>Delete association</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($course_assignment as $row) {?>
            <tr>
                <td><?php echo $row->lecture->course->course_name." ".$row->lecture->lecture_code; ?></td>
                <td><?php echo $row->user->get_full_name(); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
                <td><form method="post"><input type="hidden" name='lecture_id' value="<?php echo$row->lecture->id; ?>"><input type="submit" name="delete_association" value="delete">
                    <input type="hidden" name='instructor_id' value="<?php echo $row->user->id;?>">
                    </form></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <blockquote>No instructors assigned to courses.</blockquote>
<?php } ?>

<h2>Assign Instructors to courses</h2>
<?php if($lecture && $instructors) { ?>
    <form method="post">
        Instructor: 
        <select Name="instructor_selection" id="instructor_selection" required>
            <option value="" disabled>----Select----</option>
        <?php foreach ($instructors as $row) { ?>
            <option value="<?php echo $row->id; ?>">
            <?php echo $row->get_full_name(); ?>
            </option>
        <?php } ?>
        </select>
        
        <!-- TO DO look into having more significant names for course lectures? or get course name as well? -->
        Course Lecture: 
        <select Name="lecture_selection" id="lecture_selection" required>
            <option value="" disabled>----Select----</option>
        <?php foreach($unassigned_lectures as $row) { ?>
            <option value="<?php echo $row->id; ?>">
            <?php echo $row->course->course_name." ".$row->lecture_code; ?>
            </option>
        <?php } ?>
        </select>

        <input type="submit" name="lecture_submit" value="Submit">
    </form>
<?php } elseif (!$instructors) { ?>
    <blockquote> No instructors in database </blockquote>
<?php } else { ?>
    <blockquote> No course lecture in database </blockquote>
<?php } ?>

<?php } else {?>
    <h2>You do not have the credentials to view this page.</h2>
<?php }?>
 
<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
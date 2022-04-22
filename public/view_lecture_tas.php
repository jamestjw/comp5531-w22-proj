<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php
require_once "../modules/models/user.php";
require_once "../modules/models/section.php";
require_once "../modules/models/section_student.php";
require_once "../common.php";

$lecture_page_id = $_GET['id'];

try {
    $course_sections = Section::includes(["section_ta" => "user", "lecture" => []])->where(array('lecture_id' => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

?> 
<h2>Teaching Assistants</h2>
<?php

foreach ($course_sections as $course_section) {
    echo "<h4>{$course_section->lecture->lecture_code} - {$course_section->section_code}</h4>";

    if ($course_section->section_ta != null) {
        echo "<p>{$course_section->section_ta->user->get_full_name()} - {$course_section->section_ta->user->email}</p>";
    } else {
        echo "<p>No TA assigned yet.</p>";
    }
}

?> 
<h3>Assign existing TA</h3>
<?php
$sections_without_tas = array_filter($course_sections, fn ($s) => $s->section_ta == null);
$existing_tas = User::where_raw_sql("roles & 4");

?>

<form action="sections/assign_existing_ta.php" method="post">
    <div>
        <select name = "section_id" required>
            <option value = "" disabled selected>--Select section--</option>
            <?php foreach ($sections_without_tas as $section):; ?>
                <option value = <?php echo($section->id); ?>><?php echo "{$section->lecture->lecture_code} - {$section->section_code}"; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <select name = "user_id" required>
            <option value = "" disabled selected>--Select TA--</option>
            <?php foreach ($existing_tas as $ta):; ?>
                <option value = <?php echo($ta->id); ?>><?php echo $ta->get_full_name();?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <input type="submit" name="submit" value="Submit">
</form>

<h3>Create new TA</h3>

<form action="sections/create_new_ta.php" method="post">
    <div>
        <select name = "section_id" required>
            <option value = "" disabled selected>--Select section--</option>
            <?php foreach ($sections_without_tas as $section):; ?>
                <option value = <?php echo($section->id); ?>><?php echo "{$section->lecture->lecture_code} - {$section->section_code}"; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <div>
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" required>
        </div>
        <div>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="email">Email Address</label>
            <input type="text" name="email" id="email" required>
        </div>
    </div>
    <input type="submit" name="submit" value="Submit">
</form>
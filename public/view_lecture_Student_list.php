<?php
require_once "../modules/models/user.php";
require_once "../modules/models/section.php";
require_once "../modules/models/section_student.php";
require_once "../common.php";

$lecture_page_id = $_GET['id'];

try {
    $course_lecture = Lecture::includes("course")->where(array('id' => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}


try {
    $course_section_students = SectionStudent::includes(["user", "section"])->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $marked_entities = MarkedEntity::where(array("lecture_id" => $lecture_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $course_sections = Section::includes(["section_students"])->where(array('lecture_id' => $lecture_page_id));

} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $teams = Team::includes(['team_members'])->where(array('lecture_id' => $lecture_page_id));

} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

?>

<?php if(get_current_role() == 'instructor') { ?>
    <h2>Student List </h2>
    <?php
    $course_students = array();

    foreach ($course_sections as $section) {
        $course_section_student = $section->section_students;
        foreach ($course_section_student as $student) {
            array_push($course_students, $student->user);
        }
    }

    if ($course_students && count($course_students)) { ?>
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
        <?php foreach ($course_students as $row) { ?>
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
        <blockquote>No Students found for this course.</blockquote>
    <?php }?>
<?php } ?>
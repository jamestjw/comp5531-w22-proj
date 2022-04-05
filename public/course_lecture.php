<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php
require_once "../modules/models/user.php";
require_once "../modules/models/course_section.php";
require_once "../modules/models/course_section_student.php";
require_once "../common.php";

$course_page_id = $_GET['id'];

try {
    $courseLecture = Course::where(array('id' => $course_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}


try {
    $course_section_students = CourseSectionStudent::includes(["user", "course_section"])->getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

try {
    $marked_entities = MarkedEntity::where(array("course_offering_id" => $course_page_id));
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

?>

<?php include "templates/header.php"; ?>

<?php if(get_current_role() == 'instructor') { ?>
    <h2>Student List </h2>
    <?php
    $student = array();
    foreach($course_section_students as $section_student) {
        if($section_student->course_section->course_id == $course->id) {
            array_push($student, $section_student->user);
        }
            
    }
    if ($student && count($student)) { ?>
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
        <?php foreach ($students as $row) { ?>
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

    <h2>Groups</h2>
    <a href="group_creation.php?id=<?php echo $course_page_id ?>">Create new groups</a> 

<?php } ?>

<h2>Marked entities</h2>
<?php
if ($marked_entities && count($marked_entities)) { ?>
        <div class="container">
            <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Group work</th>
                            <th scope="col">Due at</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php foreach ($marked_entities as $row) { ?>
                    <tr>
                        <td scope="row"><?php echo escape($row->id); ?></td>
                        <td><a href="marked_entity.php?id=<?php echo $row->id; ?>"><?php echo escape($row->title); ?></a></td>
                        <td><?php echo escape($row->is_group_work); ?></td>
                        <td><?php echo escape($row->due_at); ?></td>
                        <td><?php echo escape($row->created_at);  ?> </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <blockquote>No marked entities found.</blockquote>
    <?php }
?> 


<div class="container">
    <h2>Add a marked entity for this lecture</h2>

    <form method="post" action="create_marked_entity.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control">
        </div>

        <!-- TO DO pass info of course lecture to marked entity creation-->

        <div class="form-group">
            <input type = "hidden" name = "course_offering_id" id="course_offering_id" value ="<?php echo $course_page_id ?>" />
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"  rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="is_group_work">Group Work</label>
            <input name="is_group_work" type="checkbox" value="true" class="form-check-input" />
            <input name="is_group_work" type="hidden" value="false" />
        </div>

        <div class="form-group">
            <!-- TODO: Figure out how to support multiple file uploads -->
            <label for="marked_entity_file">File</label>
            <input type="file" class="form-control-file" id="marked_entity_file" name="marked_entity_file">
        </div>

        <div class="form-group">
            <label for="due_at">Due at</label>
            <input type="text" id="due_at" name="due_at">
            
            <script>
                
            $("#due_at").datepicker({
                dateFormat: "yy-mm-dd"
            });
            </script>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

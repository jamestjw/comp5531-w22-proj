<?php
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>
<?php include "templates/header.php"; ?>

<link rel="stylesheet" href="css/marked_entity.css">

<?php
require_once "../modules/models/marked_entity.php";
require_once "../common.php";

ensure_logged_in();
?>

<?php
// TODO: Students can only see this if they are enrolled this course
if (isset($_GET["id"]) && ($marked_entity = MarkedEntity::find_by_id($_GET["id"]))) {
    if (get_current_role() == "student") {
        // For group works, load discussions that were started by team members
        if ($marked_entity->is_team_work) {
            // Get discussions started by teammates
            $discussions = Discussion::from_raw_sql("
            SELECT * FROM discussions
            WHERE user_id = {$_SESSION['current_user_id']} OR user_id IN (
                SELECT
                    user_id FROM
                team_members JOIN teams
                ON teams.id = team_members.team_id
                AND teams.lecture_id = {$marked_entity->lecture_id}
                AND teams.id IN (
                    SELECT team_id FROM team_members
                    where user_id = {$_SESSION['current_user_id']}
                )
            ) AND discussable_id = {$marked_entity->id}
            AND discussable_type = 'MarkedEntity';
            ");
        } else {
            // Get discussions started by yourself
            $discussions = Discussion::where([
                "user_id" => $_SESSION['current_user_id'],
                "discussable_id" => $marked_entity->id,
                "discussable_type" => "MarkedEntity"
            ]);
        }
    } else {
        $discussions = $marked_entity->discussions;
    }
    ?>
    <div class="container">
        <h4>Marked Entity - <?php echo $marked_entity->title ?></h4>
        <p><?php echo $marked_entity->description ?></p>
        <hr>

    <div class="info" >
        <h5>Instructor Files:</h5>
        <div class="innerBox">
                <?php
                if (!empty($files = $marked_entity->files)) {
                    foreach ($files as $file) { ?>
                        <p>
                            <a href='<?php echo "download.php?file_id={$file->file_id}" ?>'><?php echo $file->file_filename; ?></a>
                            <?php if (get_current_role() == "instructor") { ?>
                                <div id="deleteInstructorFileForm">
                                    <form method="post" action="marked_entities/delete_instructor_file.php">
                                        <input type="hidden" id="file_id" name="file_id" value="<?php echo $file->file_id; ?>">
                                        <input type="hidden" id="marked_entity_id" name="marked_entity_id" value="<?php echo $marked_entity->id; ?>">
                                        <input type="submit" name="submit" value="Delete">
                                    </form>
                                </div>
                            <?php } ?>
                        </p>
                    <?php }
                } else {
                 echo "<blockquote>No files.</blockquote>";
                 } ?>
            </div>
    </div>
    <br>

    <div class="info">
        <h5>Created at: </h5>
        <div class="innerBox">
            <?php echo $marked_entity->created_at; ?>
        </div>
    </div>

    <br> 

    <div class="info">
        <h5>Due at:</h5>
        <div class="innerBox">
            <?php echo $marked_entity->due_at; ?> 
        </div>
    </div>
                
    <br>

    <div class="info">
        <h5>Student Files:</h5>
        <div class="innerBox">
            <blockquote><a href="marked_entity_files.php?marked_entity_id=<?php echo $marked_entity->id ?>">View here</a></blockquote>
        </div>
    </div>

    <br>
    <div class="info">
        <h5>Discussion:</h5>
        <div class="innerBox" >
            <br>
            <?php
                if ($_SESSION['current_user']->is_student()) {
                    $discussable_id = $marked_entity->id;
                    $discussable_type = "MarkedEntity";
                }
                include "discussion_list.php"
                ?><br>
        </div>
    </div>
    </div>
    
    <?php if (get_current_role() == "instructor") { ?>
        <br>
        <button type="button" id="displayUpdateForm">Toggle update</button>
        <!-- Update form -->
        <div style="display: none" id="updateForm">
            <form method="post" style="display:table" action="marked_entities/update.php" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<?php echo $marked_entity->id; ?>">
                <ul>
                    <li >
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo escape($marked_entity->title)?>">
                    </li>
                    <li>
                        <label for="description">Description:</label>
                        <input type="text" id="description" name="description" value="<?php echo escape($marked_entity->description)?>">
                    </li>
                    <li>
                        <label for="due_at">Due at:</label>
                        <input type="due_at" id="due_at" name="due_at" value="<?php echo escape($marked_entity->due_at)?>"></input>
                    </li>
                    <li class="form-group">
                        <!-- TODO: Figure out how to support multiple file uploads -->
                        <label for="marked_entity_file">Add new file</label>
                        <input type="file" class="form-control-file" id="marked_entity_file" name="marked_entity_file">
                    </li>
                </ul>
                <input type="submit" name="submit" value="Submit">
            </form>
        </div>

        <!-- Delete form -->
        <div id="deleteForm">
        <form method="post" style="display:table" action="marked_entities/delete.php">
            <input type="hidden" id="id" name="id" value="<?php echo $marked_entity->id; ?>">
            <input type="submit" name="submit" value="Delete">
        </form>
        </div>
    <?php } ?>
<?php
}
?>

<script src = "../js/marked_entity.js"></script>

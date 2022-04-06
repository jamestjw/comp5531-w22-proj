<?php include "templates/header.php"; ?>

<?php

require_once "../modules/models/marked_entity.php";
require_once "../common.php";

// TODO: Require ensure_logged_in.php instead
ensure_logged_in();

try {
    // TODO: Get different marked entities based on roles
    $result = MarkedEntity::getAll();
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}
?>

<?php
if ($result && count($result)) { ?>
        <div class="container">
            <h2>Marked entities</h2>

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
                <?php foreach ($result as $row) { ?>
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
    <h2>Add a marked entity</h2>

    <form method="post" action="create_marked_entity.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"  rows="3"></textarea>
        </div>
    
        <!-- TODO: Figure out a better way of getting the course lecture ID -->
        <div class="form-group">
            <label for="lecture_id">Course Lecture</label>

            <select name="lecture_id" id="lecture_id">
                <option value="" disabled selected>Select course lecture</option>
                <!-- TODO: Get only the course lectures that the instructor teaches -->
                <?php foreach (Lecture::getAll() as $lecture):;?>
                    <option value = <?php echo($lecture->id);?>><?php echo($lecture->lecture_name);?></option>
                <?php endforeach;?>
            </select>
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

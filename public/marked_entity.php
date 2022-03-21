<?php include "templates/header.php"; ?>

<?php
require_once "../modules/models/marked_entity.php";
require_once "../common.php";

ensure_logged_in();
?>

<?php

if (isset($_GET["id"]) && ($marked_entity = MarkedEntity::find_by_id($_GET["id"]))) {
    $discussions = $marked_entity->discussions; ?>
    <div class="container">
        <h4>Marked Entity - <?php echo $marked_entity->title ?></h4>
        <p><?php echo $marked_entity->description ?></p>

        <h5>Instructor Files:</h5>

        <?php
        if (!empty($files = $marked_entity->files)) {
            foreach ($files as $file) { ?>
                <p><a href='<?php echo "download.php?file_id={$file->file_id}" ?>'><?php echo $file->file_filename; ?></a></p>
            <?php }
        } else {
            echo "<p> No files.</p>";
        } ?>

        <h5>Due at: <?php echo $marked_entity->due_at; ?> </h5>

        <h5>Student Files:</h5>
        <p>TODO: Require course teams to find relevant files</p>

        <h5>Discussion:</h5>
        <?php
            $discussable_id =  $marked_entity->id;
    $discussable_type = "MarkedEntity";
    include "discussion_list.php"
        ?>

        <h5>Progress:</h5>
        <p>TODO</p>
        
    </div>
<?php
}
?>
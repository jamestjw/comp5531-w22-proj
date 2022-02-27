<?php include "templates/header.php"; ?>

<?php
require_once "../modules/models/marked_entity_file.php";
require_once "../modules/models/attachment.php";
require_once "../common.php";

ensure_logged_in();

$marked_entity_id = $_GET["marked_entity_id"] ?? $_POST["marked_entity_id"] ?? null;

if (isset($_POST['submit'])) {
    $marked_entity_file = new MarkedEntityFile();
    $marked_entity_file->title = $_POST['title'];
    $marked_entity_file->description = $_POST['description'];
    $marked_entity_file->entity_id = $_POST['marked_entity_id'];
    $marked_entity_file->user_id = $_POST['user_id'];

    // Uploads folder needs to be created in the public/ directory
    // TODO: Make this more convenient
    $target_dir = "uploads/";
    $file_id = basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_id;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_size = $_FILES["file"]["size"];

    $attachment = new Attachment();
    $attachment->file_content_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $attachment->file_size = $_FILES["file"]["size"];
    $attachment->file_filename = basename($_FILES["file"]["name"]);
    $attachment->file_id = $file_id;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["file"]["name"])). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
    }

    try {
        $marked_entity_file->save();
        $attachment->attachable_id = $marked_entity_file->id;
        $attachment->attachable_type = 'MarkedEntityFile';
        $attachment->save();

        // header("Location: marked_entity_files.php?marked_entity_id=".$marked_entity_file->entity_id);
    }  catch(PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

// TODO: Ensure that marked entity ID is valid.
if (isset($marked_entity_id)) {
    $files = MarkedEntityFile::where(array("entity_id"=>isset($marked_entity_id))); 
?>
    <div>Files for marked entity ID: <?php echo $marked_entity_id; ?> </div>
    <div>Number of files: <?php echo count($files); ?> </div>

    <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>File name</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($files as $row) { ?>
            <tr>
                <td><?php echo escape($row->id); ?></td>
                <td><?php echo escape($row->title); ?></td>
                <td><?php echo escape($row->description); ?></td>
                <td><?php echo $row->attachment->file_filename; ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div>Add new file:</div>
    <form method="post" action="marked_entity_files.php" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" name="title" id="title">
        <label for="description">Description</label>
        <input type="text" name="description" id="description">
        <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION["current_user_id"]; ?>">
        <input type="hidden" id="marked_entity_id" name="marked_entity_id" value="<?php echo $marked_entity_id; ?>">
        <input type="file" name="file" id="file">
        <input type="submit" name="submit" value="Submit">
    </form>
<?php
} else {
    echo "Invalid marked entity ID.";
}
?>

<?php include "templates/footer.php"; ?>

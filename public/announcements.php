<?php
require_once "../common.php";

ensure_logged_in();

try {
    $announcement = Announcement::getAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['submit']) && get_current_role() == "admin") {
    $announcement = new Announcement();
    $announcement->announcement_text = $_POST['announcement_text'];

    try {
        $announcement->save();
        header("refresh: 1");
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
} elseif (isset($_POST['submit']) && get_current_role() != "admin") {
    echo "Invalid access, you are not an admin.";
}
?>

<?php include "templates/header.php"; ?>

<?php
if ($announcement && count($announcement)) { ?>
        

        <?php foreach ($announcement as $row) { ?>

            <h3>Announcement #<?php echo escape($row->id); ?></h3>
            <h5>Posted on <?php echo escape($row->created_at);?></h5>
            <p><?php echo escape($row->announcement_text)?></p>

        <?php } ?>

<?php } else { ?>
    <blockquote>No Announcements</blockquote>
<?php }?>

<?php if (get_current_role() == "admin") { ?>
<h2>New announcement</h2>

<form method="post">
    <label for="announcement_text">Announcement</label>
    <textarea name="announcement_text" cols="40" rows="5"></textarea>
    <input type="submit" name="submit" value="Submit">
</form>
<?php } ?>

<?php include "templates/footer.php"; ?>
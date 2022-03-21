<?php 
require_once "../common.php"; 

ensure_logged_in();

try {
    $notices = Notice::getAll();
    
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['submit'])) {
    $notice = new Notice();
    $notice->notice = $_POST['notice_text'];

    try {
        $notice->save();
        $create_success = true;
    } catch (PDOException $error) {
        echo "<br>" . $error->getMessage();
    }
}

?>

<?php include "templates/header.php"; ?>

<?php
if ($notices && count($notices)) { ?>
        

        <?php foreach ($notices as $row) { ?>

<<<<<<< HEAD
            <h3>Announcement #<?php echo escape($row->id); ?></h3>
=======
            <h3>Notice #<?php echo escape($row->id); ?></h3>
>>>>>>> f4e6c87204f67e516a4215fc15b7663e141c870c
            <h5>Posted on <?php echo escape($row->created_at);?></h5>
            <p><?php echo escape($row->notice)?></p>

        <?php } ?>

<?php } else { ?>
<<<<<<< HEAD
    <blockquote>No Announcements</blockquote>
<?php }?>

<?php if (get_current_role() == "admin"){ ?>
<h2>New announcements</h2>

<form method="post">
    <label for="notice_text">Announcement</label>
    <textarea name="notice_text" cols="40" rows="5"></textarea>
    <input type="submit" name="submit" value="Submit">
</form>
<?php }?>
=======
    <blockquote>No Notices</blockquote>
<?php }?>

<!-- ADD IF STATEMENT TO SHOW NEW NOTICE ONLY IF USER == ADMNIN -->
<h2>New notice</h2>

<form method="post">
    <label for="notice_text">Notice</label>
    <textarea name="notice_text" cols="40" rows="5"></textarea>
    <input type="submit" name="submit" value="Submit">
</form>
>>>>>>> f4e6c87204f67e516a4215fc15b7663e141c870c

<?php include "templates/footer.php"; ?>
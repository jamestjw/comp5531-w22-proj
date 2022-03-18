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

            <h3>Notice #<?php echo escape($row->id); ?></h3>
            <h5>Posted on <?php echo escape($row->created_at);?></h5>
            <p><?php echo escape($row->notice)?></p>

        <?php } ?>

<?php } else { ?>
    <blockquote>No Notices</blockquote>
<?php }?>

<!-- ADD IF STATEMENT TO SHOW NEW NOTICE ONLY IF USER == ADMNIN -->
<h2>New notice</h2>

<form method="post">
    <label for="notice_text">Notice</label>
    <textarea name="notice_text" cols="40" rows="5"></textarea>
    <input type="submit" name="submit" value="Submit">
</form>

<?php include "templates/footer.php"; ?>
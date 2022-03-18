<?php 
require_once "../common.php"; 

ensure_logged_in();

try {
    $notices = Notice::getAll();
    
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
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

<?php include "templates/footer.php"; ?>
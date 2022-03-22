<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php

require "../modules/models/discussion.php";

try {
    $discussions = Discussion::where(["discussable_id"=>null, "discussable_type"=>null]);
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

?>

<?php include "templates/header.php"; ?>

<h2>Discussions</h2>
<?php include "discussion_list.php" ?>

<p><a href="index.php">Back to home</a></p>

<?php include "templates/footer.php"; ?>
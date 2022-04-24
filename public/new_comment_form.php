<?php
// James Juan Whei Tan - 40161156
?>
<form method="post" action="create_comment.php" class="newCommentForm">
    <input type="text" name="content" id="content">
    <input type="hidden" id="commentable_id" name="commentable_id" value="<?php echo $commentable_id; ?>">
    <input type="hidden" id="commentable_type" name="commentable_type" value="<?php echo $commentable_type; ?>">
    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ?>">
    <input type="submit" name="submit" value="Add comment">
</form>
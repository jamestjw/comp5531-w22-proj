<?php
// James Juan Whei Tan - 40161156
?>
<?php require_once(dirname(__FILE__)."/../modules/ensure_logged_in.php"); ?>

<?php
    $VALID_ROLES = array("admin", "instructor", "ta", "student");
    // do any authentication first, then add POST variable to session

    if (isset($_POST['role']) && in_array(isset($_POST['role']), $VALID_ROLES)) {
        $_SESSION['current_role'] = $_POST['role'];
    } else {
        http_response_code(422);
        echo "Invalid role";
    }
?>
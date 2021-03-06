<?php
// Andréanne Chartrand-Beaudry - 29605991
// Christopher Almeida Neves - 27521979
// James Juan Whei Tan - 40161156
?>
<?php

/**
  * Escapes HTML for output
  *
  */

include_once dirname(__FILE__)."/modules/models/loggedin.php";

date_default_timezone_set('America/Montreal');

function escape($html)
{
    return htmlspecialchars($html ?? '', ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function maybe_session_start()
{
    if (!isset($_SESSION)) {
        session_start();
    }
}

function ensure_logged_in()
{
    maybe_session_start();

    if (!isset($_SESSION["current_user"])) {
        header("Location: login.php");
    } elseif (!$_SESSION["current_user"]->is_password_changed) {
        $_SESSION["alert_message"] = "Please change your password.";
        header("Location: account_settings.php");
    }
}

function array_last($arr)
{
    return $arr[array_key_last($arr)] ?? null;
}

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function get_users_name()
{
    // Gets the first name of the logged in user
    if (isset($_SESSION)) {
        $fname = $_SESSION["current_user"];
        return $fname->first_name;
    } else {
        return '';
    }
}

function get_users_id()
{
    // Gets the user id of the logged in user
    if (isset($_SESSION) && array_key_exists("current_user", $_SESSION)) {
        return $_SESSION["current_user"]->id;
    }
    return '';
}

function verify_logged_in()
{
    if (isset($_SESSION) && array_key_exists("AuthKey", $_SESSION)) { // Session is started and authentication key exists
        // Get the auth token from session
        $authtoken = $_SESSION["AuthKey"];
        if (!Loggedin::find_by_user_digest($authtoken)) { // If user does not exist or authentication key does not match an entry in the loggedin table, logout
            $_SESSION = array();
            setcookie(session_name(), '', time() - 30000, '/'); // time() - 30000 changes the expiration time of the cookie to some point in the past so that the cookie expires
            session_destroy();
        }
    } else { // No session details exist or authentication key doesnt exist
        return false;// Should this return none or null?
    }
}

// Get role of current user
function get_current_role()
{
    maybe_session_start();

    return $_SESSION['current_role'] ?? null;
}

function set_current_role($r)
{
    $_SESSION['current_role'] = $r;
}

function current_user_possible_roles()
{
    maybe_session_start();

    return $_SESSION['current_user']->get_possible_roles();
}

function is_logged_in()
{
    maybe_session_start();

    return isset($_SESSION["current_user"]);
}

function isAssoc(array $arr): bool
{
    if (array() === $arr) {
        return false;
    }
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function set_error_and_go_back(string $err) {
    $_SESSION["alert_message"] = $err;
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    die();
}

function html_tick_cross(bool $b)
{
    return $b ? "&#10003;" : "&#10008;";
}
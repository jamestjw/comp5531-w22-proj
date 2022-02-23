<?php

/**
  * Escapes HTML for output
  *
  */

function escape($html) {
  return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function maybe_session_start() {
  if(!isset($_SESSION))
  {
      session_start();
  }
}

function ensure_logged_in() {
  maybe_session_start();

  if (!isset($_SESSION["current_user"])) {
    header("Location: login.php");
  }
}

function array_last($arr) {
  return $arr[array_key_last($arr)] ?? null;
}

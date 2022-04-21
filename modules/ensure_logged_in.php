<?php

require_once(dirname(__FILE__)."/../common.php");

if(!isset($in_settings)){
    $in_settings = false;
}

if(!$in_settings){
    ensure_logged_in();
}


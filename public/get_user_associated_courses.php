<?php
$user_id = get_user_id();
$user_role = get_current_role();

switch ($user_role) {
    case 'admin':
        # code...
        break;

    case 'instructor':
        # code...
        break;

    case 'TA':
        # code...
        break;
    case 'student':
        # code...
        break;
        
    default:
        # code...
        break;
}
?>
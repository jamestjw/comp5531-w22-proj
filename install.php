<?php

/**
 * Open a connection via PDO to create a
 * new database and table with structure.
 *
 */

require "config.php";
require "modules/models/user.php";

// Load database tables
try {
    $connection = new PDO("mysql:host=$host", $username, $password, $options);
    $database_name = $environment."_cga";
    $sql = file_get_contents("data/init.sql");
    $sql = sprintf($sql, $database_name);
    $connection->exec($sql);

    echo "Database and table users created successfully.";
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

// Insert admin user
$admin = new User();
$admin->first_name = "admin";
$admin->last_name = "user";
$admin->email = "admin@concordia.ca";
$admin->is_admin = 1;
$admin->is_instructor = 0;
$admin->password_digest = password_hash('root', PASSWORD_DEFAULT);
$admin->save();

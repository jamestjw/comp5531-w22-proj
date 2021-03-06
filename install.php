<?php
// Andréanne Chartrand-Beaudry - 29605991
// James Juan Whei Tan - 40161156
// Christopher Almeida Neves - 27521979
?>
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
    $sql = file_get_contents("data/init.sql");
    $sql = sprintf($sql, $dbname);
    $connection->exec($sql);

    echo "Database and table created successfully.<br>";

    $sql = file_get_contents("data/seed.sql");
    $sql = sprintf($sql, $dbname);
    $connection->exec($sql);

} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}

// Insert admin user
$admin = new User();
$admin->first_name = "admin";
$admin->last_name = "user";
$admin->email = "admin";
$admin->set_role("admin");
$admin->password_digest = password_hash('admin', PASSWORD_DEFAULT);
$admin->password_digest = password_hash('admin', PASSWORD_DEFAULT);
$admin->save();

include 'seed.php';

if (!file_exists('public/uploads')) {
    mkdir('public/uploads', 0755, true);
}

echo "Database seeded successfully. <br><br>Please wait as we redirect you.<br>";
header("refresh:4;url=public/login.php");
exit();

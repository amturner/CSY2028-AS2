<?php
// Database Connection Configuration
$dbHost = 'localhost';      // The Hostname/IP Address of the database server.
$dbPort = '3306';           // The port the database server is listening on.
$dbName = 'job';            // The name of the database/schema to execute commands on.
$charset = 'utf8';          // The charset to use.
$username = 'student';      // The username of user that can access the database.
$password = 'student';      // The user's password.

try 
{
    // Attempt to establish a connection with the database server and store it in a variable called $pdo.
    $pdo = new PDO('mysql:host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . $dbName . ';charset=' . $charset, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    // Display an error message in the browser.
    echo 'Connection to the database has failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}
?>
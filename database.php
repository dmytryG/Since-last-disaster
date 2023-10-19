<?php
function getClient(): mysqli {
    include_once 'env.php';
    // MySQL server configuration
    // Create a MySQLi connection
    $mysqli = new mysqli(ENV::$host, ENV::$username, ENV::$password, ENV::$database, ENV::$port);

    // Check the connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    return $mysqli;
}
?>

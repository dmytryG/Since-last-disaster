<?php
include_once 'database.php';
$db = getClient();
$query = 'CREATE TABLE IF NOT EXISTS events (id INTEGER PRIMARY KEY, name TEXT, description TEXT NULL, lastEvent DATETIME, updateToken TEXT)';
$db->exec($query);
printf('Successfully updated');
?>
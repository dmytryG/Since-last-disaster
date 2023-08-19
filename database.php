<?php
function getClient(): SQLite3 {
    // Create or open an SQLite database file
    $db = new SQLite3('mydatabase.db');

    // Create a table if it doesn't exist
    $query = 'CREATE TABLE IF NOT EXISTS events (id INTEGER PRIMARY KEY, name TEXT, description TEXT NULL, lastEvent DATETIME, updateToken TEXT)';
    $db->exec($query);

    return $db;
}
?>
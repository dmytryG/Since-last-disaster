<?php
include_once 'database.php';
$db = getClient();
$updateToken = $_GET["updateToken"];

// Validate the event name
if (empty($updateToken)) {
    $errors[] = "updateToken is required.";
}
if (empty($errors)) {
    $lastEvent = date("c");
    $token = bin2hex(random_bytes(32));
    $insertQuery = "UPDATE events SET lastEvent = '$lastEvent' WHERE updateToken = '$updateToken'";
    $db->exec($insertQuery);
    $id = $db->lastInsertRowID();
}
$data = array(
    'errors' => $errors,
);
$jsonData = json_encode($data);
header('Content-Type: application/json');
echo $jsonData;
?>

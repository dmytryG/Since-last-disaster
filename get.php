<?php
include_once 'database.php';
$db = getClient();
$id = $_GET["id"];
$isRequested = true;
// Validate the event name
if (empty($id)) {
    $errors[] = "Id have to be specified";
}
if (empty($errors)) {
    $selectQuery = "SELECT * FROM events WHERE id = $id";
    $result = $db->query($selectQuery);
    $row = $result->fetchArray(SQLITE3_ASSOC);
    if (!$row) {
        $errors[] = "Event not found";
        return;
    }
    $name = $row['name'];
    $description = $row['description'];
    $lastEvent = $row['lastEvent'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php
      if ($isRequested && empty($errors)) {
          $title = 'Since '.$name;
      } else {
          $title = 'Event not found';
      }
    ?>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta http-equiv="refresh" content="30">
    <title><?php echo $title?></title>
</head>
<body>
<div class="centered-div">
    <div class="inner-column home-link">
        <a href="/index.php" class="name">Since last disaster 🦖☄️</a>
    </div>
<?php
// Display validation errors if any
if ($isRequested) {
    if (!empty($errors)) {
        ?>
        <div class="inner-column">
            <?php
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
            ?>
        </div>
    <?php
    } else {
        ?>
    <div id='timeSinceLastEvent' class="timer"></div>
        <div class="name">Since <?php echo $name?></div>
        <div><?php echo $description?></div>
        <script>
            function updateElapsedTime() {
                var lastEventDate = new Date("<?php echo $lastEvent; ?>");
                var currentDate = new Date();

                // Convert dates to UTC
                var lastEventDateUTC = new Date(lastEventDate.getUTCFullYear(), lastEventDate.getUTCMonth(), lastEventDate.getUTCDate(), lastEventDate.getUTCHours(), lastEventDate.getUTCMinutes(), lastEventDate.getUTCSeconds());
                var currentDateUTC = new Date(currentDate.getUTCFullYear(), currentDate.getUTCMonth(), currentDate.getUTCDate(), currentDate.getUTCHours(), currentDate.getUTCMinutes(), currentDate.getUTCSeconds());

                var timeDifference = currentDateUTC - lastEventDateUTC;
                console.log('lastEventDate', lastEventDate, 'currentDate', currentDate, 'timeDifference', timeDifference, '$lastEvent', "<?php echo $lastEvent; ?>")
                console.log('lastEventDateUTC', lastEventDateUTC, 'currentDateUTC', currentDateUTC)

                var days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                var displayElement = document.querySelector('#timeSinceLastEvent');
                displayElement.innerHTML = days + " days, " + hours + " hours, " + minutes + " minutes, " + seconds + " seconds";
            }

            // Update the time every second (1000 milliseconds)
            updateElapsedTime()
            setInterval(updateElapsedTime, 1000);
        </script>
        <?php
    }
}
?>
</div>
</body>
</html>

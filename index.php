<?php
include_once 'database.php';
$db = getClient();
$isRequested = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isRequested = true;
    // Retrieve POST parameters
    $eventName = $_POST["event_name"];
    $eventDescription = $_POST["event_description"]; // Optional field

    // Validate the event name
    if (empty($eventName)) {
        $errors[] = "Event name is required.";
    }
    if (empty($errors)) {
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone("UTC"));
        $lastEvent = $date->format(\DateTime::ISO8601);
//        $lastEvent = date("c");
        $token = bin2hex(random_bytes(32));
        $insertQuery = "INSERT INTO events (name, description, lastEvent, updateToken) VALUES ('$eventName', '$eventDescription', '$lastEvent', '$token')";
        $db->exec($insertQuery);
        $id = $db->lastInsertRowID();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add your event</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="centered-div">
    <div class="inner-column timer">
        Since last disaster 🦖☄️
    </div>
    <div class="inner-column">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="centered-form">
            <label for="event_name">What could go wrong:</label><br>
            <input type="text" name="event_name" required class="form-field"><br>
            <div id="changingText" class="random-text-container">Apocalypses 🦖☄️</div>
            <br />

            <label for="event_description">Maybe a bit of details idk:</label><br>
            <textarea name="event_description" class="form-field textarea-setting"></textarea><br>

            <input type="submit" value="Submit" class="button">
        </form>
    </div>
    <div class="inner-column">
        <?php
        // Display validation errors if any
        if ($isRequested) {
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='message-block'>$error</div>";
                }
                echo "</ul></div>";
            } else {
                $page = '/get.php?id='.$id;
                $link = $_SERVER['HTTP_HOST'].$page;
                ?>
                <div class='message-block'>You can access your 'Time since <?php echo $eventName?>' via <a href='<?php echo $page?>' target="_blank"><?php echo $link?></a> and update it with token <a id="copyMe" style="cursor: pointer;"><?php echo $token?></a></div>
                <?php
            }
        }
        ?>
    </div>
    <script>
        let isReady = false;
        function setCopyMe() {
            console.log('Started setting copyMe event listener')
            if (isReady) return
            console.log('Setting copyMe event listener')
            const copyMe = document.getElementById('copyMe');
            if (!copyMe) {
                isReady = false
                return;
            }

            copyMe.addEventListener('click', () => {
                const textToCopy = copyMe.textContent;

                // Create a temporary textarea to copy text to clipboard
                const tempTextarea = document.createElement('textarea');
                tempTextarea.value = textToCopy;
                document.body.appendChild(tempTextarea);
                tempTextarea.select();
                document.execCommand('copy');
                document.body.removeChild(tempTextarea);
            });
            isReady = true;
        }
        setInterval(setCopyMe, 1000); // Change text every 3 seconds
    </script>
    <script>
        const textArray = [
            "Main database fall 😳",
            "Client stuck in the toilet 🚽",
            "DDOS attack 🤬",
            "Employee killed boss (in a game of course) 🔪",
            "One Piece episode came out 🏴‍☠️",
            "Apocalypses 🦖☄️",
        ];
        const changingText = document.getElementById('changingText');
        function changeRandomText() {
            const randomIndex = Math.floor(Math.random() * textArray.length);
            changingText.textContent = textArray[randomIndex];
            changingText.style.animation = 'none';
            changingText.offsetHeight; /* trigger reflow */
            changingText.style.animation = null;
        }
        setInterval(changeRandomText, 4000); // Change text every 3 seconds
    </script>
</div>
</body>
</html>

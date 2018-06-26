
<?php
    //Connection Info
    $con = mysqli_connect("localhost", "joshsutt", "joshsutt", "joshsutt_db");

    //Check for valid connection
    if (!$con) {
        echo "Failed to connect: " . mysqli_connect_error();
    }
?>
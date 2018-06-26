<?php

    require 'dbconnect.php';

    if (isset($_POST['search'])){
        $search = ($_POST['search']);
    }

    $search = mysqli_real_escape_string($con, $search);

    $q = "SELECT email FROM Charity_Users WHERE email LIKE '%" . $search . "%' AND active='1'";

    if ($stmt = $con->prepare($q)) {
        $stmt->execute();
        $stmt->store_result();
        $num_of_rows = $stmt->num_rows;
        $stmt->bind_result($email);

        while ($stmt->fetch()) {
            echo "<option val='$email'>$email</option>";
        }
        /* free results */
        $stmt->free_result();
    }
    $con->close();


?>
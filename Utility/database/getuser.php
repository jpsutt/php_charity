<?php

    require 'dbconnect.php';

    if (isset($_POST['edituser'])){
        $search = ($_POST['edituser']);
    }

    $search = mysqli_real_escape_string($con, $search);


    $q = "SELECT id, email, password, points,stored_resources, collection_level, collection_speed, last_gather_time, active, is_admin, admin_level FROM Charity_Users WHERE email = '" . $search . "'";

    $edituser;

    if ($stmt = $con->prepare($q)) {
        $stmt->execute();
        $stmt->store_result();
        $num_of_rows = $stmt->num_rows;
        $stmt->bind_result($id, $email, $password, $points, $resources, $collectlvl, $collectspeed, $lastgathertime, $active, $isAdmin, $adminlvl);

        while ($stmt->fetch()) {

            $edituser->id = $id;
            $edituser->email = $email;
            $edituser->password = $password;
            $edituser->points = $points;
            $edituser->resources = $resources;
            $edituser->collectlvl = $collectlvl;
            $edituser->active = $active;
            $edituser->isAdmin = $isAdmin;
            $edituser->adminlvl = $adminlvl;

        }
        /* free results */
        $stmt->free_result();
        echo json_encode($edituser);
    }



?>
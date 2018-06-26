
<?php

    if( !isset( $_SESSION) ) {
        session_start();
    }


    function __autoload($className) {
        include './Utility/classes/'. $className . '.php';
    }

    include './Utility/head.php';
    require './Utility/database/dbconnect.php';





    function checkCode($c) {           //function to check randomly generated code.
        global $validCode, $con;

        $emailcode = $c;

        $q = "SELECT * FROM Charity_Users WHERE Activation_Code = '" . $emailcode . "'";
        $result = mysqli_query($con, $q);

        if (mysqli_num_rows($result) == 1) {
            // Activate user in the database.
            $statement = $con->prepare("UPDATE Charity_Users SET Active = '1' WHERE Activation_Code=?");
            //bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
            $statement->bind_param('s', $c);
            $statement->execute();

            $validCode = True;
        }
        else {
            $validCode = False;
        }

        return $validCode;

    }

    function login($e, $p) {
        global $con;
        $dirty = array( $e, $p,);
        foreach ($dirty as $item) {
            $item = mysqli_real_escape_string($con, $item);
        }
        $q = "SELECT * FROM Charity_Users WHERE email = '" . $e . "' AND password = '" . $p."' AND active = '1'";
        $result = mysqli_query($con, $q);
        if (mysqli_num_rows($result) == 1) {
            return True;
        }
        else {
            return False;
        }
    }

    function initiateUser($e) {
        global $con;
        $q = "SELECT id, email, points,stored_resources, collection_level, collection_speed, last_gather_time, is_admin, admin_level FROM Charity_Users WHERE email = '" . $e . "' AND active = '1'";
        if ($stmt = $con->prepare($q)) {
            if ($q === FALSE) {
                die($con->error);
            }
            $stmt->execute();
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            $stmt->bind_result($id, $email, $points, $resources, $collectlvl, $collectspeed, $lastgathertime, $isAdmin, $adminlvl);

            while ($stmt->fetch()) {

                $_SESSION['user']->id = $id;
                $_SESSION['user']->email = $email;
                $_SESSION['user']->points = $points;
                $_SESSION['user']->resources = $resources;
                $_SESSION['user']->collectlvl = $collectlvl;
                $_SESSION['user']->collectspeed = $collectspeed;
                $_SESSION['user']->lastgathertime = date_create($lastgathertime);

                if ($isAdmin == 1) {
                    $_SESSION['user']->adminlvl = $adminlvl;
                }

            }
            /* free results */
            $stmt->free_result();

            $q = "SELECT resource_amount, gather_time FROM Charity_Collection_Level WHERE Level = ?";
            if ($stmt = $con->prepare($q)) {
                $stmt->bind_param("i", $_SESSION['user']->collectlvl);
                $stmt->execute();
                $stmt->store_result();
                $num_of_rows = $stmt->num_rows;
                $stmt->bind_result($amount, $time);
                while ($stmt->fetch()) {
                    $_SESSION['user']->gatherAmount = $amount;
                    $_SESSION['user']->gatherTime = $time;
                }
                /* free results */
                $stmt->free_result();


                /* close statement */
                $stmt->close();
            }
        }
        $con->close();
    }

    function getCollectionInfo() {
        global $con;
        $q = "SELECT resource_amount, gather_time FROM Charity_Collection_Level WHERE Level = ?";
        if ($stmt = $con->prepare($q)) {
            $stmt->bind_param("i", $_SESSION['user']->collectlvl);
            $stmt->execute();
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            $stmt->bind_result($amount, $time);
            while ($stmt->fetch()) {
                $_SESSION['user']->gatherAmount = $amount;
                $_SESSION['user']->gatherTime = $time;
            }
            /* free results */
            $stmt->free_result();
            /* close statement */
            $stmt->close();
        }
        $con->close();
    }


    /* STOP FUNCTIONS */

    if (isset($_SESSION["loggedIn"])) {

        if (isset($_SESSION["user"])) {

            if (is_null($_SESSION['user']->id)){
                initiateUser($_SESSION['logemail']);
            }

            initiateUser($_SESSION['user']->email);

            echo <<< HERE
                <ul id='btntomainv' class='actions'>
                    <li><a id='btntomain' name='btntomain' type='submit' class='button alt' >Continue</a></li>
                </ul>
            <script type='text/javascript'>
                $(function() {
                    $('#btntomain').trigger('click');
                });
            </script>
HERE;
        }

    }

    else if (isset($_GET['code'])) {             //User came to this page from registration email.
        $code = trim($_GET["code"]);

        if (checkCode($code)) {             //Activation Successful
            $h2 = "Activation Successful";
            $p = "Welcome to <strong>Charity</strong>";
            $optional = <<< HERE
                <ul class="actions">
                    <li><a href="index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
        }
        else {                              // If Bad Code
            $h2 = "Ducking Fammit!";
            $p = "An error has occurred.";
            $optional = <<< HERE
                <ul class="actions">
                    <li><a href="index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
        }
        include './sections/messageblue.php';
    }
    else if (isset($_POST['Login'])) {              ///User came from login.
        $email = trim($_POST["loginemail"]);
        $pwd = trim($_POST["loginpwrd"]);
        if (login($email, $pwd)) {                  //Successful login.

            $_SESSION['logemail'] = $email;
            $_SESSION['logpwd'] = $pwd;

            initiateUser($email);

            $_SESSION['loggedIn'] = true;

            $h2 = "Welcome " . $_SESSION['user']->email . ".";
            $p = "Aw yiss.";
            $optional = <<< HERE
                <ul class="actions">
                    <li><a id="welcomeback" name="welcomeback" type="submit" class="button alt">Continue</a></li>
                </ul>
HERE;
        }
        else {                                      //Unsuccessful login.
            $h2 = "Typing with hotdogs?";
            $p = "Email and/or password incorrect.";
            $optional = <<< HERE
                <ul class="actions">
                    <li><a href="index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
        }
        include './sections/messageblue.php';
    }

    else {      //User came from the unknown. Log in you silly user.

        header("Location: index.php"); /* Redirect browser */
        exit();
        include './Utility/loginpopup.php';
        include './Utility/registerpopup.php';
        include './Utility/forgotpasswordpopup.php';
    }

    include './Utility/navbar.php';
    include 'hud.php';
    include './Utility/footer.php';


?>

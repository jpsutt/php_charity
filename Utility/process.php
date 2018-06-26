<?php

        if( !isset( $_SESSION ) ) {
            session_start();
        }


        //include 'head.php';
        require 'database/dbconnect.php';

        $css = <<< HERE
                <link rel="stylesheet" href="../assets/css/main.css" />
                <link rel="stylesheet" href="../assets/css/jquery.countdown.css" />
                <!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
                <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
                <!-- Scripts -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
                <script src="../assets/js/skel.min.js"></script>
                <script src="../assets/js/util.js"></script>
                <!--[if lte IE 8]><script src="../assets/js/ie/respond.min.js"></script><![endif]-->
                <script src="../assets/js/main.js"></script>
                <script src="../assets/js/jquery.plugin.js"></script>
                <script src="../assets/js/jquery.countdown.js"></script>
                <script src="../assets/js/customjquery.js"></script>
HERE;


    function forgotPassword($e) {
        require "mail/mail.class.php";
        global $con, $css;
        $email = $e;
        $q = "SELECT * FROM Charity_Users WHERE email = '" . $email . "'";
        $result = mysqli_query($con, $q);
        if (mysqli_num_rows($result) == 1) {
            $q = "SELECT password FROM Charity_Users WHERE email = ?";
            if ($stmt = $con->prepare($q)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($pwd);
                $stmt->fetch();
                $stmt->free_result();
                /* close statement */
                $stmt->close();
            }


            $subject = "Forgot Password";             //Subject line.
            //Email body.
            $body = <<<HERE
            <html>
            <body>
                <center>
                    <h2>Password: $pwd</h2>
                    <p>If you did not request this email then something terribly terribly wrong has occurred.</p>
                </center>
            </body>
            </html>
HERE;
            //End email body.
            $mailer = new Mail(); //Create instance of Mail().
            //Determine if sending email was successful or not.
            if (($mailer->sendMail($email, $email, $subject, $body)) == true) {
                $h2 = "Sent!";
                $p = "You password has been sent to your email.";
                $optional = <<< HERE
                <ul class="actions">
                    <li><a href="../index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
                echo $css;
                include '../sections/messageblue.php';
                include 'footer.php';
            } else {
                $h2 = "Uh Oh!";
                $p = "Something went wrong while sending the email.";
                $optional = <<< HERE
                <ul class="actions">
                    <li><a href="../index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
                echo $css;
                include '../sections/messageblue.php';
                include 'footer.php';
            }
        }
        else {//email could not be found
            $h2 = "Email not found.";
            $p = "";
            $optional = <<< HERE
                <ul class="actions">
                    <li><a href="../index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
            echo $css;
            include '../sections/messageblue.php';
            include 'footer.php';
        }

    }

    function gatherResources() {
        global $con;
        $now = new DateTime('now');
        $q = "SELECT * FROM Charity_Users WHERE ID = '" . $_SESSION['user']->id . "'";
        $result = mysqli_query($con, $q);
        if (mysqli_num_rows($result) == 1) {
            // Add collected resources.
            $statement = $con->prepare("UPDATE Charity_Users SET stored_resources = stored_resources + ? WHERE ID=?");
            //bind parameters for markers, where (s = string, i = integer, d = double,  b = blob)
            $statement->bind_param('ii', $_SESSION['user']->gatherAmount, $_SESSION['user']->id);
            $statement->execute();
            /* free results */
            $statement->free_result();

            //Update datetime of gather
            $statement = $con->prepare("UPDATE Charity_Users SET last_gather_time = ? WHERE ID=?");
            $statement->bind_param('si', $now->format('Y-m-d H:i:s'), $_SESSION['user']->id);
            $statement->execute();
            /* free results */
            $statement->free_result();

            /* close statement */
            $statement->close();
        }
        $con->close();
    }


    function sendResources($recip, $points, $sentResources) {
        global $con, $css;
        if ($_SESSION['user']->resources < $points) {
            $h2 = "This is Embarrassing..";
            $p = "You do not have enough resources to complete this transaction.";
            $optional = "";

            include '../sections/messageblue.php';
            include 'footer.php';
        }
        else if ($_SESSION['user']->email == $recip) {
            $h2 = "Swindler!";
            $p = "Charity doesn't mean giving to yourself!";
            $optional = <<< HERE
                <ul class="actions">
                    <li><a href="../index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
            echo $css;
            include '../sections/messageblue.php';
            include 'footer.php';
        }
        else {
            $q = "SELECT * FROM Charity_Users WHERE email = '" . $recip . "'";
            $result = mysqli_query($con, $q);
            if (mysqli_num_rows($result) == 1) {
                //Update the points and resources for the sending user

                $statement = $con->prepare("UPDATE Charity_Users SET stored_resources = stored_resources - ?, points = points + ? WHERE email=?");
                $statement->bind_param('iis', $points, $points, $_SESSION['user']->email);
                $statement->execute();
                /* free results */
                $statement->free_result();
                $statement->close();


                //Update the points and resources for the recieving user
                $statement2 = $con->prepare("UPDATE Charity_Users SET stored_resources = stored_resources + ? WHERE email=?");
                $statement2->bind_param('is', $sentResources, $recip);
                $statement2->execute();
                $statement2->free_result();
                $statement2->close();

                //Create a record of the transaction
                $sender = $_SESSION['user']->email;
                mysqli_query($con, "INSERT INTO Charity_Transactions (sender, recipient, amount) VALUES ('$sender', '$recip', '$points')");
                mysqli_close($con);

                return true;

            } else {
                $h2 = "Where's Waldo?";
                $p = "We could not find the user you were trying to 'make it rain' on.";
                $optional = <<< HERE
                <ul class="actions">
                    <li><a href="../index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
                echo $css;
                include '../sections/messageblue.php';
                include 'footer.php';
            }
        }
    }

    function buyUpgrade(){
        global $con;
        $q = "SELECT Level, resource_amount, gather_time, upgrade_cost FROM Charity_Collection_Level WHERE Level = ?";
        if ($stmt = $con->prepare($q)) {
            $nextlvl = $_SESSION['user']->collectlvl + 1;
            $stmt->bind_param("i", $nextlvl);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($ugLevel, $ugResources, $ugTime, $ugCost);
            $stmt->fetch();
            $stmt->free_result();
            /* close statement */
            $stmt->close();
        }

        $statement = $con->prepare("UPDATE Charity_Users SET stored_resources = stored_resources - ?, collection_level=? WHERE email=?");
        $statement->bind_param('iis', $ugCost, $ugLevel, $_SESSION['user']->email);
        $statement->execute();
        /* free results */
        $statement->free_result();
        $statement->close();
        mysqli_close($con);
    }


    if (isset($_POST['gather'])) {
        gatherResources();
        unset($_POST);
        header("Location: ../homepage.php");
    }
    else if (isset($_POST['SendResources'])) {
        $recip = $_POST['recipemail'];
        $points = $_POST['resourcessent'];
        $sentResources = $_POST['resourcesreceived'];
        if (sendResources($recip, $points, $sentResources)) {
            unset($_POST);
            header("Location: ../homepage.php");
        }
    }
    else if (isset($_POST['buyupgrades'])) {
        buyUpgrade();
        unset($_POST);
        header("Location: ../homepage.php");
    }
    else if (isset($_POST['frgtemail'])) {
        $x = mysqli_real_escape_string($con, $_POST['frgtemail']);
        forgotPassword($x);
        unset($_POST);
    }
    else {
        $h2 = "What have you done!?";
        $p = "You dun goofed.";
        $optional = <<< HERE
                <ul class="actions">
                    <li><a href="../index.php"  class="button alt">Home</a></li>
                </ul>
HERE;
        echo $css;
        include '../sections/messageblue.php';
        include 'footer.php';
    }



?>
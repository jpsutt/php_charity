<?php
    if( !isset( $_SESSION ) ) {
        session_start();
    }

    if(isset($_SESSION["loggedIn"])) {
        header("Location: homepage.php"); /* Redirect browser */
        exit();
    }

    include './Utility/head.php';
    require './Utility/mail/mail.class.php';
    require './Utility/database/dbconnect.php';

    $errorList = array();

    function checkPwd() {
        global $pwd, $cpwd, $errorList;	//Declare global variables to be used in scope of function.
        $pwdLength = strlen($pwd); 	//This is a value used to iterate through password using a for loop.
        $letters = 0; 				//This is a variable to keep track of the number of alphabetic characters used in the password.
        $numbers = 0;				//This is a variable to keep track of the number of numbers used in the password.

        //Check for empty password field.
        if (empty($_POST["regpassword"])) {
            $errorList[] = "Password not entered.";
        }
        //Check for password length requirement.
        if (strlen($pwd) < 10) {
            $errorList[] = "Password must be a least 10 characters long.";
        }
        //Check for password and confirm password match.
        if ($pwd != $cpwd) {
            $errorList[] = "Passwords do not match";
        }
        //Check for both letters and numbers in password.
        for ($i = 0; $i < $pwdLength; $i++) {
            $element = substr( $pwd, $i, 1 );
            if (is_numeric($element)) {
                $numbers++;
            }
            if (ctype_alpha($element)) {
                $letters++;
            }
        }
        if ($numbers <= 0 || $letters <= 0) {
            $errorList[] = "Password must contain both numbers and alphabetic characters";
        }
    }

    function checkForm() {
        //Declare global variables to be used in scope of function.
        global $errorList, $email, $cemail, $outcomeHeader;
        //Check for matching emails.
        if ($email != $cemail) {
            $errorList[] = "Emails to not match.";
        }
        //Check is email is valid.
        if (isset($_POST["regemail"])) {
            if (filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL) === false) {
                $errorList[] = 'Email is invalid.';
            }
        }

        checkPwd(); //Check password function.
        //Determine id errors have occurred or not.
        if (count($errorList) == 0) {
            $outcomeHeader = "Success!";
            mailMe();
        } else {
            $outcomeHeader = "Oops! We noticed some errors!";
        }

    }
    //Random code generating function.
    function codeGenerate($codeLength) {
        $code = ""; //Declare code variable.
        $alphabet = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"); //Array of alpha characters.
        //Create code by with A -> Z and 1 -> 9.
        for ($i = 0; $i < $codeLength; $i++) {
            $rand = rand(0,34);
            if ($rand > 25) {
                $code = $code . strval($rand - 25);
            }
            else {
                $code = $code . $alphabet[$rand];
            }
        }
        return $code;
    }

    function mailMe()   {
        //Global variables used in function.
        global $email, $outcomeHeader, $outcome, $code;

        $code = codeGenerate(50);                //Build randomly generated code and assign it to $code variable.
        $tcode = "?code=" . $code;

        codeToDB();                                              //Send it to the DB!

        $subject = "Complete Your Activation!";             //Subject line.
        //Email body.
        $body = <<<HERE
                <html>
                <a href="http://corsair.cs.iupui.edu:20291/Presentation3/homepage.php$tcode">
                <body style="background-color: #00cdcf; border: 1px solid #2A2A2A; border-radius: 1.5em; padding: 2em">
                    <center>
                        <p style="color: #FFFFFF; font-weight: bold">
                        Welcome to Charity, $email.<br>
                        Click here to complete your activation.
                        </p>
                        <br>
                    </center>
                </body>
                </a>
                </html>
HERE;
        //End email body.
        $mailer = new Mail(); //Create instance of Mail().
        //Determine if sending email was successful or not.
        if (($mailer->sendMail($email, $email, $subject, $body))==true) {
            $outcomeHeader = "Success!";
            $outcome = "<p>Thank you.</p><p>An email has been sent to the provided account with a link to complete registration.</p>";
        }
        else {
            $outcomeHeader = "How Embarrassing!";
            $outcome = "<p>An error occurred while trying to send the email.</p>";
        }
    }


    function report() {                     //Function used to report information to user via HTML page.
        global $errorList, $outcome;

        if (count($errorList) > 0) {
            $outcome = <<<HERE
                        <p>We were unable to complete your registration due to the errors listed above.</p>
                        <a id="back" class="button">Back to Registration</a>
HERE;
            echo "<ul>";
            foreach ($errorList as $error) {
                print("<li>$error</li>");
            }
            echo "</ul>";
        }
        echo($outcome);
    }

    function codeToDB() {
        global $con, $email, $pwd, $code, $dirty;

        $dirty = array( $email, $pwd,);
        foreach ($dirty as $item) {
            $item = mysqli_real_escape_string($con, $item);
        }
        mysqli_query($con, "INSERT INTO Charity_Users (Email, Password, Activation_Code) VALUES ('$email', '$pwd','$code')");
        mysqli_close($con);
    }


    include './Utility/loginpopup.php';
    include './Utility/registerpopup.php';
    include './Utility/forgotpasswordpopup.php';

?>

    <!-- One -->
    <section id="banner">
        <?php
            if(isset($_POST['submit'])) {

                //Get variables from form submission to use throughout program.
                $email = trim($_POST["regemail"]);
                $cemail = trim($_POST["regcemail"]);
                $pwd = trim($_POST["regpassword"]);
                $cpwd = trim($_POST["regcpassword"]);

                $outcome = "";
                $outcomeHeader = "";


                checkForm(); //Verify form function

                echo "<h2>$outcomeHeader</h2>";
                report();
                echo "</section>";
            }
            else {
                echo <<< EOT
        <h2><strong>Charity</strong></h2>
        <p>A game of giving back.</p>
        <ul class="actions">
            <li><a href="#" id="btnlogin" class="button special">Login</a></li>
            <li><a href="#" id="btnregister"  class="button">Register</a></li>
        </ul>
    </section>
EOT;

                include './Utility/footer.php';
            }
    ?>
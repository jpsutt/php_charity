<?php

        if( !isset( $_SESSION ) ) {
            session_start();
        }

    require './Utility/database/dbconnect.php';

    /* FUNCTIONS */
    function formatInterval($i) {
        $obj = new stdClass();
        $obj->time = $i->format('%H:%I:%S');
        $obj->days = $i->format('%a');
        $obj->hours = $i->format('%H');
        $obj->minutes = $i->format('%I');
        $obj->seconds = $i->format('%S');
        $obj->sign = $i->format('%R');

        return $obj;
    }

    function calculateGather() {

        $sqlDateTime = new DateTime(date_format($_SESSION['user']->lastgathertime, 'Y-m-d H:i:s'));
        $now = new DateTime('now');
        //getCollectionInfo();                        //user->gatherTime [XX:XX:XX] SQL to assign variables to session user.

        //Solve the time difference
        $interval = date_diff($now, $sqlDateTime);
        $fint = formatInterval($interval);          //format the interval to calculate with

        if (($fint->time >= $_SESSION['user']->gatherTime) && ($fint->sign == "-")) {     //Show Gather Resource Button
            echo "<script type='text/javascript'> $('#gatherbox').addClass('gatherReady'); </script>";
            echo "<h3>Gather Now!</h3>";

            echo "<ul class='actions'>";
            echo "<form method='post' action='Utility/process.php'>";
            echo "<li><button class='button special icon fa-diamond' type='submit' value='gather' name='gather' id='btngather'>" . $_SESSION['user']->gatherAmount . "</button></li>";
            echo "</form>";
            echo "</ul>";
        }
        else {      //Show Countdown Clock

            //Calculate countdown clock
            $t1 = DateTime::createFromFormat('H:i:s', $_SESSION['user']->gatherTime);
            $t2 = DateTime::createFromFormat('H:i:s', $fint->time);

            $dint = date_diff($t2, $t1);
            $tint = formatInterval($dint);


            echo "<h3>Next Gather Time</h3>";

            echo "<div id='timer'></div>";
            echo "<script type='text/javascript'>";
            echo "$(document).ready(function () {";
            echo    "$('#timer').countdown({";
            echo        "until: ' +" . $tint->days . "d +" . $tint->hours . "h +" . $tint->minutes . "m +" . $tint->seconds . "s',";
            echo        "onExpiry: refresh";
            echo    "});";
            echo "});";
            echo "function refresh() {
                    location.reload();
                }";
            echo"</script>";

        }
    }

    include './Utility/sendresourcespopup.php';
    include './Utility/edituserpopup.php';
    include './Utility/transactionhistorypopup.php';
    include './Utility/leaderboardpopup.php';
    include './Utility/upgradespopup.php';

?>
<style>
    #btngather {
        -webkit-animation: breathing 3s linear infinite normal;
        animation: breathing 3s linear infinite normal;
        -webkit-font-smoothing: antialiased !important;
        text-align: center;
    }

    @-webkit-keyframes breathing {
        0% {
            -webkit-transform: scale(1.25);
            transform: scale(1.25);
        }

        25% {
            -webkit-transform: scale(1);
            transform: scale(1);
        }

        60% {
            -webkit-transform: scale(1.25);
            transform: scale(1.25);
        }

        100% {
            -webkit-transform: scale(1.25);
            transform: scale(1.25);
        }
    }

    @keyframes breathing {
        0% {
            -webkit-transform: scale(1.25);
            -ms-transform: scale(1.25);
            transform: scale(1.25);
        }

        25% {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        60% {
            -webkit-transform: scale(1.25);
            -ms-transform: scale(1.25);
            transform: scale(1.25);
        }

        100% {
            -webkit-transform: scale(1.25);
            -ms-transform: scale(1.25);
            transform: scale(1.25);
        }
    }
</style>

<section id="hud" class="wrapper special">
    <div class="inner">
        <header class="major">
            <h2><?php echo $_SESSION['user']->email; ?></h2>
        </header>
        <div class="features">
            <div id="resourcesbox" class="feature">
                <i class="fa fa-diamond"></i>
                <h3>Resources</h3>
                <h2><?php echo  $_SESSION['user']->resources; ?></h2>
            </div>
            <div id="gatherbox" class="feature">
                <i class="fa fa-clock-o"></i>
                <?php calculateGather(); ?>
            </div>
            <div id="pointsbox" class="feature">
                <i class="fa fa-trophy"></i>
                <h3>Score: <?php echo  $_SESSION['user']->points; ?></h3>
                <ul class="actions">
                    <li><a  id="btnleaderboard" class="button special">Leaderboard</a></li>
                </ul>
            </div>
            <div id="sendresourcesbox" class="feature">
                <i class="fa fa-heart-o"></i>
                <h3>Send Resources</h3>
                <ul class="actions">
                    <li><a  id="btnsendresources" class="button special">Give</a></li>
                </ul>
            </div>
            <div id="upgradesbox" class="feature">
                <i class="fa fa-gears"></i>
                <h3>Upgrades</h3>
                <ul class="actions">
                    <li><a  id="btnupgrades" class="button special">Buy Upgrades</a></li>
                </ul>
            </div>
            <div id="tranhistory" class="feature">
                <i class="fa fa-history"></i>
                <h3>Transaction History</h3>
                <ul class="actions">
                    <li><a  id="btnhistory" class="button special">View</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
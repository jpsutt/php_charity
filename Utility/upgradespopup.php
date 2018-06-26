<?php

    require 'database/dbconnect.php';

?>
<section class="wrapper style2 special popup" id="Upgrades">
        <?php
            $q = "SELECT Level, resource_amount, gather_time, upgrade_cost FROM Charity_Collection_Level WHERE Level = ?";
            if ($stmt = $con->prepare($q)) {
                $nextlvl = $_SESSION['user']->collectlvl + 1;
                $stmt->bind_param("i", $nextlvl);
                $stmt->execute();
                $stmt->store_result();
                if ($num_of_rows >= 1) {
                    $stmt->bind_result($ugLevel, $ugResources, $ugTime, $ugCost);
                    $stmt->fetch();
                    echo"<h2>Level: $ugLevel</h2>";
                    echo"<h4>Collection Amount: $ugResources</h4>";
                    echo"<h4>Time: $ugTime</h4>";
                    if ($_SESSION['user']->resources < $ugCost) {
                        echo "<ul class='actions'>
                                <li><a style='color: red' class='button alt icon fa-diamond' id='btnbuyupgrade' name='buyupgrade' type='submit' disabled>$ugCost</a></li>
                            </ul>";
                    } else if ($_SESSION['user']->resources >= $ugCost){
                        echo "<ul class='actions'>
                            <li><a class='button special icon fa-diamond' id='btnbuyupgrade' name='buyupgrade'>$ugCost</a></li>
                        </ul>
                        <form method='POST' id='buyupgrades' action='Utility/process.php'>
                        <input type='hidden' name='buyupgrades'>
                        </form>";
                    }
                    /* free results */
                    $stmt->free_result();
                    /* close statement */
                    $stmt->close();
                }
                else {
                    echo"I don't know what happened";
                }
            }
            $con->close();
        ?>
</section>

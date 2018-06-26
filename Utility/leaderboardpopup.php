<?php

    require 'database/dbconnect.php';

?>
<section class="wrapper style2 special popup tablepop" id="Leaderboard">
        <?php
            $q = "SELECT email, points, stored_resources FROM Charity_Users WHERE active='1' ORDER BY points DESC, stored_resources DESC";
            if ($stmt = $con->prepare($q)) {
                $stmt->execute();
                $stmt->store_result();
                $num_of_rows = $stmt->num_rows;
                if ($num_of_rows >= 1) {
                    $stmt->bind_result($email, $points, $resources);
                    $i = 1;
                    echo "<table id='leaderboardtable'>
                        <thead>
                        <tr>
                            <th>Rank</th>
                            <th>User</th>
                            <th><i class='fa fa-trophy' aria-hidden='true'></i></th>
                            <th><i class='fa fa-diamond' aria-hidden='true'></i></th>
                        </tr>
                        </thead>
                        <tbody>";
                    while ($stmt->fetch()) {
                        ////DO THE STUFF
                        echo "<tr>";
                        echo "<td>$i</td>";
                        echo "<td>$email</td>";
                        echo "<td>$points</td>";
                        echo "<td>$resources</td>";
                        echo "</tr>";
                        $i++;
                    }
                    echo "</tbody>
                          </table>";
                    /* free results */
                    $stmt->free_result();
                    /* close statement */
                    $stmt->close();
                }
                else {
                    echo "<h2>No transactions found.</h2>";
                }

            }
            $con->close();

        ?>
</section>

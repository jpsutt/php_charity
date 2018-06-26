<?php

    require 'database/dbconnect.php';

?>
<section class="wrapper style2 special popup tablepop" id="TransactionHistory">
        <?php
            $q = "SELECT date, sender, recipient, amount FROM Charity_Transactions WHERE sender = ? OR recipient = ?";
            if ($stmt = $con->prepare($q)) {
                $stmt->bind_param("ss", $_SESSION['user']->email, $_SESSION['user']->email);
                $stmt->execute();
                $stmt->store_result();
                $num_of_rows = $stmt->num_rows;
                if ($num_of_rows >= 1) {
                    $stmt->bind_result($date, $sender, $recipient, $amount);
                    $i = 1;
                    echo "<table id='historytable'>
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sender</th>
                            <th>Recipient</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>";
                    while ($stmt->fetch()) {
                        ////DO THE STUFF
                        echo "<tr>";
                        echo "<td>$date</td>";
                        echo "<td>$sender</td>";
                        echo "<td>$recipient</td>";
                        echo "<td>$amount</td>";
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

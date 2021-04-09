

    <?php

        $userAccounts = DB::query("SELECT AccountID,type,balance,cardNum FROM `user_account` WHERE `UserID` = '$USER_ID'");


        function getRandRGB() {
            return "background-color: rgb(" . rand(0,255) ."," . rand(0,255) ."," . rand(0,255) .");";
        }

        if(DB::count() > 0) {


            //Fetch account display
            foreach ($userAccounts as $row) {

                ?>

                <div class="card" style="width: 18rem;">
                    <?php echo '<div class="card-img-top" style="' . getRandRGB() . 'padding: 35px;" >&nbsp;</div>'; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo displayAccountType($row['type']) . "Account";
                            echo "(" . $row['cardNum'] . ")"; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Balance: <?php echo "$" . $row['balance']; ?></h6>
                        <a href="<?php echo '?page=accHistory&id=' . $USER_ID . '&token=' . $USER_TOKEN . '&cType=' . $row['type'] . '&cID=' . $row['AccountID']; ?>"
                           class="card-link" style="float: right;">History</a>
                    </div>
                </div>
                <p>&nbsp;</p>

                <?php
            }
        } else {
            echo "<div class=\"alert alert-secondary\" role=\"alert\">Seems that you don't have any accounts yet. <a href='?page=openNewAccount'>Click here</a> to open some.</div>";
        }



    <?php


        $userAccounts = DB::query("SELECT * FROM `user_account` WHERE `UserID` = '$USER_ID'");


    $rowCount = DB::count();

    if($rowCount < 1) {
        die("<p style='padding: 30px;'>It seems that you don't have any accounts open yet, sorry.</p>");
    }

        //Fetch account display
        foreach($userAccounts as $row) {

            /**
             * This gets account purchase history to the specified limit, most recent is first.
             * Fills var with an array with purchases
             */
            $purchases = getTransactionHistory($row['AccountID'], $row['UserID'], $row['type'], 3);
        ?>

    <div class="card">
        <div class="card-img-top" style="background-color: lightgreen; padding: 5px;">&nbsp;</div>
        <div class="card-body">
            <h5 class="card-title"><?php echo displayAccountType($row['type']) . " Account "; echo "(" . substr($row['cardNum'], 12, 4) . ")";?></h5>
            <h6 class="card-subtitle mb-2 text-muted">Balance: <?php echo "$" . $row['balance']; ?></h6>
            <p class="card-text">Recent Transactions:</p>
            <div class="container">
            <div class="row">
                <?php
                //iterate through, count() for the the instances where there are not 3 transactions in history
                for($x = 0; $x < count($purchases); $x++) { ?>
                <div class="col-sm">
                    <?php echo substr($purchases[$x]['merchantInfo'], 0, 15) . "... - [$" . $purchases[$x]['amount'] . "]"; ?>
                </div>
                <?php } ?>
            </div>
            </div>
        </div>
    </div>

            <p>&nbsp;</p>
    <?php
            }
    /**
     * Include session destroy here just to make sure, repercussions of this being here is uncertain
     * but 'resets' sessions that may need it
    */
    //session_destroy();
    /**
     * Instead of destroying the session completely, unset the possible security flaws from Registration
     */
    if(isset($_SESSION['password1']) || isset($_SESSION['password2'])){
        unset($_SESSION['password1']);
        unset($_SESSION['password2']);
    }


    ?>

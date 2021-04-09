
    <?php
    $transferRowID = $_POST['transferRowID'];
    $decision = $_POST['decision'];
    $postFromID = $_POST['fromID'];
    $transferTypeNegPos = $_POST['transferTypeNegPos'];
    $transactionAmount = $_SESSION['_amount'];

if(isset($decision) && isset($transferRowID) && isset($transactionAmount)){
        $statusToSet = 0;
        //if($decision == "accept" && doUserTransaction($transferRowID, $USER_ID, 1, "Transfer: #[$transferRowID]", $transactionAmount)) {
        if($decision == "accept") {

            if($transferTypeNegPos == 0) {
                //REQUEST from other user
                //This USER_ID -> postFromID
                $statusToSet = doUserTransfer(getUser_BankAccountDetailsFromID($USER_ID,0), getUser_BankAccountDetailsFromID($postFromID,0), $transactionAmount, "Transfer: #UID[$postFromID] to #UID[$USER_ID]" );
            } else {
                //SEND to: fromID -> USER_ID
                $statusToSet = doUserTransfer(getUser_BankAccountDetailsFromID($postFromID,0), getUser_BankAccountDetailsFromID($USER_ID,0), $transactionAmount, "Transfer: #UID[$postFromID] to #UID[$USER_ID]" );

            }

            $statusToSet = 1;
        } else {
            $statusToSet = 0;
        }
        if($decision == "reject") {
            $statusToSet = -1;
        }
        if(setTransferRequestStatus($transferRowID, $statusToSet) == 1) {
            ?>
            <div class="alert alert-success" role="alert">
                Changes were made successfully.
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
                No changes were made to offer status.
            </div>
            <?php
        }
    }

    $action = $_POST['rowID']; // ID from POST
    if(isset($action)){
        switch($action) {
            default:
                $details = getUser_RequestDetails($action);
                /*
                 * Update to show that user viewed
                 */
                try {
                    DB::query("UPDATE `user_request` SET `viewed` = '1' WHERE `user_request`.`RowID` = '$action'");
                } catch (Exception $e) { echo "Unable to set view status.";}

                ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="card text-center">
                    <div class="card-header">
                        <?php if($details['transferType'] == 0){ echo "REQUEST"; } else {echo "MONEY RECEIVED";} ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo "Amount: " . $details['amount']; ?></h5>
                        <?php $_SESSION['_amount'] = $details['amount']; //set amount here ?>
                        <p class="card-text"><?php echo "<p>Note from user: <q>" . $details['note'] . "</q></p>"; ?></p>
                        <input type="hidden" name="transferRowID" value="<?php echo $action; ?>">
                        <input type="hidden" name="fromID" value="<?php echo $details['fromID']; ?>">
                        <input type="hidden" name="transferTypeNegPos" value="<?php echo $details['transferType']; ?>">
                        <button name="decision" value="reject" class="btn btn-danger">Reject</button> <button name="decision" value="accept" class="btn btn-success"><?php if($details['transferType'] == 0){ echo "Send Money"; } else {echo "Accept Money";} ?></button>
                    </div>
                    <div class="card-footer text-muted">
                        <?php echo $details['time']; ?>
                    </div>
                </div>
            </form>

                <?php
                break;
        }
    }

    echo "Fetching transfers requests: <br />";

    $requests = DB::query("SELECT * FROM `user_request` WHERE `toID` = '$USER_ID'");
    $rowCount = DB::count();
    echo "You have " . $rowCount . " to review.";

    ?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">From</th>
            <th scope="col">Amount</th>
            <th scope="col">Time</th>
            <th scope="col">Status</th>
            <th scope="col">-</th>
        </tr>
        </thead>
        <tbody>

    <?php

    if($rowCount > 0) {
        foreach($requests as $row) {

            ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <tr>
                <td><?php echo getUserDataOfID("firstName",$row['fromID']); ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['time']; ?></td>
                <td><?php switch($row['executed']) {case 0: echo "Pending"; break;case 1: echo "Accepted"; break;case -1: echo "Rejected"; break; } ?></td>
                <td>
                    <?php
                    if($row['executed'] == 0) { ?>
                        <button class="btn btn-success" name="rowID" value="<? echo $row['RowID']; ?>">View</button>
                    <?php
                    } else {
                        ?>
                        <button class="btn btn-success" disabled>View</button>
                        <?php
                    }
                    ?>
                </td>
            </tr>
    </form>
    <?php
        }
    } else {
        echo "You have no requests or money to accept.";
    }
    ?>

        </tbody>
    </table>



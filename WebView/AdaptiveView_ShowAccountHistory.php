
<?php

//Check if the user has a pin, this must be set before transactions are set
//show if checking account is selected and does not have a pinset
if($_GET['cType'] == 0) {
    if(!accountHasPin(getUserCheckingAccID($USER_ID))) {
        //include_once "AdaptiveView_Account_PinSettings.php";
        //exit();
        exit("You must set a pin. <a href='AdaptiveView.php?page=pinsetup'>SET PIN</a>");
    }
}


//NOT A GOOD security practice, should not be defined by GET, can be modified by user
//IS protected by SQL injection attacks however
$accountDetails = getUser_BankAccountDetailsUnAssc($_GET['cID'] , $USER_ID, $_GET['cType']);
//print_r($accountDetails);
?>

<div class="card border-light mb-3" style="max-width: auto;">
    <div class="card-header"><?php echo displayAccountType($accountDetails[0]['type']) . " Account (" . $accountDetails[0]['cardNum'] . ")"; ?></div>
    <div class="card-body">
        <h5 class="card-title">Balance: <?php echo $accountDetails[0]['balance']; ?></h5>
        <p class="card-text">Routing: <?php echo $accountDetails[0]['routingNum']; ?> | Wires: <?php echo $accountDetails[0]['wireNum']; ?></p>
    </div>
</div>

<?php


//$userAccount = DB::query("SELECT  FROM `user_account` WHERE `UserID` = '$USER_ID'");
$purchaseHistory = DB::query("SELECT * FROM `user_purchase_history` WHERE `UserID` = '$USER_ID' AND `cardType` = '". $_GET['cType'] ."' AND `AccountID` = '". $_GET['cID'] ."' ORDER BY `transactionDate` DESC");
$rowCount = DB::count();


?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Merchant Info</th>
            <th scope="col">Date</th>
            <th scope="col">Amount</th>
            <th scope="col">Balance After</th>
        </tr>
        </thead>
        <tbody>





            <?php

                    foreach($purchaseHistory as $row) {
                        ?>
                        <tr>
                            <td><? echo $row['merchantInfo']; ?></td>
                            <td><? echo $row['transactionDate']; ?></td>
                            <td><? echo $row['amount']; ?></td>
                            <td><? echo $row['currentAmount']; ?></td>
                        </tr>
                        <?php
                    }
                ?>

               <?php
               if($rowCount < 1) {
                   ?><p class="border border-secondary" style="background-color: #e8e9ea; text-color: darkgray; padding: 20px;">
                       It appears that you don't have any account history at this time.</p>
               <?php
               }
               ?>
        </tbody>
    </table>



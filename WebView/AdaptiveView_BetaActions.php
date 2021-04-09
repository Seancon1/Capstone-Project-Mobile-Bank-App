<?php

if(isset($_POST['action'])) {
echo "Action: " . $_POST['action'];
}

switch($_POST['action']){
    case "purchase":
        //Select all accounts
        $userAccountIDs = DB::query("SELECT `AccountID`, `type` FROM `user_account` WHERE `UserID` = $USER_ID");

        $x = microtime();
        foreach($userAccountIDs as $row) {
            $tempID = $row['AccountID'];
            $tempType = $row['type'];

            //loop a few times
            for($i = 0; $i < 2; $i++) {
                DB::query("INSERT INTO `user_purchase_history` (`RowID`, `AccountID`, `UserID`, `cardType`, `merchantInfo`, `transactionDate`, `amount`, `currentAmount`) VALUES (NULL, '$tempID', '$USER_ID', '$tempType', 'Auto Gen. #$x', CURRENT_TIMESTAMP, '0.00', '0.00');");
            $x++;
            }
        }

        echo "<b style='color: red;'>Two purchases added to all your accounts.</b>";

        break;

    case "newaccount":
        for($i = 0; $i < 3; $i++) {
            DB::query("INSERT INTO `user_account` (`AccountID`, `UserID`, `type`, `balance`, `cardNum`, `routingNum`, `wireNum`, `CVV`) VALUES (NULL, '$USER_ID', '$i', '12.34', '1234567891234567', '123456789123', '123123123123', '123')");
        }
        echo "<b style='color: red;'>One of each account type has been added to your account.</b>";

        break;

    case "purchase1":

        $done = doUserTransaction(11, $USER_ID, 0, "SYSTEM", (mt_rand(-100,100)));
        if($done == 1) {
            echo "<p style='color: red;'>Made a random transaction, wow.</p>";
        }
        echo $done;
        break;

    case "sendDummyMsg":
        sendUserMsg("Dummy Msg", "This is a notification. " . time(), $USER_ID);
        sendUserMsg("Dummy Msg", "This is another notification. " . time(), $USER_ID);
        echo "NOTIFICATIONS MADE";
        break;

    case "genToken":
        echo "Purchase Token: " . generatePurchaseCode($USER_ID);
        break;

    default:

        echo "No BETA action set";
        break;
}

/*
switch($_SESSION['beta']) {
    case 'purchase':
        break;
    case 'newaccount':
        break;

}
*/

?>

<h2>Beta Actions</h2>
<p>These actions will be populated as more features are added.</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p></p>
    <button name="action" value="purchase">Add Purchases (2 for all accounts)</button>
    <button name="action" value="purchase1">Do random transaction</button>
    <button name="action" value="sendDummyMsg">Create 2 notifications</button>
    <button name="action" value="genToken">Generate Purchase Token</button>
</form>


 <?php

echo "Time NOW: " . time();
echo "Time in 5 minutes: " . (time()+300);

echo "Your account ID is: " . getIDFromUserName('b');
echo "Your account ID is: " . getIDFromUserName('Boa');
echo "Your account ID is: " . getIDFromUserName('hj75hgj6ghf');
echo "Your checking acc ID is: " . getUserCheckingAccID($USER_ID);
$time = time();
echo "<br /> time: " . $time;
echo "<br /> - (+5min): " . ($time + 300); // + 5minutes

//genUniqueCardNum();


echo "<br />Generating random numbers: ";
for($i = 0; $i < 25; $i++) {
    echo genCardNumbers(16). "<br />";
    echo genCardNumbers(16). "<br />";
    echo genCardNumbers(16). "<br />";
}

for($i = 0; $i < 25; $i++) {
    echo genCardNumbers(16, 0). "<br />";
    echo genCardNumbers(16, 0). "<br />";
    echo genCardNumbers(16, 0). "<br />";
}


/*
$items[] = "";
var_dump(getUser_BankAccountDetailsFromID($USER_ID, 0));
*/





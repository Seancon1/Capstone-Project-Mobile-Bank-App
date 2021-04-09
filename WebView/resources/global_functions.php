<?php

function passwordVerify($inValue, $inID = -1) {
    $userPassword = DB::queryFirstField("SELECT `password` FROM `user` WHERE `ID` = '$inID'");
    return password_verify($userPassword, $inValue);
}

function getUserDataOfID($field,$inID = -1) {
        $query = DB::queryFirstField("SELECT `$field` FROM `user_personal` WHERE `ID` = '$inID'");
    return $query;
}

function getUserData($inID = -1) {
        $query = DB::queryFirstRow("SELECT * FROM `user_personal` WHERE `ID` = '$inID'");
    return array($query);
}

function getUserBankInfo_Simple($inID = -1) {
    $query = DB::query("SELECT * FROM `user_account` WHERE `UserID` = '$inID'");
    return array_values($query);
}

/**
 * Gets a single transaction from user transaction history
 * @param $accNum
 * @param $inType
 * @param $inID
 * @return null
 */
function getTransactionHistory($accNum, $inID, $inType, $numToDisplay) {
    $rows = DB::query("SELECT * FROM `user_purchase_history` WHERE `AccountID` = '$accNum' AND `UserID` = '$inID' AND `cardType` = '$inType' ORDER BY `RowID` DESC LIMIT $numToDisplay");
    return $rows;
}

function updateUserPersonal($field, $inValue, $inID = -1) {
    DB::queryFirstRow("UPDATE `user_personal` SET `$field`= '$inValue' WHERE `ID` = '$inID'");
    $numRows = DB::affectedRows();
    if($numRows > 0) {
        return 1;
    }else{return 0;}
}

function displayAccountType($inType) {
    switch($inType) {
        case 0:
            return "Checking";
            break;
        case 1:
            return "Savings";
            break;
        case 2:
            return "Credit";
            break;
        default:
            return null;
    }
}

/**
 * Performs a transaction using User ID
 * @param $inID - ID of the user
 * @param $amount - Amount specified
 * @param $accountType - Account type to help distinguish between different accounts
 * @return int - boolean to show success
 */
function performUserTransaction($inID, $amount, $accountType) {

    $userBalance = DB::queryFirstField("SELECT `balance` FROM `user_account` WHERE `UserID` = '$inID' AND `type` = '$accountType'");
    $userBalance += $amount;
    DB::query("UPDATE `user_account` SET `balance`= '$userBalance' WHERE `UserID` = '$inID' AND `type` ='$accountType'");
    $rowsAffected = DB::count();
    if($rowsAffected > 0) {
        return 1;
    } else {
        return 0;
    }

}


function getUser_RequestDetails($inRowID) {
    $details = DB::queryFirstRow("SELECT * FROM `user_request` WHERE `RowID` = '$inRowID'");
    if(DB::count() > 0) {
        return $details;
    }
    return null;
}

function getUser_BankAccountDetailsFromID($userID, $accountType) {
    $details = DB::queryFirstRow("SELECT * FROM `user_account` WHERE `UserID` = '$userID' AND `type` = '$accountType'");
    //print_r($details);
    if(DB::count() > 0) {
        //array_values returns the data as an indexed array
        return array_values($details);
    }
    return null;
}

function getUser_BankAccountDetails($accountID, $userID, $accountType) {
    $details = DB::queryFirstRow("SELECT * FROM `user_account` WHERE `UserID` = '$userID' AND `AccountID` ='$accountID' AND `type` = '$accountType'");

    if(DB::count() > 0) {
        return array_values($details);
    }
    return null;
}

function getUser_BankAccountDetailsUnAssc($accountID, $userID, $accountType) {
    $details = DB::queryFirstRow("SELECT * FROM `user_account` WHERE `UserID` = '$userID' AND `AccountID` ='$accountID' AND `type` = '$accountType'");

    if(DB::count() > 0) {
        return array($details);
    }
    return null;
}

function setTransferRequestStatus($inRowID, $status) {
    /* $status
    1 for accepted
    0 for no action yet
    -1 for rejected
    */
    DB::queryFirstRow("UPDATE `user_request` SET `executed`='$status' WHERE `RowID` = '$inRowID'");
    if(DB::affectedRows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

/**
 * Transfer money between users. firstUser -> secondUser
 * Each array should have two items: AccountID and UserID
 * @param array $firstUser
 * @param array $secondUser
 * @param int $amount
 * @return boolean
 */

function doUserTransfer(Array $firstUser, Array $secondUser, $amount, $inInfo) {
    //Array holds all values needed to use in doUserTransaction function
    //$userOneDetails =  Account ID [0]    UserID [1]
    $userOneDetails = getUser_BankAccountDetails($firstUser[0], $firstUser[1],0);
    $userTwoDetails = getUser_BankAccountDetails($secondUser[0], $secondUser[1],0);

    //cardType by default should be 0, user cannot have more than one checking account
    if((doUserTransaction($userOneDetails[0],$userOneDetails[1],0, $inInfo ,(-$amount)) == 1)  && (doUserTransaction($userTwoDetails[0], $userTwoDetails[1],0, $inInfo, $amount))) {
        sendUserMsg("Money Sent", "An amount of " . $amount . " was deducted from your checking account. ", $userOneDetails[1]);
        sendUserMsg("Money Received", "An amount of " . $amount . " was added to your checking account. ", $userTwoDetails[1]);
        return 1;
    } else {
        return 0;
    }
}

/*
 * Generates query to change user account balance AND
 * Also makes a record in user purchase history
 */
function doUserTransaction($accountID, $userID, $cardType, $info, $amount)
{
    DB::startTransaction();
    $currentBalance = DB::queryFirstField("SELECT `balance` FROM `user_account` WHERE `AccountID` = '$accountID' AND `UserID` = '$userID' AND `type` = '$cardType'");
    $newBalance = $currentBalance + ($amount);
    DB::query("UPDATE `user_account` SET `balance`= '$newBalance' WHERE `AccountID` = '$accountID' AND `UserID` ='$userID' AND `type` ='$cardType'");
    $counter = DB::affectedRows();
    if ($counter > 0) {
        DB::commit(); //do changes

        //Now update history
        DB::startTransaction();
        DB::query("INSERT INTO `user_purchase_history` (`RowID`, `AccountID`, `UserID`, `cardType`, `merchantInfo`, `transactionDate`, `amount`, `currentAmount`) VALUES (NULL, '$accountID', '$userID', '$cardType', '$info', CURRENT_TIMESTAMP, '$amount', '$newBalance');");
        $rowsAffected = DB::affectedRows();
        if($rowsAffected > 0) {
            DB::commit();
            return 1; //THIS SHOULD RETURN TRUE if everything went as planned
        } else {
            return 0;
        }
    } else {
        DB::rollback(); //undo changes
        return 0;
    }
    return 0;
}


/**
 * Sends messages to a user.
 * @param $title
 * @param $text
 * @param $inID
 * @return int
 */
function sendUserMsg($title ,$text, $inID)
{
    DB::startTransaction();
    DB::query("INSERT INTO `user_notifications` (`RowID`, `UserID`, `title`, `msg`, `time`, `viewed`) VALUES (NULL, '$inID', '$title', '$text', CURRENT_TIMESTAMP, '0');");
    $rowsAffected = DB::affectedRows();
    if ($rowsAffected > 0) {
        DB::commit();
        return 1; //THIS SHOULD RETURN TRUE if everything went as planned
    } else {
    DB::rollback(); //undo changes
    return 0;
    }
}

function getUserMsg($rowID, $userID) {
    $details = DB::queryFirstRow("SELECT * FROM `user_notifications` WHERE `RowID` = '$rowID' AND `UserID` = '$userID'");
    if(DB::count() > 0) {
        return $details;
    }
    return null;
}

function setMsgViewed($rowID) {
    DB::startTransaction();
    DB::query("UPDATE `user_notifications` SET `viewed` = '1' WHERE `user_notifications`.`RowID` = '$rowID'");
    $rowsAffected = DB::affectedRows();
    if ($rowsAffected > 0) {
        DB::commit();
        return 1; //THIS SHOULD RETURN TRUE if everything went as planned
    } else {
        DB::rollback(); //undo changes
        return 0;
    }
}

function getUnViewedNotificationCount($inID) {
    DB::query("SELECT * FROM `user_notifications` WHERE `UserID` = '$inID' AND `viewed` = 0");
    return DB::count();
}

/*
 * Generates history for any transaction
 */
/*
function addUserHistory($accountID, $userID, $cardType, $info, $amount) {
    $balanceAfterTransaction = DB::queryFirstField("SELECT `balance` FROM `user_account` WHERE `AccountID` = '$accountID' AND `UserID` = '$userID' AND `type` = '$cardType'");

    $queryForHistory = DB::query("INSERT INTO `user_purchase_history` (`RowID`, `AccountID`, `UserID`, `cardType`, `merchantInfo`, `transactionDate`, `amount`, `currentAmount`) VALUES (NULL, '$accountID', '$userID', '$cardType', '$info', CURRENT_TIMESTAMP, '$amount', '$balanceAfterTransaction');");
}
*/

/**
 * Checks if user checking exists
 */

function isUserCheckingPresent($userID){
    DB::query("SELECT * FROM `user_account` WHERE `UserID` = '$userID'");

    if(DB::count() > 0) {
        return true;
    }
    return false;
}
function isUserCreditAccPresent($userID){
    DB::query("SELECT * FROM `user_account` WHERE `UserID` = '$userID' AND `type` = '2'");

    if(DB::count() > 0) {
        return true;
    }
    return false;
}
function isUserSavingsAccPresent($userID){
    DB::query("SELECT * FROM `user_account` WHERE `UserID` = '$userID' AND `type` = '1'");

    if(DB::count() > 0) {
        return true;
    }
    return false;
}

function returnNumOfPresentAccountTypes($userID, $accountType){
    DB::query("SELECT * FROM `user_account` WHERE `UserID` = '$userID' AND `type` = '$accountType'");
    return DB::count();
}

function formatMoney($inMoney) {
    //return "$" . number_format();
}

/**
 * Generates a number at the specified length
 * @param $length
 * @param int $flag
 * @return int|string
 */
function genCardNumbers($length, $flag = 1) {
    //$random mt_rand(0, 9);
    $cardNum = "";

    if($flag == 0) {
        $cardNum = mt_rand(1,9); //first number cannot be 0
        $length--;
    }

    for($x = 0; $x < ($length); $x++) {
        $cardNum .= mt_rand(0,9);
    }
    return $cardNum;
}


function genUniqueCardNum() {
    //echo "Generating number: ";
    //First generate one, this will usually be sufficient
    $num = genCardNumbers(16, 0);
    //echo $num . ". ";
    DB::query("SELECT `cardNum` FROM `user_account` WHERE `cardNum` = '$num'");
    //echo "Query executed. ";
    if(DB::count() > 0) {
        //echo "Number found, generating again. <br /> ";
        return genUniqueCardNum(); //this should loop until a number is found
    } else {
        return $num; //otherwise return number
    }
}

function genUniqueRoutingNum() {
    //echo "Generating number: ";
    //First generate one, this will usually be sufficient
    $num = genCardNumbers(11);
    //echo $num . ". ";
    DB::query("SELECT `cardNum` FROM `user_account` WHERE `routingNum` = '$num'");
    //echo "Query executed. ";
    if(DB::count() > 0) {
        //echo "Number found, generating again. <br /> ";
        return genUniqueRoutingNum(); //this should loop until a number is found
    } else {
        return $num; //otherwise return number
    }
}

function genUniqueWireNum() {
    //echo "Generating number: ";
    //First generate one, this will usually be sufficient
    $num = genCardNumbers(12);
    //echo $num . ". ";
    DB::query("SELECT `cardNum` FROM `user_account` WHERE `wireNum` = '$num'");
    //echo "Query executed. ";
    if(DB::count() > 0) {
        //echo "Number found, generating again. <br /> ";
        return genUniqueWireNum(); //this should loop until a number is found
    } else {
        return $num; //otherwise return number
    }
}

/**
 * This will give a user a checking account, generating random and unique numbers for the accounts
 * @param $inID : ID of user
 * @return int : 0 if fail, 1 if success
 */
function submitNewCheckingAccount($inID) {
    $cardNum = genUniqueCardNum();
    $routingNum = genUniqueRoutingNum();
    $wireNum = genUniqueWireNum();
    $cvv = genCardNumbers(3);

    DB::startTransaction();
    DB::query("SELECT `type` FROM `user_account` WHERE `UserID` = '$inID' AND `type` = '0'");
    if(DB::count() < 1) {
        DB::query("INSERT INTO `user_account` (`AccountID`, `UserID`, `type`, `balance`, `cardNum`, `routingNum`, `wireNum`, `CVV`) VALUES (NULL, '$inID', '0', '0.00', '$cardNum', '$routingNum', '$wireNum', '$cvv')");
        DB::commit();
    } else {
        DB::rollback(); //undo any changes
        return 0;
    }
    return 1;
}

function submitNewCreditAccount($inID) {
    $cardNum = genUniqueCardNum();
    $routingNum = genUniqueRoutingNum();
    $wireNum = genUniqueWireNum();
    $cvv = genCardNumbers(3);

    DB::startTransaction();
    DB::query("SELECT `type` FROM `user_account` WHERE `UserID` = '$inID' AND `type` = '2'");
    if(DB::count() < 1) {
        DB::query("INSERT INTO `user_account` (`AccountID`, `UserID`, `type`, `balance`, `cardNum`, `routingNum`, `wireNum`, `CVV`) VALUES (NULL, '$inID', '2', '0.00', '$cardNum', '$routingNum', '$wireNum', '$cvv')");
        DB::commit();
    } else {
        DB::rollback(); //undo any changes
        return 0;
    }
    return 1;
}

function submitNewSavingsAccount($inID) {
    $cardNum = genUniqueCardNum();
    //$routingNum = genUniqueRoutingNum();
    //$wireNum = genUniqueWireNum();
    //$cvv = genCardNumbers(3);

    DB::startTransaction();
    DB::query("SELECT `type` FROM `user_account` WHERE `UserID` = '$inID' AND `type` = '1'");
    if(DB::count() < 1) {
        DB::query("INSERT INTO `user_account` (`AccountID`, `UserID`, `type`, `balance`, `cardNum`, `routingNum`, `wireNum`, `CVV`) VALUES (NULL, '$inID', '1', '0.00', '$cardNum', '', '', '')");
        DB::commit();
    } else {
        DB::rollback(); //undo any changes
        return 0;
    }
    return 1;
}

function getUserCheckingAccID($inID) {
    $query = DB::queryFirstRow("SELECT `AccountID` FROM `user_account` WHERE `UserID` = '$inID' AND `type` = '0' ORDER BY `AccountID` ASC");

    if(DB::count() > 0) {
        return $query['AccountID'];
    }
    return null;
}

/**
 * Secures pin by inserting pin as hashed values, can only be 4 characters
 * @param $accountID
 * @param $inPIN
 * @return int
 */
function submitNewPin($accountID,$inPIN) {
    $pin = Array();
    for($i =0; $i < 4; $i++){
        $pin[$i] = password_hash(substr($inPIN, $i, 1), PASSWORD_BCRYPT);
    }

    DB::startTransaction();
    DB::query("SELECT `AccountID` FROM `user_pin` WHERE `AccountID` = '$accountID'");
    if(DB::count() < 1) {
        DB::query("INSERT INTO `user_pin` (`RowID`, `AccountID`, `pin1`, `pin2`, `pin3`, `pin4`) VALUES (NULL, '$accountID', '$pin[0]', '$pin[1]', '$pin[2]', '$pin[3]');");
        DB::commit();
    } else {
        DB::rollback(); //undo any changes
        return 0;
    }
    return 1;
}

function verifyPin($accountID, $inPin) {
    $query = DB::query("SELECT * FROM `user_pin` WHERE `AccountID` = '$accountID'");
    $dbPass = array_values($query);

    $verified = false;
    for($i =0; $i < strlen($inPin); $i++){
        /*
        *password_verify returns true or false if the hash value matches
        *so, if true (1), $verified = true;
        *This iterates through each entry for a pin in the
        * db: pin1, pin2, pin3 ..
        */
        if(password_verify(substr($inPin, $i, 1), $dbPass[0]['pin'.($i+1)]) == 1) {
            $verified = true;
        } else {
            $verified = false;
        }
    }
    return $verified;
}

function accountHasPin($accountID) {
    DB::query("SELECT `AccountID` FROM `user_pin` WHERE `AccountID` = '$accountID'");
    if(DB::count() > 0) {
        return 1;
    } else {
        return 0;
    }
}

function generatePurchaseCode($inID) {

    $token =random_strings(7);

    /*
     * Check to see if there is already a valid token live,
     * if there is,
     * invalidate it before inserting new one
     */
    DB::startTransaction();
    $mostRecent = DB::queryFirstRow("SELECT `RowID` FROM `user_purchase_token` WHERE `UserID` = $inID ORDER BY `RowID` DESC"); //get last token row
    if(DB::count() > 0) {
        $idToPass = $mostRecent['RowID'];
        DB::query("UPDATE `user_purchase_token` SET `valid`= 0 WHERE `RowID` = $idToPass;"); //invalidate it
        DB::commit();
    } else {
        DB::rollback(); //undo any changes
    }

    DB::startTransaction();
    DB::query("INSERT INTO `user_purchase_token` (`RowID`, `UserID`, `token`, `expireTime`, `valid`, `lastModified`) VALUES (NULL, '$inID', '$token', '" . (time()+60) . "', '1', CURRENT_TIMESTAMP);");
    if(DB::affectedRows() > 0) {
        DB::commit();
    } else {
        DB::rollback(); //undo any changes
        return 0;
    }

    return $token;
}

function usePurchaseCode($inToken, $userName) {

    /*
     * Check to see if token is present, then compare userName with
     * account id associated with token
     */
    DB::startTransaction();
    $query = DB::queryFirstRow("SELECT * FROM `user_purchase_token` WHERE `token` = '$inToken'"); //info regarding token
    if(DB::count() > 0) {
        if($query['UserID'] == getIDFromUserName($userName)) {
            //continue
            if($query['valid'] == 0) {
                return 0; //token not valid anymore
            }
        } else {
            return 0;
        }
        /*
        echo "<br />";
        echo "Token time: " . $query['expireTime'];
        echo "<br /> Current time: " . time();
        */

        if($query['expireTime'] < time()) {
            return 0; //token has expired
        }

        DB::query("UPDATE `user_purchase_token` SET `valid`= 0 WHERE `token` = '$inToken';"); //invalidate it
        DB::commit();
    } else {
        return 0;
    }
    return 1;
}

function getIDFromUserName($userName) {
    $query = DB::queryFirstRow("SELECT `ID` FROM `user` WHERE `user_name` = '$userName'");
    if(DB::count() > 0) {
        return $query['ID'];
    } else {
        return null;
    }
}

/*
* Copied from some sort of stackoverflow solution, does what I need
*/
function random_strings($length_of_string)
{
    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shufle the $str_result and returns substring
    // of specified length
    return substr(str_shuffle($str_result),
        0, $length_of_string);
}


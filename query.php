<?php

require_once 'connect_now.php';
require_once 'method_isSessionValid.php';

// Shows all post data
/*
$postdata = file_get_contents("php://input");
echo $postdata;
 */


//Thank you ankit15697 @ geeksforgeeks.com
//https://www.geeksforgeeks.org/how-to-generate-a-random-unique-alphanumeric-string-in-php/
// This function will return a random 
// string of specified length 
function random_strings($length_of_string) 
{ 
  
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
  
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result),  
                       0, $length_of_string); 
} 
  

//will filter later
$action = $_POST['action'];
$type = $_POST['type'];

//These items should be present for every request besides LOGIN or REGISTER
$USER_ID = $_POST['UID'];
$USER_TOKEN = $_POST['TOKEN'];


//Checks here for valid session, skips check if action="login"
if(($action == "LOGIN" || $action == "login")) {
    //ignore session check
} else {
    //if session invalid, kill loading
    if(!isSessionValid($USER_ID, $USER_TOKEN)) {
        die("invalid_session");
    }
}

if(isset($action)) {
    switch($action) {
        case "LOGIN":
        case "login":
            $u = $_POST['username'];
            $p = $_POST['password'];
           
            //query to find info
            $row = DB::queryFirstRow("SELECT * FROM user WHERE user_name = '$u'");
            $userID = $row['ID']; //set ID for linking token with user
            $userAlias = $row['alias']; //set ID for linking token with user
            //echo "ID: " . $ID;
            
            //compare so we know it's an authentic user OLD
            //if($p == $row['password']) { // OLD COMPARE
            
            if(password_verify($p, $row['password']) == 1) { //New compare, compare pass with hashed pass from DB
                //generate token for use this session, so we dont pass PSW all over the place
                $token = random_strings(25); //set token here
                $hashedToken = password_hash($token, PASSWORD_BCRYPT); //extra layer of security? or redundant/unnecessary
                
                //insert token into db so we know session is authentic
                DB::query("INSERT INTO `user_token`(`ID`, `token`) VALUES ('$userID','$hashedToken')");
               
                //echo $token; //echo token for use in application
                //build json array
                $outAccount['ID'] = $userID;
                $outAccount['name'] = $userAlias;
                $outAccount['authString'] = $token; //let the original token stay in memory on Android app, 
                                                    //still not sure whether that is the best approach
                
                //Echo json account info
                echo json_encode($outAccount);
                
            } else {
                echo "invalid_login_credentials";
                echo "incorrect";
            }
            
            break;
            
        case "getBankAccounts":
            
            
             $userAccounts = DB::query("SELECT type,balance,cardNum,routingNum,wireNum,CVV FROM `user_account` WHERE `UserID` = '$USER_ID'");
             
             /*
             foreach($userAccounts as $row) {
                 //loop through each entry and assign each type (KEY) and balance (VALUE)
                 //associative array to convert to json format
                 //$collection[$row['type']] = $row['balance'];
                 echo $row['type']. ":" . $row['balance'];
             }
              */
             
             //echo $userAccounts;
             echo json_encode($userAccounts);
             //echo json_encode($collection);
             
            break;
            
        case 'adjustBalance':
            /*
             * 
             */
            
            //UPDATE `user_account` SET `balance` = `balance` + 100 WHERE `ID` = '1' AND `type`='1'
            break;

        case 'TransferRequest':

            $requestUserID = $_POST['requestID'];
            $transferAmount = $_POST['transferAmount'];
            $transferAmount = round($transferAmount, 2); //round to 2 decimals to the right of decimal
            $transferType = $_POST['transferType'];
            $query = DB::query("INSERT INTO `user_request` (`RowID`, `fromID`, `toID`, `transferType`, `amount`, `note`, `time`, `viewed`) VALUES (NULL, '$USER_ID', '$requestUserID', '$transferType', '$transferAmount', 'no_note', CURRENT_TIMESTAMP, '0')");
            $isSuccess = DB::affectedRows();
            if($isSuccess > 0) {
                echo "success";
            } else {
                echo "failure";
            }

            break;
           
        case "test":
            //echo "getBankAccounts \n";
            //echo "Session Valid: " . isSessionValid($USER_ID, $USER_TOKEN);
            break;
        
        default:
            echo "error_100";
            break;
    }
} else {
    echo "error_1";
}






//json_encode();


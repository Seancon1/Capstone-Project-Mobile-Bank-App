<?php

require_once 'connect_now.php';

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
                echo "incorrect";
            }
            
            break;
            
        case "getBankAccounts":
            
            if(isSessionValid($USER_ID, $USER_TOKEN))
            {
             $userAccounts = DB::query("SELECT type,balance,cardNum,routingNum,wireNum,CVV FROM `user_account` WHERE `ID` = '$USER_ID'");
             
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
             
            } else {
                echo "bad_token";
            }
            break;
            
        case 'adjustBalance':
            /*
             * 
             */
            
            //UPDATE `user_account` SET `balance` = `balance` + 100 WHERE `ID` = '1' AND `type`='1'
            break;
        
        case 'authorize':
            if(isSessionValid($USER_ID, $USER_TOKEN)) {
                echo "1"; //valid
            } else {
                echo "0"; //invalid
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


//Function to check if current session is valid
/*
 * ID & TOKEN is expected to be passed with every transaction after login
 * 
 * ID of user
 * TOKEN of user
 */
function isSessionValid($ID, $inToken) {
    //TokenRequest returns the most recently assigned token from the DB
    $tokenRequest = DB::queryFirstRow("SELECT `token` FROM `user_token` WHERE `ID` = '$ID' ORDER BY `time` DESC");
    
    //inToken will be inserted by POST request and will be compared here before
    //any information will be given
    
    //check hashed token
    
    //echo "$inToken" . " - " . $tokenRequest['token'] . "";
    
    //Compare the hash value of the user's token ID with hashed value
    if(password_verify($inToken, $tokenRequest['token']) ==1) {
        return true;
    } else {
        return false;
    }
    
    
    /*
    if($inToken === $tokenRequest['token']) {
        return true;
        //return true; //Token is valid
    } else {
        //return false;  //token is not valid
        return false;
    }
     */
     
    
}


//json_encode();


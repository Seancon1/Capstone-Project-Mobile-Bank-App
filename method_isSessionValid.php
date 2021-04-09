<?php

require_once 'connect_now.php';

//Function to check if current session is valid
/*
 * ID & TOKEN is expected to be passed with every transaction after login
 *
 * ID of user
 * TOKEN of user
 */
function isSessionValid($ID, $inToken) {
    //tokenRequest returns the most recently assigned token from the DB
    $tokenRequest = DB::queryFirstRow("SELECT `token` FROM `user_token` WHERE `ID` = '$ID' ORDER BY `time` DESC");

    //echo "$inToken" . " - " . $tokenRequest['token'] . "";

    //Compare the hash value of the user's token ID with hashed value
    if(password_verify($inToken, $tokenRequest['token']) == 1) {
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

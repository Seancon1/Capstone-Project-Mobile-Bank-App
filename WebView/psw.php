<?php
include_once 'resources/header.php';

echo time();

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
/**
 * Password test case, wanted to make sure the passwords could be verified before implementing comparison where it mattered
 */

/*

$un = $_GET['un'];
$pass = $_GET['pa'];
print_r($_GET);

//$hashedPA = password_hash($pass, PASSWORD_BCRYPT);

        $getPassword = DB::queryFirstRow("SELECT `password`FROM `user` WHERE `user_name` = '$un'");
        print_r($getPassword);
        echo "Password from DB: " . $getPassword['password'] . " ";

$verifyInput = password_verify($pass, $getPassword['password']);

echo "<p></p>";
echo "This hashed: " . $hashedPA . "\r\n";
echo " <p>&nbsp;</p>";
echo "Verifiable with DB hash: [" . $verifyInput . "]";
*/
 /*
$in = $_GET['in'];
echo $in[0] . " - ";
echo $in[1] . " - ";
echo $in[2] . " - ";
echo $in[3] . " - ";
echo "<br /> Submitting new pin for AccountID 0: " . submitNewPin(1, 6969);

echo "<br /> Verifying pin for AccountID 0: " . verifyPin(1, 1234);
echo "<br /> Verifying pin for AccountID 0: " . verifyPin(1, 6969);
echo "<br /> Verifying pin for AccountID 0: " . verifyPin(1, 12400);
echo "<br /> Verifying pin for AccountID 0: " . verifyPin(1, 6969 . "");
echo "<br /> Verifying pin for AccountID 0: " . verifyPin(1, 69690);
echo "<br /> Account has pin 0: " . accountHasPin(0);
echo "<br /> Account has pin 1: " . accountHasPin(1);
echo "<br /> Account has pin 2: " . accountHasPin(2);
echo "<br /> has pin 100 : " . accountHasPin(100);



testShowPin(12312312);
testShowPin(5035);
testShowPin(6969);
*/
echo "Used:" . usePurchaseCode("gT15bLm", "b");

include_once 'resources/footer.php';


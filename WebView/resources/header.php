<?php session_start(); ?>
<!doctype html>
<?php

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

//Thank you Bootstrap for template
// https://getbootstrap.com/docs/4.0/getting-started/introduction/

require_once '../connect_now.php';
require_once '../method_isSessionValid.php';
require_once './resources/global_functions.php'; //Include global functions

//Populate these values here so we can run isSessionValid()
// without passing values into the method
// isSessionValid($USER_ID, $USER_TOKEN) -> isSessionValid()
// making it easier to work with
$USER_ID = $_GET['id'];
$USER_TOKEN = $_GET['token'];

$USER_PAGE = $_GET['page'];

//Set/checks for each session so that I don't need to recall the method elsewhere
if(!isset($_GET['id']) && !isset($_GET['token'])) {
    //if either GET for id or token isn't set, then populate with SESSION values
    //echo "Populating auth with Session values";
    $USER_ID = $_SESSION['id'];
    $USER_TOKEN = $_SESSION['token'];
}
//For when there isn't a GET page value, resume the last valid GET page value;
if(!isset($_GET['page'])) {
    $USER_PAGE = $_SESSION['page'];
}


$SESSION_VALID = isSessionValid($USER_ID,$USER_TOKEN);



/**
 * Methods
 */

function appendAuthDetails($USER_ID, $USER_TOKEN) {
    return "&id=' . $USER_ID . '&token=' . $USER_TOKEN;";
}

$SESSION_AUTH_DETAILS = appendAuthDetails($USER_ID, $USER_TOKEN);


?>

<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>WebView</title>
  </head>
  <body>
  <div class="container-fluid">
      
  


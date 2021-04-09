<?php
include_once 'resources/header.php';

/**
 * Open account option
 */
function display_OpenAccountOption() {
    ?>
    <div class="card">
        <img class="card-img-top" src="" style="background-color: lightskyblue; padding: 25px;">
        <div class="card-body">
            <h4 class="card-title">Open a New Account</h4>
            <p class="card-text">Open a new debit, credit, or savings account here.</p>
            <a href="?do=openNewAccount" class="btn btn-primary stretched-link">Open New Account</a>
        </div>
    </div>
    <p>&nbsp;</p>
    <?php
}
?>
<?php
//?do triggers an action to be observed inside the Android Application
?>
<?php
//Thank you Bootstrap : https://www.w3schools.com/bootstrap4/bootstrap_cards.asp
/*
?>
    <div class="card" style="width:400px">
  <img class="card-img-top" src="img_avatar1.png" alt="Card image">
  <div class="card-body">
    <h4 class="card-title">John Doe</h4>
    <p class="card-text">Some example text.</p>
    <a href="#" class="btn btn-primary">See Profile</a>
  </div>
</div>


<div class="card" style="width:400px">
        <img class="card-img-top" src="" alt="Card image" style="background-color: lightgreen; margin-inside: 25px;">
        <div class="card-body">
            <h4 class="card-title">Open a New Account</h4>
            <p class="card-text">Open a new debit, credit, or savings account here.</p>
            <a href="?do=showall" class="btn btn-primary stretched-link">Open New Account</a>
        </div>
    </div>
    */
?>
<p>&nbsp;</p>
    <div class="card">
        <img class="card-img-top" src="" style="background-color: darkblue; padding: 25px;">
        <div class="card-body">
            <h4 class="card-title">Welcome <?php echo getUserDataOfID("firstName", $USER_ID); ?></h4>
            <p class="card-text"></p>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    New Messages
                    <span class="badge badge-primary badge-pill"><?php echo getUnViewedNotificationCount($USER_ID); ?></span>
                </li>
            </ul>
            <p class="card-text"></p>
            <p><a href="?do=accountsettings" class="btn btn-primary stretched-link">My Account</a></p>
        </div>
    </div>
<p>&nbsp;</p>
<?php
//Check the number of accounts open.
//Disable all other options until they successfully open an account.

DB::query("SELECT type,balance,cardNum FROM `user_account` WHERE `UserID` = '$USER_ID'");
$numberOfAccounts = DB::count();
if($numberOfAccounts < 1) {
    display_OpenAccountOption();
    die("<div class=\"alert alert-info\" role=\"alert\">More options will be available after you open an account.</div>");
}
?>

<!--
    <div class="card">
        <img class="card-img-top" src="" style="background-color: rebeccapurple; padding: 25px;">
        <div class="card-body">
            <h4 class="card-title">BETA Actions</h4>
            <p class="card-text">These are test actions that allow you to populate your account with sample data.</p>
            <a href="?do=beta" class="btn btn-primary stretched-link">View</a>
        </div>
    </div>
 <p>&nbsp;</p>
 -->
    <div class="card">
        <img class="card-img-top" src="" style="background-color: lightgreen; padding: 25px;">
        <div class="card-body">
            <h4 class="card-title">My Accounts</h4>
            <p class="card-text">View the bank accounts you have with us.</p>
            <a href="?do=showall" class="btn btn-primary stretched-link">View</a>
        </div>
    </div>
<p>&nbsp;</p>
    <div class="card">
        <img class="card-img-top" src="" style="background-color: yellow; padding: 25px;">
        <div class="card-body">
            <h4 class="card-title">Make a Purchase</h4>
            <p class="card-text">Generate unique purchase tokens to purchase things with!</p>
            <a href="?do=genPurchaseToken" class="btn btn-primary stretched-link">Generate Token</a>
        </div>
    </div>

    <p>&nbsp;</p>
    <div class="card">
        <img class="card-img-top" src="" style="background-color: orangered; padding: 25px;">
        <div class="card-body">
            <h4 class="card-title">Send or Request Money</h4>
            <p class="card-text">Send or request money from other people.</p>
            <p style="float: left;"><a href="?do=transfer" class="btn btn-primary stretched-link">Transfer</a></p><p style="float: right;"><a href="?do=transferView" class="btn btn-primary stretched-link">View</a></p>
        </div>
    </div>


    <p>&nbsp</p>
    <?php
    //This shows if the user has an account open.
    if($numberOfAccounts > 0) {
        display_OpenAccountOption();
    }
    ?>

    <div class="card">
        <img class="card-img-top" src="" style="background-color: gray;">
        <div class="card-body">
            <!--<h4 class="card-title"></h4>-->
            <p class="card-text">Want to close your account or change your name? Settings is the place to do that.</p>
            <p><a href="?do=accountsettings" class="btn btn-primary stretched-link">Settings</a></p>
        </div>
    </div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>





<?php
include_once 'resources/footer.php';

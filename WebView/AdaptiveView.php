<?php
include_once 'resources/header.php';

/**
 * Set them when they are both present, if either one is missing
 * don't replace the session values.
 */

if(isset($_GET['id']) && isset($_GET['token'])) {
    $_SESSION['id'] = $_GET['id'];
    $_SESSION['token'] = $_GET['token'];
}


?>

    <?php
/*
        echo "<p>DEBUG PARAMS: <br />";
        echo "GET: " . print_r($_GET);
        echo "<br /> POST:" . print_r($_POST);
        echo "<br /> SESSION:" . print_r($_SESSION);
        echo "</p>";
*/


    ?>

    <?php
if($_GET['page'] == "demoPurchase" || $_POST['page'] == "demoPurchase") {
    include_once "AdaptiveView_DoAPurchase.php";
}

if($SESSION_VALID) {

    if (isset($USER_PAGE)) {

        switch ($USER_PAGE) {
            case "openNewAccount":
                include_once "AdaptiveView_OpenAccount.php";
                break;
            case "showall":
                include_once "AdaptiveView_ShowAccounts.php";
                break;
            case "briefShowAll":
                include_once "AdaptiveView_AccountBriefOverview.php";
                break;
            case "accHistory":
                include_once "AdaptiveView_ShowAccountHistory.php";
                break;
            case "transfer":
                $_SESSION['transfer'] = true;
                include_once "AdaptiveView_MoneyTransfers.php";
                break;
            case "transferView":
                include_once "AdaptiveView_MoneyTransfersView.php";
                break;
            case "accountsettings":
            case "myaccount":
                include_once "AdaptiveView_MyAccount.php";
                break;
            case "notifs":
                include_once "AdaptiveView_ShowAccountMsgs.php";
                break;
            case "pinsetup":
                include_once "AdaptiveView_Account_PinSettings.php";
                break;
            case "genPurchaseToken":
                include_once "AdaptiveView_Account_GeneratePurchaseCode.php";
                break;
            case "beta":
                include_once "AdaptiveView_BetaActions.php";
                break;
            }

            //Set page value to session value for backup
            $_SESSION['page'] = $USER_PAGE;

        } else {
            echo "No page action found.";
        }

    } else {
        echo "Not a valid session, you may need to restart your application.";
    session_destroy(); //No longer need any form of session value since invalid session
    session_unset();
}


    ?>


<?php
include_once 'resources/footer.php';

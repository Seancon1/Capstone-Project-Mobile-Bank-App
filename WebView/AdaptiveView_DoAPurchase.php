<?php

?>
<div style="max-width: 350px; min-width: 350px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p></p>

    <form class="form-inline">
        <div class="form-group">
            <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
            <div class="input-group">
                <div class="input-group-addon">$</div>
                <input type="number" class="form-control" id="exampleInputAmount" placeholder="Amount" name="inputAmount" required>
            </div>
        </div>
        <div class="form-group">
            <label class="sr-only" for="exampleInputAmount">Username</label>
            <div class="input-group">
                <input type="text" class="form-control"placeholder="Username" name="inputUN" maxlength="25" required>
            </div>
        </div>
        <div class="form-group">
            <label class="sr-only" for="exampleInputAmount">Purchase Code</label>
            <div class="input-group">
                <input type="text" class="form-control"placeholder="Code" name="inputPurchaseCode" maxlength="7" required>
            </div>
        </div>
        <input type="hidden" name="page" value="demoPurchase">
        <button class="btn btn-info" name="action" value="gen" type="submit">Confirm Purchase</button>
    </form>

</form>
</div>
<p>&nbsp;</p>
<?php

if($_POST['action'] == "gen") {

    $amount = abs($_POST['inputAmount']);
    $token = $_POST['inputPurchaseCode'];
    $userName = $_POST['inputUN'];

    /*
    echo " " . (integer) $amount;
    echo " " . $userName;
    echo " " . $token;
    */

    if(isset($amount) && isset($token) && isset($userName)) {

        if(usePurchaseCode($token, $userName)) {
            //$userDetails = getUser_BankAccountDetailsFromID(getIDFromUserName($userName));
            if(doUserTransaction(getUserCheckingAccID(getIDFromUserName($userName)),getIDFromUserName($userName), 0, "PrestigeCode MerchID #4234", -$amount)) {
                sendUserMsg("Online Purchase Made", "A purchase of (-$" . $amount . ") was made online with your checking account.", getIDFromUserName($userName));
            } else {
                echo "Transaction could not be performed.";
            }
    ?>
            <div class="alert alert-success">
                <h3>Purchased</h3>
                <p>You have purchased the item.</p>
            </div>

    <?php
} else {
    echo "<p class=\"alert alert-warning\">Not a valid purchase code combination.</p>";
}

} else {
    echo "Input parameters are invalid.";
}
}

exit("");




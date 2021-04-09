<?php


if(isUserCheckingPresent($USER_ID)) {
    if(accountHasPin(getUserCheckingAccID($USER_ID))) {
        exit("Already has a pin.");
    }
} else {
    exit("You need a checking account to perform this operation.");
}


//Verify pin is correct format
$pin = $_POST['inputPIN'];
$pin2 = $_POST['inputPIN2'];
/*
echo "[" . $pin . "]";
echo var_dump(is_integer((int)$pin));
echo " - ";
echo var_dump(strlen($pin));
echo "BOTH are the same: ";
echo var_dump($pin == $pin2);
*/
if(isset($_POST['inputPIN'])&& isset($_POST['inputPIN'])) {
    if(is_integer((int)$pin) && (strlen($pin) == 4) && ($pin == $pin2)) {
        //echo "Pin satisfies requirements";
    if(!submitNewPin(getUserCheckingAccID($USER_ID), $pin)) {
        echo "";
        exit("An error occurred setting your pin.");
    } else {
        exit("<p class='alert alert-success'>You have set your pin, don't forget it! <a href='AdaptiveView.php?page=showall'> Return</a></p>");
    }

    } else {
        echo "<p class='alert alert-warning'>Please use a valid 4 digit pin and make sure the pins match.</p>";
    }
}

?>

<h2>PIN Settings</h2>
<p>Please enter a PIN for your checking account.</p>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="form-group">
        <label for="inputPIN">PIN</label>
        <input type="password" class="form-control" name="inputPIN" id="inputPIN" placeholder="4 digit pin" value="" maxlength='4' required>
        <small class="form-text text-muted">This will be used when purchasing items.</small>
    </div>

    <div class="form-group">
        <label for="inputPIN2"></label>
        <input type="password" class="form-control" name="inputPIN2" id="inputPIN2" placeholder="Re-enter 4 digit pin" value="" maxlength='4' required>
        <small class="form-text text-muted">Just to double check. :)</small>
    </div>

    <button class="btn btn-primary" name="action" value="submitPin">Submit</button>
</form>


 <?php





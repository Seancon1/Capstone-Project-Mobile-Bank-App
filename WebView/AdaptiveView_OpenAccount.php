
<?php

//var_dump($_POST);
if(!empty($_POST['action']) && isset($_POST['action'])) {
    switch($_POST['action']) {
        case "checking":
            if(submitNewCheckingAccount($USER_ID) == 1) {
                echo "<div class=\"alert alert-success\" role=\"alert\">You now have a checking account!</div>";
            } else {
                echo "<div class=\"alert alert-danger\" role=\"alert\">Something didn't go quite as planned.</div>";
            }//run function
            break;
        case "credit":
            if(isUserCreditAccPresent($USER_ID)) {
                    echo "<div class=\"alert alert-warning\" role=\"alert\">You qualify for only one Credit account right now, sorry.</div>";
                } else {
                    if(submitNewCreditAccount($USER_ID) == 1) {
                        echo "<div class=\"alert alert-success\" role=\"alert\">You have been instantly approved for an account. You now have a checking account!</div>";
                    } else {
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Something didn't go quite as planned.</div>";
                    }//run function
                }

            break;
        case "saving":
            if(isUserSavingsAccPresent($USER_ID)) {
                echo "<div class=\"alert alert-warning\" role=\"alert\">We only offer one savings account per user profile, sorry.</div>";
            } else {
                if(submitNewSavingsAccount($USER_ID) == 1) {
                    echo "<div class=\"alert alert-success\" role=\"alert\">Your savings account is now available.</div>";
                } else {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Something didn't go quite as planned.</div>";
                }//run function
            }
            break;
    }
    exit(); //stop the rest of the page from loading
}

?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
    <?php


    //Check if user has accounts already open.

    if(isUserCheckingPresent($USER_ID)) {
        echo "<div class=\"alert alert-secondary\" role=\"alert\">Seems that you already have a checking account. At the moment you can only have one.</div>";
    } else {
    ?>

        <div class="card">
            <div class="card-header">
                Featured
            </div>

            <div class="card-body">
                <h5 class="card-title">Open a Checking Account</h5>
                <p class="card-text">With a checking account you can purchase items electronically.
                    You can also send to and receive money from your friends!</p>
                <button type="submit" class= "btn btn-success" name="action" value="checking">Open a Checking account</button>
            </div>
        </div>

    <?php

    }

    if(isUserCheckingPresent($USER_ID)) {
        ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Credit Account</h5>
                <p class="card-text">You have the option to open a sudo Credit account that you can make credit
                    purchases with.</p>
                <button type="submit" class="btn btn-success" name="action" value="credit">Apply now</button>
            </div>
        </div>
        <p></p>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Open a savings account</h5>
                <p class="card-text">A savings account will allow you to save money that you don't want on your Checking
                    account.</p>
                <button type="submit" class="btn btn-success" name="action" value="saving">Open an account</button>
            </div>
        </div>


        <?php
    }
?>

</form>


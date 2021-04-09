
<h2>Purchase Codes</h2>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p>Click the button below in order to generate a new token. BEWARE the code only works once and will expire in one minute.
        If you generate a new token, the others will expire. Be careful and don't share with anyone.</p>
    <?php

    if($_POST['action'] == "gen") {

        ?>
        <h4>Code:</h4>
        <h3 class="alert alert-success"><?php echo " <b>" . generatePurchaseCode($USER_ID) . "</b>"; ?></h3>
        <?php
    }
    ?>

    <button class="btn btn-info" name="action" value="gen">Generate New Purchase Code</button>
</form>



 <?php


/*
$items[] = "";
var_dump(getUser_BankAccountDetailsFromID($USER_ID, 0));
*/





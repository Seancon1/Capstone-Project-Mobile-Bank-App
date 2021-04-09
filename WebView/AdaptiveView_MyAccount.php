
<h2>Settings</h2>
    <?php

    //Call data by specifying user data field. e.g. $userData['firstName']
    $userData = getUserData($USER_ID);

    if(!empty($_POST['action']) && isset($_POST['action'])) {
        include_once "AdaptiveView_ShowAccountMsgs.php";
    }

    ?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">

        <div class="card">
            <div class="card-header">
                Your Information
            </div>

            <div class="card-body">
                <h5 class="card-title"><?php echo $userData[0]['firstName'] . " " . $userData[0]['lastName']; ?></h5>
                <table class="table table-borderless">
                    <tbody>
                    <tr>
                        <td><b>Phone #</b></td>
                        <td><?php echo "".$userData[0]['PrimaryPhone']; ?></td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td>
                        <td><?php echo "".$userData[0]['userEmail']; ?></td>
                    </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col">
                        <p class="card-text"></p>
                        <p class="card-text"></p>
                    </div>
                </div>
                <!--<button type="submit" class="btn btn-success">Edit Information</button>-->
            </div>
        </div>
<p>&nbsp</p>
    <div class="card" style="width: auto;">
        <div class="card-body">
            <h5 class="card-title">Notifications</h5>
            <input class="btn btn-primary" type="submit" name="action" value="View Messages (<?php echo getUnViewedNotificationCount($USER_ID); ?>)" />
        </div>
    </div>
<p>&nbsp;</p>

    <div class="card">
            <div class="card-header">
                Your Accounts
            </div>

            <div class="card-body">
                <p>Your accounts at a glance:</p>
                <table class="table table-borderless">
                    <tbody>
                    <?php
                    $bankAccounts = getUserBankInfo_Simple($USER_ID);

                        foreach($bankAccounts as $account) {


                        ?>
                            <tr>
                                <td><b>Account (<?php echo substr($account['cardNum'], 12, 4) . ") - Balance ". $account['balance']; ?></td>
                            </tr>

                        <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
</form>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
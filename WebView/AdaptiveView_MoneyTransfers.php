
    <?php

    //Experimental, User reached the point where redirection has happened. So reset.
    /*
    if($_SESSION['transfer_step'] > 2) {
        $_SESSION['transfer_step'] = 0; //set back to 0
    }
    */

    $userEmail = $_POST['userEmail'];

    /*
    $transferStep = $_SESSION['transfer_step'];

    switch($transferStep) {
        case 0:
            echo "";
            break;
    }
    */

    //Check if email is available in system
    $temp_userEmail = $_POST['inputEmail'];
    if(isset($temp_userEmail)) {
        $userSelect = DB::queryFirstRow("SELECT * FROM `user_personal` WHERE `userEmail` = '$temp_userEmail'"); // OK
        $userExist = DB::count();
        $userOutID = $userSelect['ID'];
        $recipientNameQuery = DB::queryFirstRow("SELECT * FROM `user` WHERE `ID` = '$userOutID'");
        $recipientName = $recipientNameQuery['alias'];
        if($userExist > 0) {
            echo "<b style='margin: 50px;'>User found. <a href='?do=OpenTransfer&outID=" . $userOutID ."&recipientName=" . $recipientName . "'>Click here to continue</a></b>";
            die();
            //header("Location: /?outID=" . $userOutID . "&do=OpenTransfer");
        } else {
            echo "<p>&nbsp;</p><br /><b style='color: red;'>We could not find a user under that email.</b>";
        }
    }

    ?>

    <div style="margin: 25px; align-content: center;">
        <form name="transfer" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">

            <p>Please enter the email address of the person you want to send to or request from.</p>
            <div class="form-group">
                <label for="inputEmail">Email address:</label>
                <input type="email" class="form-control" name="inputEmail" id="inputEmai1" aria-describedby="emailHelp" placeholder="Enter email" value="<?php echo $_SESSION['userEmail']; ?>" required>
                <small id="emailHelp" class="form-text text-muted">The email is used to identify the user on our side.</small>
            </div>

            <button type="submit" class="btn btn-primary">Continue</button>
        </form>
    </div>







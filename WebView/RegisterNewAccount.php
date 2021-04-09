<?php

session_start();

include_once 'resources/header.php';



/*
 * POST values to work with, set upon SELF submit 
 */
$inUN = $_POST['inputUN'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$inEmail = $_POST['inputEmail'];
$inPass = $_POST['inputPassword1'];
$inPass2 = $_POST['inputPassword2'];

//POST: update
//here to add compatibility for updating instead of just registering
if(!empty($_POST['update']) && isset($_POST['update'])) {
    $updatingInfo = true;
}


/*
 * Populate session values
 */
$_SESSION['UN'] = $inUN;
$_SESSION['firstName'] = $firstName;
$_SESSION['lastName'] = $lastName;
$_SESSION['email'] = $inEmail;

if($updatingInfo) {
    //$_SESSION['UN'] = getUserDataOfID("", $USER_ID);
    $_SESSION['firstName'] = getUserDataOfID("firstName", $USER_ID);
    $_SESSION['lastName'] = getUserDataOfID("lastName", $USER_ID);
    $_SESSION['email'] = getUserDataOfID("email", $USER_ID);
}
/*
 * Debug settings
 */
//print_r($_POST);
$disable = 0;


/*
 * CHECK if post data is set, then we continue with executing registration
 */
if(isset($_POST['inputUN']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['inputEmail']) 
        && isset($_POST['inputPassword1']) && ($disable ==0)) {

        //Check for already used names/emails
        if(canUserRegister($inUN, $inEmail, $inPass, $inPass2)) {

            //echo "Registration New USERID #" . registerUser($inUN,$firstName,$inPass);
            try{
                addUserPersonal(registerUser($inUN,$firstName,$inPass), $firstName, $lastName, $inEmail);
                sendUserMsg("Welcome", "Thank you for making an account with us. If you have any concerns, please email help@prestigecode.com", getIDFromUserName($inUN));
                session_destroy(); // IMPORTANT for security reasons
                echo "<p>&nbsp;</p>";
                echo "<div style='padding: 5%;' class='alert alert-success'><p>You have successfully registered your account.</p></div>";
                echo "<p>&nbsp;</p>";
                exit("");
            } catch (Exception $ex) {
               echo "Error : $ex";
            }

        }
}

//Update revision
if(isset($_POST['inputUN']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['inputEmail'])
    && isset($_POST['inputPassword1']) && ($_POST['update'] == 1)) {

}



function canUserRegister($thisInUN, $thisInEmail, $thisInPass1, $thisInPass2) {
    $error = [];
    $checkUserQuery = DB::queryFirstRow("SELECT * FROM `user` WHERE `user_name` = '$thisInUN'");
    if(empty($checkUserQuery['user_name'])) {
        //Name is available
        //echo "name not in use " . $checkUserQuery['user_name'];
    } else {
        array_push($error, "Username in use already.");
        if(isset($_SESSION['UN'])) {
            unset($_SESSION['UN']);
        }
    }
    
    $emailCheckQuery = DB::queryFirstRow("SELECT userEmail FROM `user_personal` WHERE `userEmail` = '$thisInEmail'");
    
    //check email
    if(empty($emailCheckQuery['userEmail'])) {
        //echo "email not in use " . $emailCheckQuery['email'];
    } else {
        array_push($error, "Email is currently being used by another user.");
                if(isset($_SESSION['email'])) {
            unset($_SESSION['email']);
        }
    }
   
    if($thisInPass1 == $thisInPass2) {
        //echo "email not in use " . $emailCheckQuery['email'];
    } else {
        array_push($error, "Both passwords must match.");
    }
    
    if(!empty($error)) {
        foreach($error as $key => $value) {
            echo "<p style='color:red;'>". $value . "</p> \n";
        } 
        return false; //Cannot
    } else {
       return true; //Can register 
    }   
    
}

function registerUser($this_UN, $this_firstName, $this_inPass) {
    
        //Hash password
    $hashPass = password_hash($this_inPass, PASSWORD_BCRYPT);
    $doRegisterUser = DB::query("INSERT INTO `user` (`ID`, `user_name`, `alias`, `password`) VALUES (NULL, '$this_UN', '$this_firstName', '$hashPass')"); //OK
    
    $newUserID = DB::queryFirstRow("SELECT `ID` FROM `user` WHERE `user_name` = '$this_UN'"); // OK
    //echo "USER ID: " . $newUserID['ID'];
    return $newUserID['ID'];
}


function addUserPersonal($this_userID, $this_fn, $this_ln, $this_email) {
    /*
        $getPassword = DB::query("SELECT `password` FROM `user` WHERE `ID` = '$newUserIDUsable'");
        echo "PASSWORD RETRIEVED: " . $getPassword['password'] . " ";
        echo "PASSWORD VERIFY: " . password_verify($inPass, $getPassword);
        
    */
    try{
        $doRegisterUserPersonal = DB::query("INSERT INTO `user_personal` (`ID`, `firstName`, `lastName`, `DOB`, `userEmail`, `PrimaryPhone`) VALUES ('$this_userID', "
                . "'$this_fn', '$this_ln', 'null', '$this_email', 'null')");
        //DB::query("INSERT INTO `user_personal` (`ID`, `email`) VALUES ('$this_userID', '$this_email')");
        
        //echo "[" .$doRegisterUserPersonal . "]";
        return "Success";
    } catch (Exception $e) {
        //echo $e;
        return "fail";
    }
        
}

?>

<div style="margin: 35px; align-content: center;">

    <?php
    if($updatingInfo) {
        echo "<h2>Updating your Information</h2>";
    } else {
        echo "<h2>First Time New Account</h2>";
    }
    ?>


    <form name="register" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
        
    <div class="form-group">
        <label for="inputUN">Username</label>
        <input type="text" class="form-control" name="inputUN" id="inputUN" aria-describedby="emailHelp" placeholder="Enter desired username" value="<?php echo $_SESSION['UN']; ?>" maxlength='25' required>
        <small id="usernameHelp" class="form-text text-muted">You will use this to log in.</small>
      </div>
        
    <div class="form-group">
            <label for="inputFirstLast">First and Last Name</label>
            <div class="form-row">

            <div class="col">
              <input type="text" class="form-control" name="firstName" id="firstName" placeholder="First name" value="<?php echo $_SESSION['firstName']; ?>" required>
            </div>
            <div class="col">
              <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Last name" value="<?php echo $_SESSION['lastName']; ?>" required>
            </div>
          </div>
    </div>
        
      <div class="form-group">
        <label for="inputEmail">Email address</label>
        <input type="email" class="form-control" name="inputEmail" id="inputEmai1" aria-describedby="emailHelp" placeholder="Enter email" value="<?php echo $_SESSION['email']; ?>" required>
        <small id="emailHelp" class="form-text text-muted">We use this to communicate with you.</small>
      </div>
      <div class="form-group">
        <label for="inputPassword1">Password</label>
        <input type="password" class="form-control" name="inputPassword1" id="inputPassword1" placeholder="Password" value="" required>
      </div>

        <?php
        if(isset($_POST['update']) && !empty($_POST['update'])) {
            ?>
            <button type="submit" class="btn btn-primary" name="update_status" value="update">Update</button>
            <?php
        } else {
        ?>
            <div class="form-group">
                <label for="inputPassword2">Reenter Password</label>
                <input type="password" class="form-control" name="inputPassword2" id="inputPassword2" placeholder="Password" required>
            </div>
      <button type="submit" class="btn btn-primary">Submit</button>
        <?php } ?>
    </form>
</div>

<?php

include_once 'resources/footer.php';

?>
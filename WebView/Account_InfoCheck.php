
<?php

function start() {

}

function doIterate($next) {
    //$_POST['next']

}

//Loop through each field in the account profile
// and check if it's correct

function generateCheckField($fieldName, $count) {

    $type = "";
    switch($fieldName) {
        case "DOB":
            $type = "date";
        break;
        case "userEmail":
            $type = "email";
        break;
        default:
            $type="text";
            break;
    }

    echo '<div class="form-group">
        <label></label><input type="hidden" name="next" value="' . $count .'"/>
        <input type="' .$type .'" class="form-control" name="'.$fieldName .'" value="'.getUserDataofID($fieldName, $USER_ID) .'" maxlength=\'25\' required>
       </div>';


}

?>

<form name="register" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">

    <input class="btn btn-info" type="submit" value="Submit" />
</form>

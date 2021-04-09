<?php



$msgID = $_POST['msgID'];
$action = $_POST['action'];

echo "<h2>Your Messages</h2>";
if($action == "showMsg") {
    if(isset($msgID) && !empty($msgID)) {
        $msgDetails = getUserMsg($msgID, $USER_ID);
        ?>
        <div class="card" style="width: auto;">
            <div class="card-body">
                <h5 class="card-title"><?php echo $msgDetails['title']; ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo "Sent: " . $msgDetails['time']; ?></h6>
                <p class="card-text"><?php echo $msgDetails['msg']; ?></p>
            </div>
        </div>
<?php
        if(setMsgViewed($msgID) == 0){
            echo "<div class=\"alert alert-light\" role=\"alert\">Could not set as viewed.</div>";
        }
    }
}



//$userAccount = DB::query("SELECT  FROM `user_account` WHERE `UserID` = '$USER_ID'");
$messages = DB::query("SELECT * FROM `user_notifications` WHERE `UserID` = '$USER_ID' ORDER BY time DESC");
$rowCount = DB::count();


?>


<table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">Viewed</th>
        </tr>
        </thead>
        <tbody>
            <?php

                    foreach($messages as $row) {
                        ?>
            <form name="transfer" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
            <input type="hidden" name="msgID" value="<?php echo $row['RowID']; ?>" />
                        <tr><a href="#">
                            <td><? echo $row['RowID']; ?></td>
                            <td><? echo "<button name='action' value='showMsg'>" . $row['title'] . "</button>"; ?></td>
                            <td><? echo $row['viewed']; ?></td>
                            </a>
                        </tr>
            </form>
                        <?php
                    }
                ?>

               <?php
               if($rowCount < 1) {
                   ?><p class="border border-secondary" style="background-color: #e8e9ea; text-color: darkgray; padding: 20px;">
                       It appears that you don't have any messages at this time.</p>
               <?php
               }

               /*
                * stop the rest of the page from loading, to stop showing all account info
                */
               exit();
               ?>
        </tbody>
    </table>
</form>


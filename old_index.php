<?php

/*
 * 
 *      String username = "bankApp";
        String password = "REDACTED";
 * 
 */


function method() {
    $link = mysqli_connect("localhost","bankApp","REDACTED", "clicky_");

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $query = "SELECT * FROM bla";

    if ($stmt = mysqli_prepare($link, $query)) {

        /* execute statement */
        mysqli_stmt_execute($stmt);

        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $name, $code);

        /* fetch values */
        while (mysqli_stmt_fetch($stmt)) {
            echo $name . " " . $code;
        }

        /* close statement */
        mysqli_stmt_close($stmt);
    }

    /* close connection */
    mysqli_close($link);
}
?>
<?php
    function hideMe() {
        $DBConnect = mysqli_connect("localhost","bankApp","REDACTED", "clicky_");
        $TableName = "bla";
        $SQLstring = "SELECT * FROM $TableName";
        $QueryResult = @mysqli_query($SQLstring, $DBConnect);
        
            /*
        while (($Row = mysqli_fetch_row($QueryResult)) !== FALSE) {
            echo "<tr><td>{$Row[0]}</td>";
            echo "<td>{$Row[1]}</td>";
            }
            */
        
    }
    
    function doFetchQuery() {
            mysql_connect("localhost", "bankApp", "REDACTED");
            mysql_select_db("clicky_");
            $SQLQuery = mysql_query("SELECT * FROM bla");
            
            $resultArray = array();
            
            while($row = mysql_fetch_array( $SQLQuery )) {
                //array_push($resultArray, $row['id'], $row['string']);
                $resultArray[$row['id']] = $row['string'];
            //echo $row['id'];
            //echo $row['string'];
            }
            
            echo json_encode($resultArray);
    }
    
        function doLogIn($user_name, $pass) {
            mysql_connect("localhost", "bankApp", "REDACTED");
            mysql_select_db("mobilebank");
            $SQLQuery = mysql_query("SELECT auth_token WHERE user_name = '$user_name';");
            
            $resultArray = array();
            
            while($row = mysql_fetch_array( $SQLQuery )) {
                //array_push($resultArray, $row['id'], $row['string']);
                $resultArray[$row['id']] = $row['string'];
            //echo $row['id'];
            //echo $row['string'];
            }
            
            echo json_encode($resultArray);
           
    }
    
    $queryType = $_GET['type'];
    //$postQuery = $_GET['query'];
    
    $action = $_GET['action'];
   
    $test = $_GET['WOW'];  
    
    echo $test + "\n";
    echo $test + "\n";
    
    if($action == "login") {
        doLogIn($);
    }
    
    if(!isset($action)) { 
        switch($type) {
            case "get":
                //do get query
            case "post":
                //do post query
                break;
            default:
                echo "error_type \n";
                break;
        }
       //doFetchQuery($postQuery); 
    } else {
        echo "no_query";
    }
    
       

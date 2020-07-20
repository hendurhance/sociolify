<?php

//Require files

require "../require/config.php";
require "../require/functions.php";

// Reset code
if (isset($_GET['code'])) {
    
    $reset_code = validate_input($_GET['code']);

    $sql = "SELECT * FROM users WHERE reset_code = '$reset_code'";
    $result = $connectdb->query($sql);

    if ($result->num_rows > 0) {
        
        // Data output

        $row = $result->fetch_assoc();
        $sql = "UPDATE users SET is_active = 1, reset_code = '' WHERE reset_code = '$reset_code'";

        echo "Verifying account...";

        if ($connectdb->query($sql) === TRUE) {
            $msg = "Your account is verified with your email";
            header("Location: ../login.php?message=$msg");
        }else{
            echo "Error verifying " . $connectdb->error;
        }
    }else{
        echo "No results";
    }


}
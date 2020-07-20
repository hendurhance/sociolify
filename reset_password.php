<?php
    session_start();
    
    require_once 'require/config.php';
    require_once 'require/functions.php';

    //Get the reset code from session
    $reset_code = $_SESSION['reset_code'];
    
    //Set all input to default
    $is_active = $password = $confirm_password = "";
    $passwordError = $confirm_passwordError = "";

    $count = 0;
    $msg = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $password = validate_input($_POST["password"]);
        $confirm_password = validate_input($_POST["confirmpassword"]);
        
        //Validate user input
        if (empty($_POST["password"])) {
            $passwordError = "Password is required";
            $count++;
        } else {
            $password = validate_input($_POST["password"]);
        }
        
        if (empty($_POST["confirmpassword"])) {
            $confirm_passwordError = "Confirm password is required";
            $count++;
        } else {
            $confirm_password = validate_input($_POST["confirmpassword"]);
            //Check if passwords match
            if($confirm_password != $password){
                $confirm_passwordError = "Password does not match";
                $confirm_password = "";
                $count++;
            } else {
                $confirm_password = validate_input($_POST["confirmpassword"]);
            }
        }
        
        //Check if there is no error
        if($count == 0){
            //Getting reset code from db
            $sql = "SELECT * FROM users WHERE reset_code = '$reset_code'";
            $result = $connectdb->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                //If the user is verified
                if($row['is_active'] == 1){
                    
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    //Update the user password and delete the reset code
                    $sql = "UPDATE users SET password = '$hash', reset_code = '' WHERE reset_code = '$reset_code'";
                    
                    if ($connectdb->query($sql) === TRUE) {
                    $msg = 'Your password has been reset';
                    header("Location: login.php?message=$msg");
                    //Unset the reset_code variable
                    session_unset();
                    exit();
                    } else {
                        echo "Error updating record: " . $connectdb->error;
                    }
                } else {
                    
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    //Update the user password only
                    $sql = "UPDATE users SET password = '$hash' WHERE reset_code = '$reset_code'";

                    if ($connectdb->query($sql) === TRUE) {
                        $msg = 'Your password has been reset';
                        header("Location: login.php?message=$msg");
                        //Unset the reset_code variable
                        session_unset();
                        exit();
                    } else {
                        echo "Error resetting password: " . $connectdb->error;
                    }
                }
            } else {
                echo "Something went wrong";
            }
        }
    }
?>

<!-- HTML DOCUMENT -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociolify | Reset Password</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/app.css">
    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>
    <!-- Bootstrap Framework-->
    <link rel="stylesheet" href="libs/css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="libs/css/fontawesome-all.min.css">
</head>
<body>
    
   
<!-- NAVIGATION -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-mine static-top">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img class="logo" src="assets/img/sociolify.png" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-align-right hamburger"></i>
        </button>
       <div class="collapse navbar-collapse" id="navbarResponsive">
         <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php"><i class="fas fa-home"></i>   Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i>   Login</a>
            </li>
         </ul>
       </div>
      </div>
    </nav>

<!-- FORM -->
   <section class="form">
     <div class="container">
       <h3 class="form-h3">RESET PASSWORD</h3>
       <p><?php 
              if($msg != ''){
              echo '<hr>';
              echo '<div class="alert alert-success" role="alert">';
              echo  $msg;
              echo '</div>';
          }?>
        </p>
       <div class="my-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <input type="password" name="password" placeholder="Password">
          <span class="error"><?php echo $passwordError ?></span>
          <input type="password" name="confirmpassword" placeholder="Confirm Password">
          <span class="error"><?php echo $confirm_passwordError; ?></span>
          <button type="submit" class="btn btn-submit">RESET PASSWORD</button>
        </form>
       </div>
     </div>
   </section>

</body>
    <!-- jQuery -->
    <script src="libs/js/jquery.slim.min.js"></script>
    <!-- Bootstrap Framework -->
    <script src="libs/js/bootstrap.bundle.min.js"></script>
    
</html>
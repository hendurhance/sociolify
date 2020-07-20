<?php

require "require/config.php";
require "require/functions.php";
require "libs/PHPMailer-master/PHPMailerAutoload.php";
    

    //Setting variables to default
    $reset_code = $is_active = $email = $emailError = "";
    $count = 0;
    $msg = '';
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
$email = validate_input($_POST["email"]);

//Validating our input
if (empty($_POST["email"])) {
    $emailError = "Email is required";
    $count++;
} else {
    $email = validate_input($_POST["email"]);
    
    // Check if email is invalid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Email is invalid";
        $count++;
    } else {
        //Checking if email exist in database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $connectdb->query($sql);
        
        if ($result->num_rows == 0) {
            $emailError = "Email not found";
            $email = "";
            $count++;
        } else {
            $row = $result->fetch_assoc();
            $is_active = $row['is_active'];
            $reset_code = $row['reset_code'];
        }
    }
}

//Free for errors
if ($count == 0){
    if($is_active == 1) {
        //Generate reset code
        $reset_code = md5(crypt(rand(), 'aa'));
        //Update the database delete password and insert the new reset_code
        $sql = "UPDATE users SET password = '', reset_code = '$reset_code' WHERE email = '$email'";
        
        if ($connectdb->query($sql) === TRUE) {
            
            $msg = 'You requested a password reset, please check email to reset your password';

            $message = "You requested a password reset. Click the link below to reset your password. <br><br> 
            <a href='https://socialify.herokuapp.com/process/reset_password_process.php?code=$reset_code'>Reset your password</a>";

                    //sending email to the user
            send_mail($email, $message);

            $email = $emailError = "";
            
        } else {
            echo "Error updating record: " . $connectdb->error;
        }
        
    } else {
        //Update password from database
        $sql = "UPDATE users SET password = '' WHERE email = '$email'";
        if ($connectdb->query($sql) === TRUE) {
            $msg = 'You requested a password reset, please check email to reset your password';
            
            $message = "You requested a password reset. Click the link below to reset your password. <br><br> 
            <a href='https://socialify.herokuapp.com/process/reset_password_process.php?code=$reset_code'>Click here to reset your password</a>";
            
            //sending email to the user
            send_mail($email, $message);

            $email = $emailError = "";
            
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
   }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociolify | Forget Password</title>
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
                <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i>   Login</a>
            </li>
         </ul>
       </div>
      </div>
    </nav>

<!-- FORM -->
   <section class="form">
     <div class="container">
       <h3 class="form-h3">Forget Password</h3>
       <p><?php 
              if($msg != ''){
              echo '<hr>';
              echo '<div class="alert alert-warning" role="alert">';
              echo  $msg;
              echo '</div>';}?>
        </p>
       <div class="my-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <input type="text" value="" name="email" placeholder="Email Address">
          <span class="error"><?php echo $emailError; ?></span><br>
          <span>We will send you a reset code</span>
          <button type="submit" class="btn btn-submit">RESET PASSWORD</button>
        </form>
        <p>Go back to Login? <a href="login.php">Login Here</a></p>
       </div>
     </div>
   </section>

</body>
    <!-- jQuery -->
    <script src="libs/js/jquery.slim.min.js"></script>
    <!-- Bootstrap Framework -->
    <script src="libs/js/bootstrap.bundle.min.js"></script>
    
</html>
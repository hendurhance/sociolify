<?php

// Require files
require "require/config.php";
require "require/functions.php";
require "libs/PHPMailer-master/PHPMailerAutoload.php";

//Set all input to default
$fname = $username = $email = $website = $password = $confirm_password = "";
// Set error variable to default
$nameError = $usernameError = $emailError = $websiteError = $passwordError = $confirm_passwordError = "";
$count = 0;
$msg = "";
// Validating and taking user info to database
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $fname = validate_input($_POST["fullname"]);
  $username = validate_input($_POST["username"]);
  $email = validate_input($_POST["email"]);
  $website = validate_input($_POST["website"]);
  $password = validate_input($_POST["password"]);
  $confirm_password = validate_input($_POST["confirmpassword"]);

  // Validate user input
  if (empty($_POST["fullname"])) {
    $nameError = "Full name is required";
    $count++;
  }else {
    $fname =  validate_input($_POST["fullname"]);
    // Remove invalid characters
    if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
      $nameError = "Invalid character";
      $count++;
    }
  }

//Validate username
  if (empty($_POST["username"])) {
    $usernameError = "Username is required";
    $count++;
  }else {
    $username =  validate_input($_POST["username"]);

    // SQL Query
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $connectdb->query($sql);

    if($result->num_rows > 0){
      $usernameError = "Username already exists";
      $count++;
    }
  }

// Validate emails
  if (empty($_POST["email"])) {
    $emailError = "Email is required";
    $count++;
  }else {
    $email =  validate_input($_POST["email"]);
    //Check for invalid emails
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $emailError = "Invalid email";
       $count++;
    }

    // SQL Query
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $connectdb->query($sql);

    if($result->num_rows > 0){
      $emailError = "Email already exists";
      $count++;
    }
  }

// Validate website url
  if (empty($_POST["website"])) {
    $websiteError = "Website url is required";
    $count++;
  }else {
    $website =  validate_input($_POST["website"]);
    // Check for invalid website address
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
      $websiteError = "Invalid website url, www is required";
      $count++;
    }
  }

// Validate password
  if (empty($_POST["password"])) {
    $passwordError = "Password is required";
    $count++;
  }else {
    $password =  validate_input($_POST["password"]);
  }

// Validate confirm password
  if (empty($_POST["confirmpassword"])) {
    $confirm_passwordError = "Confirm password is required";
    $count++;
  }else {
    $password =  validate_input($_POST["confirmpassword"]);
    // Check if password match
    if ($confirm_password != $password) {
      $confirm_passwordError = "Password didn't match";
      $count++;
    }else {
      $confirm_password = validate_input($_POST["confirmpassword"]);
    }
  }
  
  
  //Check for errors
  if ($count == 0) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $reset_code = md5(crypt(rand(), 'aa'));

     //Input data into database
    $sql = "INSERT INTO users (name, email, username, password, website, image, created_at, reset_code, is_active)
           VALUES ('$fname', '$email', '$username', '$hash','$website', '', " . time() . ", '$reset_code', 0)";
      
      if ($connectdb ->query($sql) === TRUE) {
        $msg = 'You have registered successfully, click <a href="mail.google.com" target="external"><b>Here</b></a> to Verify';

        $message = "You have beed registered successfully. Click the link below to verify your account: <br><br> 
        <a href='https://www.sociolify.dev/process/verify_account.php?code=$reset_code'>Click here to verify</a>";

        //sending email to the user
        send_mail($email, $message);

        $fname = $username = $email = $website = $password = $confirm_password = '';
      }else {
          // echo  "Error: " . $sql . "<br>" . $connectdb->error;
          echo  "Something went wrong";
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
    <title>Sociolify | Register</title>
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
            <li class="nav-item active">
              <a class="nav-link" href="#"><i class="fas fa-user"></i>   Registration
                <span class="sr-only">(current)</span></a>
            </li>
         </ul>
       </div>
      </div>
    </nav>

<!-- FORM -->
   <section class="form">
     <div class="container">
       <h3 class="form-h3">Registration</h3>
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
          <input type="text" value="<?php echo $fname ?>" name="fullname" placeholder="Full Name">
          <span class="error"><?php echo $nameError ?></span>
          <input type="text" value="<?php echo $username ?>" name="username" placeholder="Username">
          <span class="error"><?php echo $usernameError ?></span>
          <input type="email" value="<?php echo $email ?>" name="email" placeholder="Email Address">
          <span class="error"><?php echo $emailError ?></span>
          <input type="text" value="<?php echo $website ?>" name="website" placeholder="Website Address">
          <span class="error"><?php echo $websiteError ?></span>
          <input type="password" name="password" placeholder="Password">
          <span class="error"><?php echo $passwordError ?></span>
          <input type="password" name="confirmpassword" placeholder="Confirm Password">
          <span class="error"><?php echo $confirm_passwordError; ?></span>
          <button type="submit" class="btn btn-submit">REGISTER</button>
        </form>
        <p>Already have an account? <a href="login.php">Login Here</a></p>
       </div>
     </div>
   </section>

</body>
    <!-- jQuery -->
    <script src="libs/js/jquery.slim.min.js"></script>
    <!-- Bootstrap Framework -->
    <script src="libs/js/bootstrap.bundle.min.js"></script>
    
</html>

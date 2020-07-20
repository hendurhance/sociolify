<?php
//Start session
session_start();

// Require files
require "require/config.php";
require "require/functions.php";

if (isset($_SESSION['id'])) {
  header("Location: dashboard.php");
}


//Set users info to default
$username = $password = "";
$usernameError = $passwordError = "";
$count = "";
$msg = "";

//Set cookie
$cookie_user = "username";
$cookie_pass = "password";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $username = validate_input($_POST["username"]);
  $password = validate_input($_POST["password"]);

  //Validate info
  if (empty($_POST["username"])) {
    $usernameError = "Username is required";
    $count++;
  }else{
    $username = validate_input($_POST["username"]);
  }

  if (empty($_POST["password"])) {
    $passwordError = "Password is required";
    $count++;
  }else{
    $password = validate_input($_POST["password"]);
  }
  

  //Check for errors
  if($count == 0){
     $sql = "SELECT * FROM users WHERE username = '$username'";
     $result = $connectdb->query($sql);

     //Check if it already exist
     if ($result->num_rows > 0) {
       //output error
       $row = $result->fetch_assoc();
        

       if ($row['is_active'] == 1){
       // Check if records match
       if (password_verify($password, $row['password'])) {

        // Implementing cookie
        if (isset($_POST["checkbox"])) {
          setcookie("username", $cookie_user, $username, time() + (86400 * 30), "/");
          setcookie("password",$cookie_pass, $password, time() + (86400 * 30), "/");
        }
        
         // Creating Sessions for users
         $_SESSION['id'] = $row['id'];
         $_SESSION['username'] = $row['username'];
         $_SESSION['email'] = $row['email'];
         $_SESSION['password'] = $row['password'];
         $_SESSION['website'] = $row['website'];
         header("Location: dashboard.php");
         exit();
       }else{
         $passwordError = "Password is invalid, try again";
         $password = "";
         $count++;
       }
      }else{
        $msg = "You need to verify your account first";
      }
     }else {
       $msg = "Username does not exist";
       $username = $password = "";
       $count++;
     }
  }

  
}


//

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociolify | Login</title>
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
            <li class="nav-item active">
                <a class="nav-link" href="#"><i class="fas fa-sign-in-alt"></i>   Login
                <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php"><i class="fas fa-user"></i>   Registration</a>
            </li>
         </ul>
       </div>
      </div>
    </nav>


<!-- FORM -->
<section class="form">
     <div class="container">
       <h3 class="form-h3">LOGIN</h3>
       <p><?php 
              if($msg != ''){
              echo '<hr>';
              echo '<div class="alert alert-danger" role="alert">';
              echo  $msg;
              echo '</div>';
          }else if (isset($_GET['message'])){
            echo '<hr>';
            echo '<div class="alert alert-success" role="alert">';
            echo  $_GET['message'];
            echo '</div>';
          }
          ?>
        </p>
       <div class="my-form">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <?php 
            if(!isset($_COOKIE[$cookie_user])){
                echo '<input type="text" name="username" placeholder="Username" value="' . $username . '">';
                echo '<span class="error"> ' . $usernameError . '</span>';
            } else {
                echo '<input type="text" name="username" placeholder="Username" value="' . $_COOKIE[$cookie_user] . '">';
                echo '<span class="error">' .  $usernameError . '</span>';
            }
         ?>
         <?php 
            if(!isset($_COOKIE[$cookie_pass])){
                echo '<input type="password" name="password" placeholder="Password" value="' . $password . '">';
                echo '<span class="error">'. $passwordError . '</span>';
            } else {
                echo '<input type="password" name="password" placeholder="Password" value="' . $_COOKIE[$cookie_pass] . '">';
                echo '<span class="error">' . $passwordError . '</span>';
            }
          ?>
          <div class="form-check">
            <input type="checkbox" name="checkbox" class="form-check-input">
            <label for="checkbox" class="form-check-label">Remember Me</label>
          </div>
          <button type="submit" class="btn btn-submit">LOGIN</button>
        </form>
        <p><a href="forget_password.php">Forget your password?</a></p>
        <p>Don't have an account? <a href="register.php">Register Here</a></p>
       </div>
     </div>
   </section>



</body>
    <!-- jQuery -->
    <script src="libs/js/jquery.slim.min.js"></script>
    <!-- Bootstrap Framework -->
    <script src="libs/js/bootstrap.bundle.min.js"></script>
    
</html>

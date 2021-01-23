<?php
  session_start();

  require_once 'require/config.php';
  require_once 'require/functions.php';

  // define id variable and set to session value
  $id = $_SESSION['id'];

  //Set all input to default
  $old_password = $new_password = $confirm_password = "";
  $old_passwordError = $new_passwordError = $confirm_passwordError = "";

  $count = 0;
  $msg = "";

   //Check if they are signed in already
  if(!isset($_SESSION['id'])){
    header("Location: login.php");
  }

  //Submitting the form
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $old_password = validate_input($_POST["oldpassword"]);
      $new_password = validate_input($_POST["newpassword"]);
      $confirm_password = validate_input($_POST["confirmpassword"]);
      
      //Validate the old password
      if (empty($_POST["oldpassword"])) {
          $old_passwordError = "Old password is required";
          $count++;
      } else {
          $old_password = validate_input($_POST["oldpassword"]);
          
          //Check if the old password is same as the one in the db
          $sql = "SELECT password FROM users WHERE id = '$id'";
          $result = $connectdb->query($sql);

          if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              //If passwords don't match
              if(!password_verify($old_password, $row['password'])){
                  $old_passwordError = "Your old password is incorrect";
                  $old_password = '';
                  $count++;
              }
          } else {
              echo "No results";
          }
      }
      
      //Validate new password
      if (empty($_POST["newpassword"])) {
          $new_passwordError = "New password is required";
          $count++;
      } else {
          $new_password = validate_input($_POST["newpassword"]);
          //If the password matches the current password
          if($new_password == $old_password){
              $new_passwordError = "New password can't match old password";
              $new_password = "";
              $count++;
          } else {
              $new_password = validate_input($_POST["newpassword"]);
          }
      }
      
      //Validate confirm password
      if (empty($_POST["confirmpassword"])) {
          $confirm_passwordError = "Confirm password is required";
          $count++;
      } else {
          $confirm_password = validate_input($_POST["confirmpassword"]);
          //If the passwords are not the same
          if($confirm_password != $new_password){
              $confirm_passwordError = "Password does not match";
              $confirm_password = "";
              $count++;
          } else {
              $confirm_password = validate_input($_POST["confirmpassword"]);
          }
      }
      
      //Check for errors
      if($count == 0){
          $hash = password_hash($new_password, PASSWORD_DEFAULT);
          
          //Update information in the database
          $sql = "UPDATE users SET password = '$hash' WHERE id = '$id'";

          if ($connectdb->query($sql) === TRUE) {
              $msg = "Your password has been changed successfully";
              header("Location: dashboard.php?message=$msg");
              exit();
          } else {
              echo "Error updating record: " . $conn->error;
          }
      }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociolify | Edit Profile</title>
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
              <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i>   Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-in-alt"></i>   Logout</a>
            </li>
         </ul>
       </div>
      </div>
    </nav>

<!-- FORM -->
   <section class="form">
     <div class="container">
       <h3 class="form-h3">CHANGE PASSWORD</h3>
       <div class="my-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <input type="password" name="oldpassword" placeholder="Old Password">
          <span class="error"><?php echo $old_passwordError ?></span>
          <input type="password" name="newpassword" placeholder="New Password">
          <span class="error"><?php echo $new_passwordError ?></span>
          <input type="password" name="confirmpassword" placeholder="Confirm Password">
          <span class="error"><?php echo $confirm_passwordError ?></span>
          <button type="submit" class="btn btn-submit">CHANGE PASSWORD</button>
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

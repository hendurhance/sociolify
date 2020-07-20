<?php
 session_start();

 require_once 'require/config.php';
 require_once 'require/functions.php';

 // define id variable and set to session value
 $id = $_SESSION['id'];
 
 //Set all input to default
 $fname = $username = $email = $website = "";
 $nameError = $usernameError = $emailError = $websiteError = "";

 $count = 0;
 $msg = '';

 //Check if they are signed in already
if(!isset($_SESSION['id'])){
    header("Location: login.php");
}

// Validating and updating user info to database
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
     $fname = validate_input($_POST["fullname"]);
     $username = validate_input($_POST["username"]);
     $email = validate_input($_POST["email"]);
     $website = validate_input($_POST["website"]);
     
     // Validate user input
     if (empty($_POST["fullname"])) {
         $nameError = "Name is required";
         $count++;
     } else {
         $fname = validate_input($_POST["fullname"]);
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
     } else {
         $username = validate_input($_POST["username"]);
     }
     
     //Validate email
     if (empty($_POST["email"])) {
         $emailError = "Email is required";
         $count++;
     } else {
         $email = validate_input($_POST["email"]);
        // Check for invalid email address
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
             $emailError = "Invalid email format";
             $count++;
         }
     }
     
     //Validate website url
     if (empty($_POST["website"])) {
         $websiteError = "Website is required";
         $count++;
     } else {
         $website = validate_input($_POST["website"]);
         // Check for invalid website address
         if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
             $websiteError = "Invalid URL. You need to provide www. before your domain name"; 
             $count++;
         }
     }
     
     //Check for errors
     if($count == 0){
         //Update information in the database
         $sql = "UPDATE users SET name = '$fname', username = '$username', email = '$email', website = '$website' WHERE id = '$id'";

         if ($connectdb->query($sql) === TRUE) {
             $msg = "Profile updated successfully";
             header("Location: dashboard.php?message=$msg");
             exit();
         } else {
             echo "Error occurred while updating profile: " . $connectdb->error;
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
       <h3 class="form-h3">EDIT PROFILE</h3>
       <p><?php 
            $sql = "SELECT * FROM users WHERE id = '$id'";
            $result = $connectdb->query($sql);

            if ($result->num_rows > 0) {
                // output data from each row in database
                $row = $result->fetch_assoc();
                $fname = $row['name'];
                $username = $row['username'];
                $email = $row['email'];
                $website = $row['website'];
            } else {
                echo "No results";
            }
        ?>
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
          <button type="submit" class="btn btn-submit">UPDATE PROFILE</button>
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
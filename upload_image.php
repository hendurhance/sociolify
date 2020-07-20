<?php
session_start();

require "require/config.php";
require "require/functions.php";

 // define id variable and set to session value
$id = $_SESSION['id'];

$msg = '';
$success = 0;

 //Check if they are signed in already
if(!isset($_SESSION['id'])){
    header("Location: login.php");
}

// Check if image is valid
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = $_FILES['file'];
    $filename = $_FILES['file']['name'];
    $fileTmpname = $_FILES['file']['tmp_name'];
    $fileError = $_FILES['file']['error'];
    $fileSize = $_FILES['file']['size'];
    
    //Explode file name with the extension
    $fileExt = explode('.', $filename);
    //Transform everything to lowercase
    $fileActualExt = strtolower(end($fileExt));
    
    $allowed = array('jpg', 'jpeg', 'png');
    
    //If extension is inside the array
    if(in_array($fileActualExt, $allowed)){
        //if we are free of errors
        if($fileError === 0){
            //If the file size is lesser that 10000000KBs
            if($fileSize < 10000000){
                //Generate a unique name based in nanoseconds
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                //Define the destination of the file to be stored
                $fileDestination = 'assets/img/'.$fileNameNew;
                //Upload the image to destination
                if(move_uploaded_file($fileTmpname, $fileDestination)){
                    $success = 1;
                    //Update database image column with the fileNameNew
                    $sql = "UPDATE users SET image = '$fileNameNew' WHERE id = '$id'";
                    if ($connectdb->query($sql) === TRUE) {
                        $msg = "Your image has been changed";
                        header("Location: dashboard.php?message=$msg");
                        exit();
                    } else {
                        echo "Error updating record: " . $connectdb->error;
                    }
                } else {
                    $success = 0;
                    $msg = "Your file failed to upload";
                }
            } else {
                $success = 0;
                $msg = "Your file is too large";
            }
        } else {
            $success = 0;
            $msg = "There was an error uploading your file";
        }
    } else {
        $success = 0;
        $msg = "You cannot upload files of this type";
    }
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociolify | Upload Image</title>
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
       <h3 class="form-h3">UPLOAD IMAGE</h3>
       <p><?php 
        if($success == 1){
            if($msg != ''){
                echo '<hr>';
                echo '<div class="alert alert-success" role="alert">';
                echo  $msg;
                echo '</div>';
            }
        } else {
            if($msg != ''){
                echo '<hr>';
                echo '<div class="alert alert-danger" role="alert">';
                echo  $msg;
                echo '</div>';
            }
        }
          ?>
        </p>
       <div class="my-form">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
          <input type="file" name="file">
          <button type="submit" class="btn btn-submit">UPLOAD IMAGE</button>
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
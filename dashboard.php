<?php

session_start();

    require 'require/config.php';
    require 'require/functions.php';
    
    //Check if the user is not logged in
    if(!isset($_SESSION['id'])){
        header("Location: login.php");
    }

    //Define variables and set them to empty values
    $fname = $username = $email = $website = $created_at = '';

    // define id variable and set to session value
    $id = $_SESSION['id'];

    $msg = '';

    //Delete account request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Select id from database
        $sql = "DELETE FROM users WHERE id = '$id'";

        if ($connectdb->query($sql) === TRUE) {
            $msg = "You account has been deleted";
            
            //Unset and delete the user information
            session_unset();
            session_destroy();
            
            //Destroy the username cookie
            if(isset($_COOKIE['username'])){
                setcookie("username", "", time() - (86400 * 30));
            }
            //Destroy the password cookie
            if(isset($_COOKIE['password'])){
                setcookie("password", "", time() - (86400 * 30));
            }
            header("Location: login.php?message=$msg");
            exit();
        } else {
            echo "Error deleting record: " . $connectdb->error;
        }
    }   
?>








<!-- HTML DOCUMENT -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sociolify | Dashboard</title>
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
              <a class="nav-link active" href="dashboard.php"><i class="fas fa-user"></i>   Home
                <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-in-alt"></i>   Logout</a>
            </li>
         </ul>
       </div>
      </div>
    </nav>

<!-- PROFILE -->

    <section class="profile">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="profile-content">
                        <h3> <i class="fas fa-user"></i> Profile Information</h3>
                        <p>Your can view your profile information and also make changes to them</p>
                        <?php
                        if (isset($_GET['message'])){
                            echo '<hr>';
                            echo '<div class="alert alert-success" role="alert">';
                            echo  $_GET['message'];
                            echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


<!-- DASHBOARD -->

    <section class="dashboard">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="dashboard-content">
                    <?php 
                            $sql = "SELECT image FROM users WHERE id = '$id'";
                            $result = $connectdb->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $image = $row['image'];
                                if ($image == ''){
                                    echo    '<img class="img-fluid" src="assets/img/profile.jpg" alt="">';
                                } else {
                                    echo    '<img class="img-fluid" src="assets/img/' . $image . '" alt="">';
                                }
                            } else {
                                echo "No result";
                            }
                        ?>
                       <h5>Profile Picture</h5>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dashboard-content">
                    <?php
                            $sql = "SELECT * FROM users WHERE id = '$id'";
                            $result = $connectdb->query($sql);

                            if ($result->num_rows > 0) {
                                // output data of each row
                                $row = $result->fetch_assoc();
                                $fname = $row['name'];
                                $username = $row['username'];
                                $email = $row['email'];
                                $website = $row['website'];
                                $created_at = $row['created_at'];
                            } else {
                                echo "No results";
                            }
                        ?>
                        <h4>Name: <strong><?php echo $fname; ?></strong> </h4>
                        <hr>
                        <p>Username: <strong><?php echo $username; ?></strong></p>
                        <p>Email: <strong><?php echo $email; ?></strong> </p>
                        <p>Website: <strong><?php echo $website; ?></strong> </p>
                        <hr>
                        <p>Date Created: <strong><?php echo(date("d-m-Y",$created_at)); ?></strong></p>
                        <hr>
                        <div class="row">
                            <div class="col text-left">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-dark text-center border rounded shadow-lg d-xl-flex" role="button" href="upload_image.php">
                                        <i class="fas fa-image d-xl-flex" style="margin-right: 0px;"></i>
                                    </a>
                                    <a class="btn btn-dark border rounded shadow-lg d-xl-flex" role="button" href="edit_profile.php">
                                        <i class="fa fa-edit d-xl-flex" style="margin-right: 0px;"></i>
                                    </a>
                                    <a class="btn btn-dark border rounded shadow-lg d-xl-flex" role="button" href="change_password.php">
                                        <i class="fa fa-unlock-alt d-xl-flex" style="margin-right: 0px;"></i>
                                    </a>
                                </div>
                                <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete your account</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        Do you really want to delete your account?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                            <form class="text-monospace" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                                  <input type="submit" value='Yes' class="btn btn-primary">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col text-right">
                                <a class="btn btn-dark text-center border rounded shadow-lg " role="button" href="#" data-toggle="modal" data-target="#exampleModal">
                                    <i class="fa fa-trash d-xl-flex" style="margin-right: 0px;"></i>
                                </a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
    <!-- jQuery -->
    <script src="libs/js/jquery.slim.min.js"></script>
    <!-- Bootstrap Framework -->
    <script src="libs/js/bootstrap.bundle.min.js"></script>
    <!-- BaguetteBox Framework -->
    <script src="libs/js/baguettebox.min.js"></script>
    
</html>

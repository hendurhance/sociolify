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
              <a class="nav-link active" href="dashboard.php"><i class="fas fa-user"></i>   Home
                <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-sign-in-alt"></i>   Logout</a>
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
                       <img src="assets/img/blog-3.jpg" alt="" class="img-fluid">
                       <h5>Profile Picture</h5>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dashboard-content">
                        <h4>Name: </h4>
                        <hr>
                        <p>Username: </p>
                        <p>Email: </p>
                        <p>Website: </p>
                        <hr>
                        <p>Date Created: </p>
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
    
</html>
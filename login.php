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
       <div class="my-form">
        <form action="" method="post">
          <input type="text" placeholder="Username">
          <input type="text" placeholder="Password">
          <div class="form-check">
            <input type="checkbox" name="" class="form-check-input">
            <label for="checkbox" class="form-check-label">Remember Me</label>
          </div>
          <button type="submit" class="btn btn-submit">LOGIN</button>
        </form>
        <p><a href="login.php">Forget your password?</a></p>
        <p>Don't have an account? <a href="login.php">Register Here</a></p>
       </div>
     </div>
   </section>



</body>
    <!-- jQuery -->
    <script src="libs/js/jquery.slim.min.js"></script>
    <!-- Bootstrap Framework -->
    <script src="libs/js/bootstrap.bundle.min.js"></script>
    
</html>

<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page


// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//     header("location: login-conn/welcome.php");
//     exit;
// }
 
// Include config file
require_once "login-conn/config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            // session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            // header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="shortcut icon" type="image/x-icon" href="images/camping.png" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <title>Camping</title>
</head>
<body>



<div class="container-fluid" id="head-top" >
      
        <ul class="nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php"><span class="material-symbols-outlined">home</span>Home</a>
            </li>
          
            <!-- <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">map</span>Map</a>
            </li> -->

            <li class="nav-item">
              <a class="nav-link" href="tent.php"><span class="material-symbols-outlined">camping</span>Tent</a>
            </li>

            
            <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">cabin</span>Bangalows</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">airport_shuttle</span>Rv's</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">rv_hookup</span>Mobile Home</a>
            </li>


            <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">restaurant_menu</span>restaurant</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">directions_bus</span>Airport Shuttle</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">pets</span>Pets Free</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">wifi</span>Free Wifi</a>
            </li>

              <li class="nav-item">
              <a class="nav-link" href="#"><span class="material-symbols-outlined">photo_camera</span>Gallery</a>
            </li>
             
         
            <li class="nav-item"id="push-right">
              <a class="nav-link "  href="#" tabindex="-1" aria-disabled="true"><span class="material-symbols-outlined">search</span>Search</a>
            </li>  
      <!-- ----------alert message----------------- -->
      <?php 
            if(!empty($login_err)){
                echo '<div class="alert-message">  ' . $login_err . '</div>';      
              }        
            ?>
      <!-- ---------end-alert message----------------- -->
           
      
            <!-- -----------------------login system---------------------------------------- -->
      
           <li>
             <div> 
              
            <?php 
            if (isset($_SESSION["loggedin"]) ) {?>          
              <h6 class="nav-link" id="username">Hi, <?php echo htmlspecialchars($_SESSION["username"]); ?>, Welcome to our site.</h6> 
          </li>
      
          <?php 
                               if ($_SESSION["username"]==="jvortelinas") {
                                ?><a href="admin/index.php" id="admin">Admin</a><?php
                               }  
                               elseif ( $_SESSION["username"]=="") {
                                
                               }
                               ?>  
            <a href="login-conn/reset-password.php" class="nav-link">Reset Your Password</a> 
            <a href="login-conn/logout.php" class="nav-link">Sign Out of Your Account</a>
           
            <?php 
           
          }
        
          else {?> 
          
         
           <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">         
                <input  type="text" name="username"  class="" id="user-area">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                          
                <input type="password" name="password" class="" id="pass-area">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
         
                <input type="submit"  value="Login" id="user-login">
                 <a href="login-conn/register.php" class="sign">Sign up now</a> 
                       
          </form><?php }?>  
       
        </div> 
   </ul> 
   </div>


 <!-- ----------------------------------end login system------------------------------ -->
  <!-- --1---------------------------------------------------------------------------------- -->
  <div class="container-fluid" id="tent-top">

  </div>
<!--1 ------------------------------------------------------------------------------------ -->

<div class="container">
  <div class="row">

   <h1 id="title">Campingplatz</h1>

   <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Home</a></li>   
    <li class="breadcrumb-item active" aria-current="page">Tent</li>
  </ol>
  </nav>
   
    <div class="col-6" id="tent-p">
      

    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam sit nihil pariatur laboriosam esse. Hic, fugit ducimus nesciunt eos ea culpa quis officia error accusamus repellat veritatis nisi asperiores deleniti iure quo aliquam mollitia. Accusamus adipisci, facilis non, unde impedit voluptas expedita aliquid suscipit provident error ipsa at laborum fuga.</p>
    
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active" id="carousel-img">
      <img src="images/25.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item" id="carousel-img">
      <img src="images/26.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item"id="carousel-img">
      <img src="images/8.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
    </div>
    <div class="col-6"id="tent-p">
      
    <p>Lorem ipsum dolor sit aboriosam esse. Hic, fugit ducimus nesciunt eos ea culpa quis officia error accusamus repellat veritatis nisi asperiores deleniti iure quo aliquam mollitia. Accusamus adipisci, facilis non, unde impedit voluptas expedita aliquid suscipit provident error ipsa at laborum fuga.</p>
<!-- -------------------book button-------------------------------- -->
<button class="btn btn-danger" id="bookt-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Book Now</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasRightLabel">Offcanvas right</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    ...
  </div>
</div>
<!-- ----------------end---book button-------------------------------- -->
  <ul class="list-group list-group-flush">
  <li class="list-group-item" id="list"><span class="material-symbols-outlined">shower</span> <b> Lorem ipsum dolor sit ametcing elit. Vero, eum.</b> </li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">baby_changing_station</span> <b> Lorem ipsum dolor s adipisicing elit. Vero, eum.</b></li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">wash</span> <b> Lorem ipsum dolordipisicing elit. Vero, eum.</b> </li>  
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">family_restroom</span> <b> Lorem ipsum dolor sit amcing elit. Vero, eum.</b> </li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">wc</span> <b> Lorem ipsum dolor sit ing elit. Vero, eum.</b> </li>
  
  </ul>
    </div>
  </div>

</div>
          <!-- -2----------------------------------------------------------------------------------- -->
       
                    <!-- -3----------------------------------------------------------------------------------- -->

                      <!-- -4----------------------------------------------------------------------------------- -->
                      <footer class="container-fluid bg-grey py-5">
                        <div class="container">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="row">
                                    <div class="col-md-6 ">
                                       <div class="logo-part">
                                          <img src="https://i.ibb.co/sHZz13b/logo.png" class="w-50 logo-footer" >
                                          <p>7637 Laurel Dr. King Of Prussia, PA 19406</p>
                                          <p>Use this tool as test data for an automated system or find your next pen</p>
                                       </div>
                                    </div>
                                    <div class="col-md-6 px-4">
                                       <h6> About Company</h6>
                                       <p>But horizontal lines can only be a full pixel high.</p>
                                       <a href="#" class="btn-footer"> More Info </a><br>
                                       <a href="#" class="btn-footer"> Contact Us</a>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="row">
                                    <div class="col-md-6 px-4">
                                       <h6> Help us</h6>
                                       <div class="row ">
                                          <div class="col-md-6">
                                             <ul>
                                                <li> <a href="#"> Home</a> </li>
                                                <li> <a href="#"> About</a> </li>
                                                <li> <a href="#"> Service</a> </li>
                                                <li> <a href="#"> Team</a> </li>
                                                <li> <a href="#"> Help</a> </li>
                                                <li> <a href="#"> Contact</a> </li>
                                             </ul>
                                          </div>
                                          <div class="col-md-6 px-4">
                                             <ul>
                                                <li> <a href="#"> Cab Faciliy</a> </li>
                                                <li> <a href="#"> Fax</a> </li>
                                                <li> <a href="#"> Terms</a> </li>
                                                <li> <a href="#"> Policy</a> </li>
                                                <li> <a href="#"> Refunds</a> </li>
                                                <li> <a href="#"> Paypal</a> </li>
                                             </ul>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6 ">
                                       <h6> Newsletter</h6>
                                       <div class="social">
                                          <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                          <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                       </div>
                                       <form class="form-footer my-3">
                                          <input type="text"  placeholder="search here...." name="search">
                                          <input type="button" value="Go" >
                                       </form>
                                       <p>That's technology limitation of LCD monitors</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        </footer>
                        
                        
                        <!-- Credit to https://bootsnipp.com/snippets/M56El  -->
                        <!-- -4----------------------------------------------------------------------------------- -->
             
              
<!-- footer -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
   <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
   <!-- footer -->

   <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
</body>
</html>
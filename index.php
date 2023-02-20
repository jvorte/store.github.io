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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <title>Camping</title>
</head>
<body>



<div class="container-fluid" id="head-top" >
      
        <ul class="nav">
            <li class="nav-item">
            <a href="index.php"class="logo-top">Camping</a>
            </li>       

            <li class="nav-item">
              <a class="nav-link" href="tent.php"><span class="material-symbols-outlined">camping</span>Tent</a>
            </li>

            
            <li class="nav-item">
              <a class="nav-link" href="bangalows.php"><span class="material-symbols-outlined">cabin</span>Bangalows</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="rvs.php"><span class="material-symbols-outlined">airport_shuttle</span>Rv's</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="mobile.php"><span class="material-symbols-outlined">rv_hookup</span>Mobile Home</a>
            </li>


            <li class="nav-item">
              <a class="nav-link" href="#college4"><span class="material-symbols-outlined">restaurant_menu</span>restaurant</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="shuttle.php"><span class="material-symbols-outlined">directions_bus</span>Airport Shuttle</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#college3"><span class="material-symbols-outlined">pets</span>Pets</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#college1"><span class="material-symbols-outlined">manage_accounts</span>Servise's</a>
            </li>

              <li class="nav-item">
              <a class="nav-link" href="gallery.php"><span class="material-symbols-outlined">photo_camera</span>Gallery</a>
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


   <!-- ----------------------------------menu------------------------------ -->    
   
   <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
   
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" id="menu-cat" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="menu-cat" >Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="menu-cat" >Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="menu-cat" >Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" id="menu-cat" >Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="#" id="menu-cat" tabindex="1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
    </div>
  </div>
</nav> -->
 <!-- ----------------------------------end menu------------------------------ -->


  <!-- --1---------------------------------------------------------------------------------- -->
 

  

        <!--1 ------------------------------------------------------------------------------------ -->
 
        <div id="center-img">

            <h6 id="small-text" >your dream days</h6>
         <h1 id="center-title">book your next <br>Vacations </h1>        
         <button type="button" class="btn btn-light"id="book-btn"><a href="#college2" >Explore now</a> </button>




           <!-- <form class="row g-2" id="select">
              <div class="col-md-2">
                <label for="inputEmail4" class="form-label" id="form-label-first">Τype of accommodation?</label>
                <select id="inputState" class="form-select">
                <option selected>Camper</option>
                <option></option>
              </select>
              </div>
              <div class="col-md-2">
                <label for="inputPassword4" class="form-label"id="form-label-first">Persons?</label>
                <select id="inputState" class="form-select">
                <option selected>Choose...</option>
                <option>...</option>
              </select>
              </div>
              <div class="col-2">
                <label for="inputAddress" class="form-label"id="form-label-first">Vesicle?</label>
                <select id="inputState" class="form-select">
                <option selected>Choose...</option>
                <option>...</option>
              </select>
              </div>
              <div class="col-2">
                <label for="inputAddress2" class="form-label"id="form-label-first">Dates?</label>
                <select id="inputState" class="form-select">
                <option selected></option>
                <option></option>
              </select>
              </div>            
              <div class="col-3">
                 
                <button type="submit" class="btn btn-primary">Find</button>
              </div>
            </form> -->

       
        </div>
      
          <!-- -2----------------------------------------------------------------------------------- -->
<!--        
          <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<lottie-player src="https://assets7.lottiefiles.com/packages/lf20_kj3hqs1b.json"  background="transparent"  speed="1"  style="width: 400px; height: 400px;"    autoplay></lottie-player>
            -->
<div class="container px-4" id="college1">
  <div class="row gx-5">
  <h1>Our Servise's <span class="material-symbols-outlined">camping</span></h1>
    <div class="col">
     <div class="p-3">
     <ul class="list-group list-group-flush">
  <li class="list-group-item" id="list"><span class="material-symbols-outlined">shower</span>  Lorem ipsum dolor sit ametcing elit. Vero, eum.</li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">baby_changing_station</span> Lorem ipsum dolor s adipisicing elit. Vero, eum.</li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">wash</span>  Lorem ipsum dolordipisicing elit. Vero, eum. </li>  
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">family_restroom</span> Lorem ipsum dolor sit amcing elit. Vero, eum. </li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">wc</span> Lorem ipsum dolor sit ing elit. Vero, eum.</li>
  
  </ul>
     </div>
    </div>
    <div class="col">
      <div class="p-3">
      <ul class="list-group list-group-flush">
      <li class="list-group-item" id="list"><span class="material-symbols-outlined">shower</span>  Lorem ipsum dolor sit ametcing elit. Vero, eum.</li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">baby_changing_station</span> Lorem ipsum dolor s adipisicing elit. Vero, eum.</li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">wash</span>  Lorem ipsum dolordipisicing elit. Vero, eum. </li>  
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">family_restroom</span> Lorem ipsum dolor sit amcing elit. Vero, eum. </li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">wc</span> Lorem ipsum dolor sit ing elit. Vero, eum.</li>
  
  </ul>
      </div>
    </div>
  </div>
</div>

<!-- -2----------------------------------------------------------------------------------- -->
<div id="top">
 <a href="#" ><span class="material-symbols-outlined">expand_less</span><br> Top</a>
 </div>
                 <!-- -3----------------------------------------------------------------------------------- -->
               


    <div class="container-fluid overflow-hidden"  id="college2">

     <h1>Find what you need <span class="material-symbols-outlined">camping</span></h1>

       <div class="row row-cols-1 row-cols-md-4 g-4">
  <div class="col">
    <div class="card"id="card-cat">
      <img src="images/3.jpg" class="card-img-top" alt="...">
      <div class="card-body">
        <h4 class="card-title">Tent's</h4>
        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        <a href="tent.php" id="book-link">Book now</a>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card"id="card-cat">
      <img src="images/27.jpg" class="card-img-top" alt="...">
      <div class="card-body">
        <h4 class="card-title">Bangalow's</h4>
        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        <a href="tent.php"id="book-link">Book now</a>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card"id="card-cat">
      <img src="images/7.jpg" class="card-img-top" alt="...">
      <div class="card-body">
        <h4 class="card-title">Rv's</h4>
        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        <a href="tent.php"id="book-link">Book now</a>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="card"id="card-cat">
      <img src="images/22.jpg" class="card-img-top" alt="...">
      <div class="card-body">
        <h4 class="card-title">Mobile's</h4>
        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
        <a href="tent.php"id="book-link">Book now</a>
      </div>
    </div>
  </div>
</div>

</div>
                    <!-- -3----------------------------------------------------------------------------------- -->
 <!-- ----------------------------------------------------------------------------------- -->
 <div id="top">
 <a href="#" ><span class="material-symbols-outlined">expand_less</span><br> Top</a>
 </div>


 

 
 <!------------------------------------------------------------------------------------- -->
<div class="container-fluid" id="college3">

<div class="container px-4">
  <div class="row gx-5">
    <div class="col">
     <div class="p-3 ">
      <img src="images/21.jpg" alt="" srcset="">
     </div>
    </div>
    <div class="col">
    
      <div class="p-3">
    <h1>Pet's Vacation's<span class="material-symbols-outlined">pets</span></h1>

    <p>Lorem quasi similique voluptatibus odit sunt optio volo voluptates tvoluptatibus odit sunt optio voluptate faenetur ea fugit ex facere quos tempora fiure esse, dolor expedita officia possimus. Eligendi?</p>
    <div class="row">
    <div class="col">
    <ul class="list-group list-group-flush">
  <li class="list-group-item" id="list"><span class="material-symbols-outlined">shower</span>  Lorem ipsum . </li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">baby_changing_station</span>  Lorem ipsum dolor </li>
 
  </ul>
    </div>
    <div class="col">
    <ul class="list-group list-group-flush">
    <li class="list-group-item" id="list"><span class="material-symbols-outlined">shower</span>  Lorem ipsum . </li>
  <li class="list-group-item"id="list"><span class="material-symbols-outlined">baby_changing_station</span>  Lorem ipsum dolor </li>
 
  </ul>
    </div>
  </div>
  </div>
    </div>
  </div>
</div>

</div>


<div id="top">
 <a href="#" ><span class="material-symbols-outlined">expand_less</span><br> Top</a>

      <!-- ------------------------------------------------------------------------------------ -->
<div class="container" id="college4">

 <h1>Restaurant<span class="material-symbols-outlined">ramen_dining</span></h1>

 <div class="container overflow-hidden">
  <div class="row gy-5">
    <div class="col-6">
      <div class="p-3 ">


      </div>
    </div>
    <div class="col-6">
      <div class="p-3 ">


      </div>
    </div>
    <div class="col-6">
      <div class="p-3 ">
      
      
      
      </div>
    </div>
    <div class="col-6">
      <div class="p-3 ">
        
      

      </div>
    </div>
  </div>
</div>

</div>



<div id="top">
 <a href="#" ><span class="material-symbols-outlined">expand_less</span><br> Top</a>
 </div>

       <!-- ------------------------------------------------------------------------------------ -->
                      <!-- -4----------------------------------------------------------------------------------- -->
                      <footer class="container-fluid bg-grey py-5">
                        <div class="container">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="row">
                                    <div class="col-md-6 ">
                                       <div class="logo-part">
                                          <!-- <img src="https://i.ibb.co/sHZz13b/logo.png" class="w-50 logo-footer" > -->
                                          <h1 class="w-50 logo-footer">Camping</h1>
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
                        <script>
  AOS.init();
</script>
                        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>        
        
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
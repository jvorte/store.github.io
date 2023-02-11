<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css"><style>
             body{ font: 14px sans-serif; }
        .wrapper{ width: 360px;
                  padding: 20px;
                  margin: 140px 0 165px 700px
                 }
    </style>
</head>
<body>
<div class="container-fluid" id="head-top">
        <ul class="nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/index.php">Logo</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link2</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" style="color: azure;" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li>
          </ul>
    </div>

    <div class="container-fluid" id="head-down">
    <nav class="navbar navbar-expand-lg navbar-light" >
            <div class="container-fluid">
              <a class="navbar-brand" href="#" style="color: azure;">Menu</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item">
                    <a class="nav-link active" aria-current="page" style="color: azure;" href="#">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link"  href="#" style="color: azure;">Link</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" style="color: azure;" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Dropdown
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                      <li><a class="dropdown-item" href="#">Another action</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link disabled" style="color: azure;" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                  </li>
                </ul>
                <form class="d-flex">
                  <input class="form-control me-2" type="search" placeholder="" aria-label="Search" id="search-input">
                  <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" style="color: azure;" href="#">See All Details</a>
                  </li>           

              </div>
            </div>
          </nav>
        </div>


    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="/index.php">Cancel</a>
            </div>
        </form>
    </div>  
    
    
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

</body>
</html>
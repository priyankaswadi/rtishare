<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   session_start();
?>
<html><title></title>    
    <link rel="shortcut icon" href="images/rti.ico">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">       
    <link rel="stylesheet" href="stylesheets/default.css">       
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script>
        $( document ).tooltip({  //http://jqueryui.com/tooltip/                
                 position: { my: "left+15 center", at: "right center",collision: "none" }                 
        });
    </script>
    <body>    
        <div id="menu">
            <ul id="nav">
                <li class="index.php"><a href="index.php">Home</a></li>
                <li><a href="upload.php">Upload An RTI</a></li>
                <li><a href="RTIInfo.php">RTI Info</a></li>				
                <li><a href="AboutUs.php">About Us</a></li>				
                <li><a href="ContactUs.php">Contact Us</a></li>
            </ul>
        </div>
		
        <form id="verifyemaillink" method="post">
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>    
            <div id="content" class="container">              
                <h1>Verify Email</h1>
                    <?php 
                    if (!isset($_POST["login"])){ 
                        if (!isset($_POST["submit"])){ 
                            $random_hash = mysql_prep($_GET['id']);
                            $email="";
                            if(isset($_SESSION['verifyemail'])){
                                $email = mysql_prep($_SESSION['verifyemail']);
                            }
                            //echo "<br />".$email."<br />".$random_hash."<br />";                                                                            
                            if ($email==""){
                                echo "<div class='error'>Session expired. If your account is still not activated please enter your email ID below</div>";                            
                                echo "<p><input name='email' type='email' value='' placeholder='Email ID' title='Enter your email here' id='email' required='' autofocus></p>";
                                echo "<input type='submit' value='Email Activation Link' name='submit'/>";                            
                            }
                            else
                            {
                                $user = find_user_by_email($email);//verify if user exists in DB
                                if($user){
                                    if($user['active']==0){//check if account is already active
                                        if($random_hash===$user['random_hash']){ //verify if link sent in email was for this same user and is valid
                                            if(activate_user($email)){ //set active=1 in DB
                                                echo "Account activated! <br />Please Enter your password to login.<br /><br />";
                                                //1. Create fields like Login Page for user to enter password 
                                                //and also autofill Email ID from session here.
                                                //2. Clicking on Login button will enable user session i.e. Log in user
                                                echo "<p><input name='email' type='email' value='".$email."' placeholder='Email ID' id='email' style='border: 0px solid; width: 100%;' readonly></p>";
                                                echo "<p><input name='pass' type='password' value='' placeholder='Password' title='Enter Password' id='pass' required='' autofocus></p>";
                                                echo "<p><input type='submit' value='Login' name='login'/></p>";                            
                                            }
                                        }
                                        else //if random_hash doesn't match it means its not the same user.
                                        {                                    
                                            echo "<div class='error'>Account cannot be activated. Your information couldn't be retrieved. Please contact admin.</div>";
                                            
                                        }
                                    }
                                    else{
                                        echo "<div class='error'>This account has already been activated earlier."." <br />"."Are you having trouble signing in? Please click Forgot Password or contact admin.</div>";
                                    }
                                }
                                else
                                {
                                    echo "<div class='error'>The Email ID you have used for registration could not be found. Please try signing up again.</div>";
                                }
                            }
                        }
                    }
                    ?>
                    <?php 
                        if (isset($_POST["submit"])){    
                                $email = mysql_prep(filter_input(INPUT_POST, 'email'));            
                                $user = find_user_by_email($email);//verify if user exists in DB
                                if($user){
                                    if($user['active']==0){
                                        verify_signup_again($email);//do steps similar to signup again
                                    }
                                    else{
                                        echo "This account has already been activated earlier."." <br />"."Are you having trouble signing in? Please click Forgot Password or contact admin.";
                                    }
                                }
                                else{
                                    echo "This Email ID doesn't exist. Please try again.";
                                }
                        }                            
                    ?>
                    <?php //not checking here for conditions like user found in DB or account already activated as the $email is readonly and user can't chanage it.
                        if (isset($_POST['login'])){    
                            $email = mysql_prep(filter_input(INPUT_POST, 'email'));            
                            $pass = mysql_prep(filter_input(INPUT_POST, 'pass'));                                
                            $hashed_password= password_encrypt($pass);    
                            $found_user= attempt_login($email,$pass);
                            if($found_user){
                                $_SESSION['user_email'] = $email;
                                redirect_to("loginsuccess.php");
                            }
                            else{
                                echo "<p><input name='email' type='email' value='".$email."' placeholder='Email ID' title='Enter your email here' id='email' required=''></p>";
                                echo "<p><input name='pass' type='password' value='' placeholder='Password' title='Enter Password' id='pass' required='' autofocus></p>";
                                echo "<p><input type='submit' value='Login' name='login'/></p>";                                                                                     
                                echo "<div class='error'>Login failed. Please try entering your password again.</div>";
                            }
                        }   

            ?>
            </div>        
        </form>
    </body>
</html>
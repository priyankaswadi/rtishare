<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include_once("ui_functions.php");
   session_start();
?>
<?php 
    $err_str ="";
    if (!isset($_POST["login"]))
    { 
        if (!isset($_POST["submit"]))
        { 
            $random_hash = mysql_prep($_GET['id']);
            $email="";
            if(isset($_SESSION['verifyemail'])){
                $email = mysql_prep($_SESSION['verifyemail']);
            }
            //echo "<br />".$email."<br />".$random_hash."<br />";                                                                            
            if ($email==""){
                $err_str.= "<div class='error'>Session expired. If your account is still not activated please enter your email ID below</div>";                            
                $err_str.= "<div class='form-group'><input name='email' type='email' value='' placeholder='Email ID' title='Enter your email here' id='email' required='' autofocus class='form-control' ></div>";
                $err_str.= "<div class='form-group'><input type='submit' value='Email Activation Link' name='submit'class='btn btn-success btn-lg'/></div>";                            
            }
            else
            {
                $user = find_user_by_email($email);//verify if user exists in DB
                if($user){
                    if($user['active']==0){//check if account is already active
                        if($random_hash===$user['random_hash']){ //verify if link sent in email was for this same user and is valid
                            if(activate_user($email)){ //set active=1 in DB
                                $err_str.= "Account activated! <br />Please Enter your password to login.<br /><br />";
                                //1. Create fields like Login Page for user to enter password 
                                //and also autofill Email ID from session here.
                                //2. Clicking on Login button will enable user session i.e. Log in user
                                $err_str.= "<div class='form-group'><input name='email' type='email' value='".$email."' placeholder='Email ID' id='email' style='border: 0px solid; width: 100%;' readonly class='form-control'></div>";
                                $err_str.= "<div class='form-group'><input name='pass' type='password' value='' placeholder='Password' title='Enter Password' id='pass' required='' autofocus class='form-control'></div>";
                                $err_str.= "<div class='form-group'><input type='submit' value='Login' name='login'class='btn btn-success btn-lg'/></div>";                            
                            }
                        }
                        else //if random_hash doesn't match it means its not the same user.
                        {                                    
                            $err_str.= "<div class='error'>Account cannot be activated. Your information couldn't be retrieved. Please contact admin.</div>";
                            
                        }
                    }
                    else{
                        $err_str.= "<div class='error'>This account has already been activated earlier."." <br />"."Are you having trouble signing in? Please click Forgot Password or contact admin.</div>";
                    }
                }
                else
                {   
                    $err_str.= "<div class='error'>The Email ID you have used for registration could not be found. Please try signing up again.</div>";
                }
            }
        }
    }
?>
<?php
    if (isset($_POST["submit"])) {
        $email = mysql_prep(filter_input(INPUT_POST, 'email'));
        $user = find_user_by_email($email); //verify if user exists in DB
        if ($user) {
            if ($user['active'] == 0) {
                verify_signup_again($email); //do steps similar to signup again
            } else {
                $err_str.= "This account has already been activated earlier." . " <br />" . "Are you having trouble signing in? Please try using <a href='forgotpassword.php'>Forgot Password</a>.";
            }   
        } else {
            $err_str.= "This Email ID doesn't exist. Please try again.";
        }
    }
?>
<?php
//not checking here for conditions like user found in DB or account already activated as the $email is readonly and user can't chanage it.
    if (isset($_POST['login'])) {
        $email = mysql_prep(filter_input(INPUT_POST, 'email'));
        $pass = mysql_prep(filter_input(INPUT_POST, 'pass'));
        $hashed_password = password_encrypt($pass);
        $found_user = attempt_login($email, $pass);
        if ($found_user) {
            $_SESSION['user_email'] = $email;
            redirect_to("loginsuccess.php");
        } else {
            $err_str.= "<div class='form-group'><input name='email' type='email' value='" . $email . "' placeholder='Email ID' title='Enter your email here' id='email' required='' class='form-control' ></div>";
            $err_str.= "<div class='form-group'><input name='pass' type='password' value='' placeholder='Password' title='Enter Password' id='pass' required='' autofocus class='form-control' ></div>";
            $err_str.= "<div class='form-group'><input type='submit' value='Login' name='login' class='btn btn-success btn-lg'/></div>";
            $err_str.= "<div class='error'>Login failed. Please try entering your password again.</div>";
        }
    }
?>
<html>
    <?php InsertCommonHeader() ?>
    <body>    
    <?php InsertNavbar(false) ?>
        <form id="verifyemaillink" method="post">
            <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>    
            <div id="content" class="container">              
                <div class="container col-sm-4">  
                <h1>Verify Email</h1>
                    <?php echo $err_str;?>
                </div>
            </div>        
        </form>
        <?php InsertFooter(400) ?>    
    </body>    
    <?php InsertCommonJS() ?>
    <script>
        $( document ).tooltip({  //http://jqueryui.com/tooltip/                
                 position: { my: "left+15 center", at: "right center",collision: "none" }                 
        });
    </script>
    
</html>
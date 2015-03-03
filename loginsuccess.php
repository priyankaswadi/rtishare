<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include_once("ui_functions.php");
   session_start();
   
?>
<html>
    <?php InsertCommonHeader() ?>
    <body>
        <?php InsertNavbar(); ?>
        <form>            
            <div id="content" class="container">              
                <div id="header" class="banner"><h1>Welcome to RTIShare.org</h1></div>   
                <?php 
                    if(isset($_SESSION['user_email'])){                    
                        echo "Login Successful! You may now upload RTIs to the website!<br />
                              Please click on Upload RTI link in the menu to do so.<br />";
                        $email = $_SESSION['user_email'];
                        $user=  find_user_by_email($email);
                        if($user['temppass_flag']==1){//check if password used by user was a temp password which must be changed
                            echo "Would you also like to reset your password?<br />";
                            echo "Please use the <a href='changePassword.php'>Reset Password </a>link to do so.";
                        }
                    }
                    else{
                        echo "Logged out successfully!";
                    }
                ?>
                
            </div>        
        </form>
        <?php InsertFooter(600) ?>    
    </body>
    <?php InsertCommonJS() ?>
</html>
<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php");
   include_once("ui_functions.php");
   
   session_start();   
   if(isset($_SESSION['user_email'])){ //Check if user is logged in      
       redirect_to("index.php");
   }
   if (!isset($_POST["submit"])){   
       $email ="";    
   }
   else{
       $email = mysql_prep(filter_input(INPUT_POST, 'email'));                                        
   }
?>
<?php
    $html_signup = "";
    if (isset($_POST["submit"])){                      
        $email = mysql_prep(filter_input(INPUT_POST, 'email'));            
        $pass = mysql_prep(filter_input(INPUT_POST, 'pass'));            
        $pass2 = mysql_prep(filter_input(INPUT_POST, 'pass2'));                                
        if(find_user_by_email($email)){
            $html_signup = "<div class='error'>";
            $html_signup.= "Email ID ".$email." already exists. <br />Please use Forgot Password link to reset your password if you have trouble logging in.";
            $html_signup.= "</div>";
        }
        else{
            $html_signup = verify_signup($email, $pass, $pass2);
        }
    }                
?>
<html>
    <?php InsertCommonHeader() ?>
    <body>    
    <?php InsertNavbar() ?>
		
        <form id="signup" method="post">
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>    
            <div id="content" class="container">             
                <h1>Sign Up</h1>
                <p><input name="email" type="email" value="<?php echo $email;?>" placeholder="Email ID" title="Enter your email here" id="email" required="" autofocus></p>
                <p><input name="pass" type="password" value="" placeholder="Password" title="A good password should be atleast 8 characters long." id="pass" required="" data-indicator="pwindicator"></p>
                <div id="pwindicator">
                    <div class="bar"></div>
                    <div class="label"></div>
                </div>
                <p><input name="pass2" type="password" value="" placeholder="Re-enter Password" title="Reenter your password again." id="pass2" required="" ></p>
                <div id="passmatch" class="match"></div><br />
                <input type="submit" value="Sign Up" name="submit"/>&nbsp;&nbsp;or
                <a href="login.php">Login to existing account</a><br /><br />                
                <?php echo $html_signup;?>
            </div>                        
            
        </form>        
    </body>
    <?php InsertCommonJS() ?>
    <script>
        $( document ).tooltip({  //http://jqueryui.com/tooltip/                
                 position: { my: "left+15 center", at: "right center",collision: "none" }                 
        });        
        $(document).ready(function(){            
            jQuery(function($) { 
                $('#pass').pwstrength();                 
            });
            $("#pass2").keyup(function(){
                var pass = $("#pass").val();
                var pass2 = $("#pass2").val();                
                if(pass===pass2){
                    $('#passmatch').text('Passwords match'); 
                }
                else{
                    $('#passmatch').text(''); 
                }
                    
                
            });
        });
        
    </script>
</html>
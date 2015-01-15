<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include_once("ui_functions.php");   
    session_start();   
?>
<html lang="en">
    <?php InsertCommonHeader() ?>
    <body>
        <?php InsertNavbar() ?>
        
        <form>
            <!--Empty header below to headline the body-->
            <div class="bs-docs-header">
            </div>   
            <div id="content" class="container">              
                Sign Up Successful. <br />
                To activate your account please use the link <br />
                you have received in your email.
            </div>        
        </form>
    </body>
    <?php InsertCommonJS() ?>
</html>
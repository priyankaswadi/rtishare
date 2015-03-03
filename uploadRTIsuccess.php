<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php");
   include("ui_functions.php");
   session_start();   
   if(!isset($_SESSION['user_email'])){ //Check if user is logged in
       $_SESSION['message'] = "You need to login to upload RTIs.";    
       redirect_to("login.php");
   }
?>
<html>
    <?php InsertCommonHeader() ?>    
    <body>
        <?php InsertNavbar() ?>
        <form id="upload">
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>    
            <div id="content" class="container">
                Your RTI has been uploaded successfully!
                
            </div>        
        </form>
        <?php InsertFooter(600) ?>    
    </body>
    <?php InsertCommonJS() ?>
</html>
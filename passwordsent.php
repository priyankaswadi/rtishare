<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include("ui_functions.php"); 
   session_start();
?>
<html>
    <?php InsertCommonHeader() ?>
    <body>
        <?php InsertNavbar() ?>
        <form>
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>    
            <div id="content" class="container">              
              A temporary password has been sent to your email ID. Please 
              <a href="login.php">Login </a>to your account using the same.
            </div>        
        </form>
    </body>
    <?php InsertCommonJS() ?>
</html>
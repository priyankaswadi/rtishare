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
        <?php InsertNavbar(); ?>    
        <form>
            <!--Empty header below to headline the body-->
            <div class="bs-docs-header" id="header"><div class="container"><h3>
                        Disclaimer
            </h3></div></div>

            <div id="content" class="container">
                
                <div class="col-sm-6">                
                    rtishare.org is not responsible for the content hosted on this website. 
                    Although we take utmost care to keep the website and its data safe and protect privacy, 
                    users are advised to use rtishare.org at their own risk. 
                    rtishare.org does not monitor external links or documents shared on this website and user discretion is advised. 
                    rtishare.org excludes liability for all loss or damage that arises in connection or through use of this website.
                </div>
            </div> 
        </form>
        <?php InsertFooter(600) ?>    
    </body>
    <?php InsertCommonJS() ?>    
</html>
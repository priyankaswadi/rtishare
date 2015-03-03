<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("validation_functions.php");
include_once("ui_functions.php");

session_start();
?>
<html lang="en">
    <?php InsertCommonHeader(); ?>
    <body>
        <?php InsertNavbar(); ?>

        <form>
            <!--Empty header below to headline the body-->
            <div class="bs-docs-header" id="header"><div class="container"><h3>
                        Contact Us
            </h3></div></div>

            <div id="content" class="container">              
                <h3>You may reach us at info.rtishare[at]gmail.com </h3>
            </div>        
        </form>
        <?php InsertFooter(600) ?>    
    </body>
    <?php InsertCommonJS() ?>
</html>
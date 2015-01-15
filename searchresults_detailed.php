<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include_once("ui_functions.php"); 
   session_start();   //need this to access session variables
    if (!$_SESSION) { //in case of logout when all session variables are destroyed, do not display old search results as that will give error.
        redirect_to("index.php");
    }
?>
<html>
    <?php InsertCommonHeader() ?>
    <body>
        <?php InsertNavbar() ?>
        <form id="searchresults">
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>               
            <div id="content" class="container">
                <div id="rtisbox">                          
                </div>
                <div id="pagination" class="pagination dark">
                </div>
            </div>
        </form>
    </body>
    <?php InsertCommonJS() ?>
    <?php InsertCustomPagingJS('get_pages_detailed.php') ?>
</html>

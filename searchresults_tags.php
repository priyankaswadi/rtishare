<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("tagcloud_functions.php");
include("validation_functions.php");
include_once("ui_functions.php");
session_start();   //need this to access session variables
?>

<?php $headertext = CreateHeaderFromUrltags(); ?>    

<html>
    <?php InsertCommonHeader() ?>
    <body>    
        <?php InsertNavbar() ?>
        <form id="searchresults">
            <div class="bs-docs-header" id="header"><div class="container"><h3>
                        <?php echo($headertext); ?>
                    </h3></div></div>               
            <div id="content" class="container">
                <div id="rtisbox"> 
                </div>
                <div id="pagination" class="pagination dark">
                </div>

            </div>
        </form>
        <?php InsertFooter(400) ?>    
    </body>
    <?php InsertCommonJS() ?>
    <?php $tag = filter_input(INPUT_GET, 'tag');
    $tag = json_encode(mysql_prep($tag));
    ?>
<?php InsertCustomPagingJS('get_pages_tags.php', $tag) ?>

</html>

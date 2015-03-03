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
                <div id="state_info">
                    <?php
                    show_state_info();
                    ?>
                </div>                
                <div id="rtisbox">                          
                </div>
                <div id="pagination" class="pagination dark">
                </div>
            </div>
        </form>
        <?php InsertFooter(400) ?>    
    </body>
    <?php InsertCommonJS() ?>
    <?php
    //get all the tags from GET here and write query accordingly                            
    $state = json_encode(mysql_prep(filter_input(INPUT_GET, 'state')));
    if ($state) {
        $get_array = filter_input_array(INPUT_GET);
        unset($get_array['state']); //remove state from get_array                        
    }
    $getarray = json_encode(mysql_prep(implode(',', $get_array)));
    ?>
    <?php InsertCustomPagingJS('get_pages_tags_states.php', $state, $getarray) ?>
</html>


<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("tagcloud_functions.php");
include("validation_functions.php");
include_once("ui_functions.php");
session_start();                //need this to access session variables
?>
<?php $headertext = CreateHeaderFromUrltags(); ?>    
<?php
$state = filter_input(INPUT_GET, 'state');
if ($state) {
    if (is_state($state)) {
        $get_array = filter_input_array(INPUT_GET);
        unset($get_array['state']); //remove state from get_array                        
        $tagcloudhtml = show_tag_cloud_for_state($state, $get_array); //use a single function to display hierarchical and non-h cloud
    } else {
        $tagcloudhtml = "This state does not exist. Please try again.";
    }
} else {
    $tagcloudhtml = "No State selected.";
}
?>

<html>
    <?php InsertNavbar() ?>    
    <body>    
        <?php InsertCommonHeader() ?>    
        <form id="searchresults">
            <div class="bs-docs-header" id="header"><div class="container"><h3>
                        <?php echo $headertext; ?>
                    </h3></div></div>    
            <div id="content" class="container">
                <div id="state_info">
                    <?php
                    show_state_info();
                    ?>

                </div>
                <div id="tagnavigation">
                    <?php
                    $get_array = filter_input_array(INPUT_GET);
                    $get_array_initial = $get_array;
                    $state_menu = $get_array['state'];
                    unset($get_array['state']); //remove state from get_array                        
                    $lastterm = end(array_values($get_array));
                    if ($get_array) {
                        echo "<a href=" . taglink($get_array_initial, 'state') . ">$state_menu</a>";
                        echo "<img src='images/tagarrow.png' width='1%'/>";
                    }
                    foreach ($get_array as $keyvalue => $term) {
                        echo "<a href=" . taglink($get_array_initial, $keyvalue) . ">$term</a>";
                        if ($term != $lastterm) {
                            echo "<img src='images/tagarrow.png' width='1%'/>";
                        }
                    }
                    ?>                    
                </div>
                <div id="tagcloud">
                    <?php echo $tagcloudhtml ?>
                </div>
                <div class="col-sm-4" style="background-color:none;" id="popularrtis">                    
                    <?php
                    $rtis_list = find_popular_rtis_for_state($state);
                    $includedetails = False;
                    echo InsertPagedRTITable($rtis_list, $includedetails,"Popular RTIs");
                    ?>
                </div>
                <div>
                    <br /><br />Know exactly what you are looking for? <a href="detailedsearch.php">Search here.</a>
                </div>
            </div>
        </form>
        <?php InsertFooter(400) ?>    
    </body>
    <?php InsertCommonJS() ?>    
</html>

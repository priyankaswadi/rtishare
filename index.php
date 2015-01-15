<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("tagcloud_functions.php");
include("validation_functions.php");
session_start();
include_once("ui_functions.php");
?>
<?php
if (isset($_POST["submit"])) {
    $search_error = 0;
    $alltags = mysql_prep(filter_input(INPUT_POST, 'tags'));
    //actually below info can be passed in a query string really!
    session_start(); //need this to access session variables
    $_SESSION["alltags"] = $alltags;
    redirect_to("searchresults.php");
}
?>
<html lang="en">
    <?php InsertCommonHeader() ?>
    <body>
        <?php InsertNavbar(); ?>

        
        <div class="bs-docs-header" id="header">
            <div class="container">
                <h1>welcome to <text class="text-primary">rtishare.org</text> </h1>
                <h2>A place to search, share and discuss RTI responses.</h2>
                <form id="searchpages" method="post">
                    <div class="form-group form-group-lg">
                        <div class="row">
                            <div class="col-lg-5">
                                <input name="tags" id="tags" class="form-control " type="text" data-toggle="tooltip" data-placement="bottom" placeholder="Search by popular tags separated with commas" 
                                       title="Type location or names of people or events this RTI may be related to. E.g. General Elections 2009. Or CWG Scam."
                                       >
                            </div>
                            <button name ="submit" type="submit" class="btn btn-success btn-lg" for="tags"><strong>Search Tags</strong></button>
                        </div>
                    </div>
                </form>
                <p>Would you like to search in more detail? <b><u><a href="detailedsearch.php"> Search Here</a></u></b></p>
            </div>
        </div>

        <div id="content" class="container">

        <div class="container col-sm-4">
            <div style="background-color:none;" id="popularrtis">                
                <?php
                $rtis_list = find_popular_rtis();
                $includedetails = False;
                echo InsertPagedRTITable($rtis_list, $includedetails,"Popular RTIs");
                ?>
            </div>
        </div>

        <div class="container col-sm-6">
        <ul id="myTab" class="nav nav-tabs">
            <li class="active"><a href="#mapsearch" data-toggle="tab">Explore using Map</a></li>
            <li><a href="#tagcloudsearch" data-toggle="tab">Popular Tags</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="mapsearch">
                <div class="col-sm-6" style="background-color:none;">
                    <!--<h2>Search by State</h2>-->
                    <?php include_once ('indiamap.php'); ?>
                </div>
            </div>
            <div class="tab-pane fade" id="tagcloudsearch">
                <div class="col-sm-6" style="background-color:none;" >            
                    <!--<h2>Search by Tagcloud</h2>-->
                    <div id="tagcloud">
                        <?php
                        $tags_for_cloud = top_tags_front_page();
                        show_tag_cloud($tags_for_cloud);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        </div>

    </div>
    <?php InsertFooter() ?>               
    </body>
    <?php InsertCommonJS() ?>
    <script src="scripts/custom.js"></script>

</html>

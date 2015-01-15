<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include("ui_functions.php");    
   session_start();
?>
<?php
            if (isset($_POST["detail_submit"])){              
                $search_error = 0;
                $select = mysql_prep(filter_input(INPUT_POST, 'select'));            
                $period = mysql_prep(filter_input(INPUT_POST, 'period'));   
                session_start(); //need this to access session variables
                $_SESSION["select"] = $select;
                $_SESSION["period"] = $period;
                if($select=='Center'){
                    $centerdept = mysql_prep(filter_input(INPUT_POST, 'centerlist'));                    
                    $_SESSION["centerdept"] = $centerdept;                                           
                    //actually below info can be passed in a query string really!                
                    redirect_to("searchresults_detailed.php");
                }
                else if($select=='State'){
                    $state = mysql_prep(filter_input(INPUT_POST, 'state'));
                    $city = mysql_prep(filter_input(INPUT_POST, 'cities'));
                    $statedept = mysql_prep(filter_input(INPUT_POST, 'stateministries'));                                    
                    $_SESSION["state"] = $state;
                    $_SESSION["city"] = $city;
                    $_SESSION["statedept"] = $statedept;
                    redirect_to("searchresults_detailed.php");
                    
                }                
                
                
            }
?>

<html>
    <?php InsertCommonHeader() ?>
    <body>    
        <?php InsertNavbar() ?>
        
        <form id="searchpages" method="post" class="form-horizontal" role="form">
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>                
            <div id="content" class="container">
                <div class="styled-index">
                    <div>
                        <div class="radio-inline"><label><input type="radio" name="select" id="centerradio" checked=1 value="Center"/> Center </label></div>
                        <div class="radio-inline"><label><input type="radio" name="select" id="stateradio" value="State"/> State</label></div>        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">  
                            <input class="form-control" name="centerlist" type="text" id="centerlist" value="" placeholder="Department Name" title="Type Ministry name like Ministry of Finance"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                        <select name="state" id="state" class="form-control btn">
                            <?php
                                   $states_set  = find_all_states();
                                    echo "<option value='-1'>" . "Select State" . "</option>";
                                    while($states = mysqli_fetch_array($states_set)){
                                            if ($state!="" && $state==$states['StateName'] ){
                                                echo "<option value='" . $states['StateName'] . "' selected=\"\">" . $states['StateName'] . "</option>";
                                            }
                                            else{
                                                echo "<option value='" . $states['StateName'] . "'>" . $states['StateName'] . "</option>";
                                            }
                                                                            
                                    }
                            ?>      
                        </select>
                        </div>                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4-offset col-sm-4">                    
                            <input name="cities" class="form-control" type="text" id="cities" value="" placeholder="City" title="Type City Name">                    
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4-offset col-sm-4">                                                            
                            <input name="period"  class= "form-control" type="text" id="period" value="" placeholder="Period" title="Enter year for which information was requested through the RTI. Example 2013 or a range like 2009-2010."></p>                    
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4-offset col-sm-4">                                        
                            <input class="form-control" name="stateministries" type="text" id="stateministries" value="" placeholder="Department Name" title="Type Ministry name like Revenue Department"></p>                    
                        </div>
                    </div>
                    <div class="form-group"><div class="col-sm-2">
                        <button name ="detail_submit" type="submit" class="btn btn-success btn-lg"><strong>Search</strong></button>
                    </div></div>
                </div>
            </div>
            
        </form>
    </body>
    <?php InsertCommonJS() ?>
    <script src="scripts/detailedindexpagescripts.js"></script>
</html>

<!--http://www.pontikis.net/blog/jquery-ui-autocomplete-step-by-step-->

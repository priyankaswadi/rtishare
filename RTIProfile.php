<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include_once("ui_functions.php");    
   session_start();
   
?>
<html>
    <?php InsertCommonHeader() ?>    
    <body>    
    <?php InsertNavbar() ?>
        
        <form id="RTIProfile">
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>               
            <div class="content">
            <?php
                $id = filter_input(INPUT_GET, 'id');
                $rti = find_rti_by_id($id);
                $updatepagecount= updatepageviews($id);
                $period = $rti['Period'];
                $folder = $rti['Location'];
                if($folder!=""){//add / for path
                    $folder = $folder."/";                    
                }
                else{
                    $folder=""; //make sure folder value is empty
                }
                if($period==""){
                    $period = "Not specified";
                }
                $dept = $rti['Center_Or_State'];
                echo "<div class='rtiprofile'>";
                if($dept=="Center"){
                    echo "<h1>".$rti['Title']."</h1><div class='rtidates'>Filing Date:".$rti['FilingDate']."<br /> Response Date:".$rti['ResponseDate']."<br />".$rti['Department']."<br />Period: ".$period."</div><br />";
                }
                else if($dept=="State"){
                    echo "<h1>".$rti['Title']."</h1><div class='rtidates'>Filing Date:".$rti['FilingDate']."<br /> Response Date:".$rti['ResponseDate']."<br />".$rti['Department']." of ".$rti['State']."<br />Period: ".$period."</div><br />";
                }
                echo $rti['Summary']."<br /><br /><br />";                
                echo "<div class='uploaddata'>Uploaded on ".$rti['UploadDate']."</div>";                
                $rtitags = find_rtitags_by_id($id);
                if(mysqli_num_rows($rtitags)>0){
                        while($tag = mysqli_fetch_array($rtitags)){                                
                                echo "<span class='highlighttag'>".$tag['TagName']."</span>   ";
                            }
                        
                }
                echo "<br /><br />";
                $rtifilenames = $rti['DocumentPath'];
                $rtidocs = explode(",",$rtifilenames); 
                echo "<div align='center' >";
                foreach ($rtidocs as $rtidoc){
                    if($rtidoc!=""){//if no documents are uploaded 
                        if(strlen($rtidoc)> 20){//longer filenames are disturbing the layout of the download images hence need to limit them                     
                            echo "<a class='rtidoc' href='uploads/". $folder.htmlentities($rtidoc)."' target='_blank'><img src='images/download.png' width='80' height='80'><br>View RTI Reply<br>".substr($rtidoc,0,20)."...</a> ";
                        }
                        else{
                            echo "<a class='rtidoc' href='uploads/". $folder.htmlentities($rtidoc)."' target='_blank'><img src='images/download.png' width='80' height='80'><br>View RTI Reply<br>".$rtidoc."</a> ";
                        }
                            
                    }
                }
                echo "</div>";
                
                //echo "<div align='center'><a href='uploads/testpng.png' target='_blank'><img src='images/download.png' width='100' height='80'><br>View RTI Reply</a></div>";                
                echo "</div>";                
                echo "<div class='rtipageviews'>Page Views: ".$rti['PageViewCount']."</div>";
            ?>
            </div>
        </form>
    </body>
    <?php InsertCommonJS() ?>    
</html>



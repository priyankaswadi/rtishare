<?php
//protect script from being accessed outside AJAX calls
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!IS_AJAX) {die('Restricted access');} 
$records_per_page=   20; // records to show per page    
require_once("dbconnection.php");   
include("functions.php");
include("tagcloud_functions.php"); 
include("ui_functions.php");
session_start();
if( isset( $_POST['p'] ) )
{ 
    $page=intval( $_POST['p'] );    
    $select = $_SESSION["select"];                  
    $period = $_SESSION["period"];                  
    if($select=='Center'){
        $centerdept = $_SESSION["centerdept"];        
        $result=find_rtis_by_center($centerdept,$period);                            
    }
    else if($select=='State'){                    
        $state = $_SESSION["state"];
        $city = $_SESSION["city"];
        $statedept = $_SESSION["statedept"];
        $result=find_rtis_by_state($state,$city,$statedept,$period);
    }                
    $current_page=   $page - 1;    
    $start =   $current_page * $records_per_page;
    $showeachside = 5; //  Number of items to show either side of selected page    
    $html =   "";    
    $count = 0;
    $total_pages = 0;        
    $count=  mysqli_num_rows($result);
    $total_pages    =   ceil($count / $records_per_page);        
    //get records
    if($select=='Center'){
        $result = find_rtis_by_center_with_paging($centerdept, $period, $start, $records_per_page);        
    }
    else if($select=='State'){                    
        $result = find_rtis_by_state_with_paging($state,$city,$statedept,$period,$start,$records_per_page);
    }
    if($count>0){
        $html.= "<div class='container col-sm-6'>";
        $html.= "<br />Here's a list of relevant RTI Applications:<br /><br />";
        $html.="Page ".($current_page+1)." of ".$total_pages."<br />";    
        $html.= InsertPagedRTITable($result);
        }
    else{
        $html.="No RTI Application found. Please try again.";
    }           
    $eitherside = ($showeachside * $records_per_page);
    $pagination     =   '';
    if($total_pages>1){
        if($start+1 > $eitherside){
            $pagination .='<span class="page dark active">.....</span>';
        }
        $pg=1;
        for($y=0;$y<$count;$y+=$records_per_page)
        {        
            if(($y > ($start - $eitherside)) && ($y < ($start + $eitherside)))
            {
                if($y==$start){
                    $pagination .='<span class="page dark active">'.$pg.'</span>';
                }
                else{
                    $pagination .='<a href="#'.$pg.'" class="page dark gradient">'.$pg.'</a>';
                }
            
            }
            $pg++;
        }
        if(($start+$eitherside)<$count){
           $pagination .='<span class="page dark active">.....</span>';
        }
    }
    //returning data
    $data           =   array(
                            'html'          =>   $html,
                            'total_pages'   =>   $total_pages,
                            'pagination'    =>   $pagination
                        );
    echo json_encode($data);
 
}
?>
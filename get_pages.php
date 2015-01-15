<?php
//protect script from being accessed outside AJAX calls
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!IS_AJAX) {die('Restricted access');} 
$records_per_page=   20; // records to show per page    
require_once("dbconnection.php");   
include("functions.php"); 
include("validation_functions.php"); 
include("ui_functions.php");
session_start();
if( isset( $_POST['p'] ) )
{ 
    $page=intval( $_POST['p'] );    
    $search_error = 0; //no errors initialize to 0        
    $alltags = $_SESSION["alltags"];    
    if(has_presence($alltags)){
        $temptags= trim($alltags,", ");
        $tags = explode(',', $temptags);  
    }
    else{
        $tags = [];
    }
    //temporary table is deleted automatically after script finishes executing
    $create_query = "CREATE TEMPORARY TABLE IF NOT EXISTS search (word VARCHAR(30))";
    $create_result = mysqli_query($connection,$create_query);                            
    if($create_result){
        $testtag = 0;                
        foreach($tags as $tag){                                                                           
            $tag= trim($tag," ");         
            $tag = mysql_prep($tag);
            $query1 = "INSERT INTO search (word) VALUES ('{$tag}')";                    
            $result1 = mysqli_query($connection,$query1);          
            if(!$result1){
                $testtag = 1;
            }
        }
        if($testtag===1){
            $search_error=1;
        }                                                
        else{                    
            $str_tags = implode(" ", $tags); //for full text search
            $str_tags = mysql_prep($str_tags);
            $result=  find_rtis_by_tags_only($str_tags);                         
        }                
    }
    else{
                    $search_error = 1;
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
    $result = find_rtis_by_tags_only_with_paging($str_tags,$start,$records_per_page);
    if($count>0){
        $html.= "<div class='container col-sm-6'>";
        $html.= "<br />Here's a list of relevant RTI Applications:<br /><br />";
        $html.="Page ".($page)." of ".$total_pages."<br />";
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
    if($search_error===1){
                    $html.="Something went wrong with your search. Try again!";
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
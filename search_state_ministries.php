<?php
    //protect script from being accessed outside AJAX calls
    define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    if(!IS_AJAX) {die('Restricted access');} 
    
    require_once("dbconnection.php");   
    include("functions.php");               
    $term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends            
    $state_ministries_set = find_all_state_ministries_by_name($term);    
    while($state_ministries = mysqli_fetch_array($state_ministries_set)){
                $state_ministry = htmlentities(stripslashes($state_ministries['MinistryName']));
		$row_set[] = $state_ministry;
    }
     //   $row_set = ["Nashik","Nagpur","Nagaland","Narmada"];
	echo json_encode($row_set);//format the array into json data        
    
    
   
    
?>




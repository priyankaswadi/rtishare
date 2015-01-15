
<?php
    //protect script from being accessed outside AJAX calls
    define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    if(!IS_AJAX) {die('Restricted access');}
    
    require_once("dbconnection.php");   
    include("functions.php");               
    $term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends            
    $benefitsmost_set = find_all_benefitsmost($term);    
    while($benefitsmost_list = mysqli_fetch_array($benefitsmost_set)){
                $benefitsmost= htmlentities(stripslashes($benefitsmost_list['WhoBenefitsMost']));
		$row_set[] = $benefitsmost;
    }
    //    $row_set = ["Nashik","Nagpur","Nagaland","Narmada"];
	echo json_encode($row_set);//format the array into json data        
    
    
   
    
?>




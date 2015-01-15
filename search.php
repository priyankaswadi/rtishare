<?php
    
    require_once("dbconnection.php");   
    include("functions.php");               
    $term = trim(strip_tags($_GET['term']));//retrieve the search term that autocomplete sends    
    $state = trim(strip_tags($_GET['state']));    //this part not working
    //$state ="Punjab"; //if we somehow gte state here rest of the stuff works
    $cities_set = find_all_cities_by_name($term,$state);    
    while($cities = mysqli_fetch_array($cities_set)){
                $city = htmlentities(stripslashes($cities['CityName']));
		$row_set[] = $city;
    }
     //   $row_set = ["Nashik","Nagpur","Nagaland","Narmada"];
	echo json_encode($row_set);//format the array into json data        
    
    
   
    
?>




<?php
    
    define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    if(!IS_AJAX) {die('Restricted access');} 
    
    require_once("dbconnection.php");   
    include("functions.php"); 
    //function generate_cities_dropdown(){
           // $state = $_POST['state_id'];
            $state = filter_input(INPUT_POST, 'state_id'); //better way of using POST            
            echo $state;
            $cities_set = find_cities_by_state($state);           
            $row_cnt = mysqli_num_rows($cities_set);
            echo $row_cnt;
            //echo "<p><label>Cities </label><select name=\"cities\">";    
            while($cities = mysqli_fetch_array($cities_set)){
                echo "<option value='" . $cities['CityName'] . "'>" . $cities['CityName'] ."</option>";                                                                           
            }    
            //echo "</select>";
    //}



?>




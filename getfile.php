<?php
//For ajax post request to get file on file upload for part 4 of upload RTI page
//protect script from being accessed outside AJAX calls
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!IS_AJAX) {die('Restricted access');} 
$output_dir = "uploads/";
session_start();
$unique = $_SESSION['unique_id'];
if($unique==""){
    die('No session information found. Please sign in again. Cannot upload files.');
}
if(!is_dir("uploads/".$unique)){
    mkdir("uploads/".$unique, 0700);
}
$output_dir = "uploads/".$unique."/";
if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myfile"]["name"];
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
                $ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    echo json_encode($ret);
 } 
 ?>
 

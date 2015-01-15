<?php
//For ajax post request to delete file on file upload for part 4 of upload RTI page
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

if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
{
	$fileName =$_POST['name'];
	$filePath = $output_dir. $fileName;
	if (file_exists($filePath)) 
	{
        unlink($filePath);
    }
	//echo "Deleted File ".$fileName."<br>";
}
?>
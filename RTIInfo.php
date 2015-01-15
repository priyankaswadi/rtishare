<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   session_start();
   
?>
<html><title></title>    
    <link rel="shortcut icon" href="images/rti.ico">
    <link rel="stylesheet" href="stylesheets/default.css">               
    <body>    
        <div id="menu">
            <ul id="nav">
                <li class=""><a href="index.php">Home</a></li>
                <li><a href="upload.php">Upload An RTI</a></li>
                <li><a href="#">RTI Info</a></li>				
                <li><a href="AboutUs.php">About Us</a></li>				
                <li><a href="ContactUs.php">Contact Us</a></li>
            </ul>
        </div>
        <?php include_once ('signinheader.php');?>				
        <form>
            <div id="header"><h1>Welcome to RTI Search Portal</h1></div>   
            <div id="content">              
                RTI Information
            </div>        
        </form>
    </body>
</html>
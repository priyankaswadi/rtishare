<!DOCTYPE html>
<?php
   require_once("dbconnection.php");   
   include("functions.php"); 
   include("validation_functions.php"); 
   include_once("ui_functions.php");   
   session_start();   
?>

<html lang="en">
    <?php InsertCommonHeader() ?>

    <body>
        <?php InsertNavbar(); ?>
    
        <form>
            <!--Empty header below to headline the body-->
            <div class="bs-docs-header" id="header"><div class="container"><h3>
                        About Us
            </h3></div></div>

            <div id="content" class="container">
                
                <div class="col-sm-6">
                <p>
                The <a href="http://en.wikipedia.org/wiki/Right_to_Information_Act">Right To Information act</a>, 
                has changed the status-quo, 
                shaken Indian bureaucracy and has made it more accountable to the people it serves.
                Since 2005, it has become a practical and effective tool to bypass and expose the 
                corruption in public service delivery.
                This website began out of an inspiration from many RTI success stories, and 
                RTI activists who spread awareness, help navigate the process, and 
                sometimes even risk their lives to unmask corruption and injustice.
                As software engineers, we were looking to somehow help accelerate this virtuous cycle of transparency 
                through what we know best, which is creating websites.
                </p>
                
                <p>
                In our quest,
                we came across amazing websites which have made 
                it ever easier to file an RTI, even anonymously, if so desired.
                Yet there was a <strong>gap</strong>. 
                There was a lack of a single, searchable platform to share such RTI responses.
                Such a platform, we thought, could achieve wider dissemination of public interest RTIs which are useful to many people.
                By making it easy to <strong>tag</strong>, <strong>share</strong>, <strong>vote</strong>, 
                <strong>search</strong> and <strong>discover</strong> such content, 
                one could promote easy discovery and cross-pollination of information obtained through RTIs.
                </p>
                
                <p>
                <a href="http://rtishare.org"><strong>RTIshare.org</strong></a> is a website designed to bridge this gap.
                We intend to run it as a non-profit service, and with an open source (link to our git repo).
                We do not intend to compete with other excellent platforms fueling the RTI revolution, 
                but merely to help bridge a gap and plant a seed. We hope that it grows and helps 
                bravehearts everywhere who choose to walk this path.
                <br><br />
                <em>Power to the people! </em>
                </p>
                
                </div>
            </div> 
        </form>
    </body>
    <?php InsertCommonJS() ?>    
</html>
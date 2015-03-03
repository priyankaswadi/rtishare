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
    
            <!--Empty header below to headline the body-->
            <div class="bs-docs-header" id="header"><div class="container"><h3>
                        Frequently Asked Questions
            </h3></div></div>

            <div id="content" class="container">

            
                           
<div class="bs-example col-md-10">
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">1. What is RTIShare.org?</a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <p>RTIShare.org is a non-profit service designed to help share and search RTI applications.</p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">2. How does RTIShare.org work?</a>
                </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>People upload the RTI applications and information obtained through them to RTIShare.org. These RTIs then become public and anyone can easily access this information and search through RTIShare.org.</p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">3. How can I upload an RTI to RTIShare.org?</a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>You will need to register with RTIShare.org to upload an RTI. </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">4. How do I find RTIs filed in my city?</a>
                </h4>
            </div>
            <div id="collapseFour" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>There are various ways to do so. You can use the Search by popular tags option on the home page. Or you can use the map and browse to your state to see if any information related to your city exists. Or you can search in detail <a href="detailedsearch.php">here</a>.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">5. Why are there so many ways to search an RTI?</a>
                </h4>
            </div>
            <div id="collapseFive" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>We just wanted to provide our users various options to quickly get to the information they are interested in. No one option is better than another.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">6. What type of files can I upload to RTIShare.org?</a>
                </h4>
            </div>
            <div id="collapseSix" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>You can upload files with extension  with extensions png,gif,jpg,jpeg,doc,docx,pdf. Also note that we currently do not allow files greater than 5 MB to be uploaded.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">7. Who can see an RTI I uploaded?</a>
                </h4>
            </div>
            <div id="collapseSeven" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Any user can see the RTI and related information that you uploaded.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseEight">8. Can I upload RTIs anonymously?</a>
                </h4>
            </div>
            <div id="collapseEight" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Absolutely! You can choose this option while you are uploading RTIs.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">9. I have problems signing in, what should I do?</a>
                </h4>
            </div>
            <div id="collapseNine" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>You can use the <a href="forgotpassword.php">Forgot Password</a> option. If that doesn't work please <a href="contactus.php">contact us </a></p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTen">10. I have technical issues using the website, what should I do?</a>
                </h4>
            </div>
            <div id="collapseTen" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Please <a href="contactus.php">contact us</a> for any support.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseEleven">11. What caution should I exercise when I upload an RTI?</a>
                </h4>
            </div>
            <div id="collapseEleven" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Since all the information you upload is in public domain, you might want to remove data which identifies you (like your name, address etc.) from your documents. </p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse12">12. I want to file an RTI. Can RTIShare.org help me with it?</a>
                </h4>
            </div>
            <div id="collapse12" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Unfortunately no. We are just an online service to share and search information.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse13">13. What can I do to make my RTI easily searchable and reach a greater audience?</a>
                </h4>
            </div>
            <div id="collapse13" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Its easy! We ask for a lot of information when you upload your RTI. Make sure you answer those questions as best as you can. Also you will be asked to enter tags related to your application. These will help users find your RTI easily.</p>
                </div>
            </div>
        </div>        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse14">14. Can I edit or delete RTI applications I have uploaded?</a>
                </h4>
            </div>
            <div id="collapse14" class="panel-collapse collapse">
                <div class="panel-body">
                    <p>Right now the only way to do this is to <a href="contactus.php">contact us </a>. But we are working on it, and would soon allow you to do this yourself.</p>
                </div>
            </div>
        </div>        
    </div>
</div>

</div> 
    <?php InsertFooter() ?>                
    </body>
    <?php InsertCommonJS() ?>    
</html>
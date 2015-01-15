<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("validation_functions.php");
include("admin_functions.php");
include_once("ui_functions.php");

session_start();
if (!isset($_SESSION['user_email'])) { //RTIs can be uploaded only when user is signed in
    $_SESSION['message'] = "You need to login to upload RTIs.";
    $_SESSION['unlogged_user'] = ""; //reset this variable if it exists to display blank Email ID field when directed to login.php
    redirect_to("login.php?from_upload=1");
}
//uniqid is  unique as it is based on unix timestamp. But if someone resets their system date it
//could be a problem. Also if multiple requests are sent to fileserver at same time there could be a 
//potential problem. Hence added a random number generator with it, to reduce the probability of duplication 
//and/or overwriting. Combining the USER ID info when signup is implemented will make this even better
// and chance of duplication will be totally eliminated.
if (!isset($_POST["submit"])) { //need to check this else unique_id changes when submit is clicked as submit causes form to reload.
    $_SESSION['unique_id'] = uniqid(TRUE) . rand(1, 100);
}
?>
<!--If there is php code below in the html form submit doesn't work at the bottom of this page as all header
information is sent already! DOn't know what error this is but putting code here works. -->
<?php
if (isset($_POST["submit"])) {
    $select = mysql_prep(filter_input(INPUT_POST, 'select'));
    //form part2
    $title = mysql_prep(filter_input(INPUT_POST, 'title'));
    $summary = mysql_prep(filter_input(INPUT_POST, 'summary'));
    $filingdate1 = mysql_prep(filter_input(INPUT_POST, 'filingdate'));
    $responsedate1 = mysql_prep(filter_input(INPUT_POST, 'responsedate'));
    $selectsatisfied = mysql_prep(filter_input(INPUT_POST, 'selectsatisfied'));
    //form part3
    $alltags = mysql_prep(filter_input(INPUT_POST, 'tags'));
    if (has_presence($alltags)) {
        $temptags = trim($alltags, ", ");
        $tags = explode(',', $temptags);
    } else {
        $tags = [];
    }
    $trimmed_tags = array_map('trim', $tags); //remove white spaces around each tag
    $lowercase_tags = array_map('strtolower', $trimmed_tags);
    $tags = array_unique($trimmed_tags);
    $benefitsmost = mysql_prep(filter_input(INPUT_POST, 'benefitsmost'));
    $period = mysql_prep(filter_input(INPUT_POST, 'period'));
    $selffiled = mysql_prep(filter_input(INPUT_POST, 'selffiled'));
    $filingdate = DateTime::createFromFormat('d-m-Y', $filingdate1)->format('Y-m-d'); //convert date for mySQL to date format
    $responsedate = DateTime::createFromFormat('d-m-Y', $responsedate1)->format('Y-m-d');
    if ($selffiled == "0") {
        $activistname = mysql_prep(filter_input(INPUT_POST, 'activistname'));
    } else {
        $activistname = "";
    }

    $selectanon = mysql_prep(filter_input(INPUT_POST, 'selectanon'));
    if ($selectanon == "0") {
        $selectcontact = mysql_prep(filter_input(INPUT_POST, 'selectcontact'));
    } else {
        $selectcontact = "";
    }

    $rtidoc = mysql_prep(filter_input(INPUT_POST, 'fileslist'));
    $location = $_SESSION['unique_id'];
    //form part1
    if ($select == "Center") {
        $centralministry = mysql_prep(filter_input(INPUT_POST, 'center_dropdown'));
        $central_not_listed = mysql_prep(filter_input(INPUT_POST, 'centerlist'));
        $notify_admin = 0;
        if ($central_not_listed != "") {
            $centralministry = $central_not_listed; //if some value was entered in this textbox use that else use the dropdown value
            $notify_admin = 1;
        }
        //Only as a safety check, as all validations are already done in jQuery.
        if (!doValidations_forCenterUpload($centralministry, $title, $summary, $filingdate, $responsedate)) {
            echo "Something went wrong! Please upload your RTI again and make sure all fields are correct. Sorry for the incovenience :(";
        } else {
            $uploaddate = date('Y-m-d');
            $query = "INSERT INTO rti_application 
                          (Center_Or_State,Title,Summary,UploadDate,FilingDate,ResponseDate,Satisfied,
                           Department,RTISelfFiled,ActivistName,WhoBenefitsMost,Period,
                           UploadAnonymous,AllowContact,DocumentPath,Location,PageViewCount)
                 	   VALUES ('{$select}','{$title}','{$summary}','{$uploaddate}','{$filingdate}','{$responsedate}',"
                    . "'{$selectsatisfied}','{$centralministry}',"
                    . "'{$selffiled}','{$activistname}','{$benefitsmost}','{$period}','{$selectanon}',"
                    . "'{$selectcontact}','{$rtidoc}','{$location}',0)";
            $result = mysqli_query($connection, $query);
            $testtag = 0;
            $rtiid = mysqli_insert_id($connection);
            if ($result) {
                if (count($tags) > 0) {
                    foreach ($tags as $tag) {
                        $tag = trim($tag, " ");
                        $tag = strtolower($tag);
                        if (is_state($tag)) {
                            $query1 = "INSERT INTO rtitags (TagName,RTIID,IsParent)VALUES ('{$tag}','{$rtiid}',1)";
                            $result1 = mysqli_query($connection, $query1);
                            if (!$result1) {
                                $testtag = 1;
                            }
                        } else if ($tag != strtolower($centralministry)) { //check if user has chosen central ministry name as tag
                            $query1 = "INSERT INTO rtitags (TagName,RTIID)VALUES ('{$tag}','{$rtiid}')";
                            $result1 = mysqli_query($connection, $query1);
                            if (!$result1) {
                                $testtag = 1;
                            }
                        }
                    }
                }
                //insert central ministry as a tag
                $query2 = "INSERT INTO rtitags (TagName,RTIID)VALUES (LOWER('{$centralministry}'),'{$rtiid}')";
                $result2 = mysqli_query($connection, $query2);
                if (!$result2) {
                    $testtag = 1;
                }
                if ($testtag === 1) {
                    echo "There was some error uploading your tags. Please check them again or contact admin";
                } else {
                    if ($notify_admin) {
                        notify_admin_about_new_data('central ministry', $rtiid);
                    }
                    redirect_to("uploadrtisuccess.php");
                }
            } else {
                echo "Failed to Upload Application. Please try again or contact administrator.";
            }
        }
    } elseif ($select == "State") {
        $state = mysql_prep(filter_input(INPUT_POST, 'state'));
        $notify_admin_city = 0;
        $notify_admin_ministry = 0;
        $cities = mysql_prep(filter_input(INPUT_POST, 'cities'));
        $cities_not_listed = mysql_prep(filter_input(INPUT_POST, 'cities_not_listed'));
        if ($cities_not_listed != "") {
            $notify_admin_city = 1;
            $cities = $cities_not_listed; //if some value was entered in this textbox use that else use the dropdown value
        }
        $taluka = mysql_prep(filter_input(INPUT_POST, 'taluka'));
        $stateministry = mysql_prep(filter_input(INPUT_POST, 'stateministries_dropdown'));
        $stateministry_not_listed = mysql_prep(filter_input(INPUT_POST, 'stateministries'));
        if ($stateministry_not_listed != "") {
            $notify_admin_ministry = 1;
            $stateministry = $stateministry_not_listed; //if some value was entered in this textbox use that else use the dropdown value
        }
        $pincode = mysql_prep(filter_input(INPUT_POST, 'zipcode'));
        if (!doValidations_forStateUpload($state, $cities, $stateministry, $pincode, $title, $summary, $filingdate, $responsedate)) {
            echo "Something went wrong! Please upload your RTI again and make sure all fields are correct. Sorry for the incovenience :(";
        } else {
            $uploaddate = date('Y-m-d');
            $query = "INSERT INTO rti_application 
                          (Center_Or_State,Title,Summary,State,City,Taluka,PinCode,UploadDate,FilingDate,
                           ResponseDate,Satisfied,Department,RTISelfFiled,ActivistName,WhoBenefitsMost,Period,
                           UploadAnonymous,AllowContact,DocumentPath,Location,PageViewCount)
                 	   VALUES ('{$select}','{$title}','{$summary}','{$state}','{$cities}','{$taluka}',"
                    . "'{$pincode}', '{$uploaddate}','{$filingdate}','{$responsedate}',"
                    . "'{$selectsatisfied}','{$stateministry}',"
                    . "'{$selffiled}','{$activistname}','{$benefitsmost}','{$period}','{$selectanon}',"
                    . "'{$selectcontact}','{$rtidoc}','{$location}',0)";
            $result = mysqli_query($connection, $query);
            $testtag = 0;
            $rtiid = mysqli_insert_id($connection);
            if ($result) {
                if (count($tags) > 0) {
                    foreach ($tags as $tag) {
                        $tag = trim($tag, " ");
                        $tag = strtolower($tag);
                        if (is_state($tag) && $tag != strtolower($state)) { //check if it is a state but also check if it is not the same state for which RTI is being uploaded
                            $query1 = "INSERT INTO rtitags (TagName,RTIID,IsParent)VALUES ('{$tag}','{$rtiid}',1)";
                            $result1 = mysqli_query($connection, $query1);
                            if (!$result1) {
                                $testtag = 1;
                            }
                        } else if ($tag != strtolower($state) && $tag != strtolower($cities) && $tag != strtolower($taluka) && $tag != strtolower($stateministry)) { //check if user has chosen state,city,taluka or ministry as tag
                            $query1 = "INSERT INTO rtitags (TagName,RTIID)VALUES ('{$tag}','{$rtiid}')";
                            $result1 = mysqli_query($connection, $query1);
                            if (!$result1) {
                                $testtag = 1;
                            }
                        }
                    }
                }
                //insert state as tag
                $query2 = "INSERT INTO rtitags (TagName,RTIID,isParent)VALUES (LOWER('{$state}'),'{$rtiid}',1)";
                $result2 = mysqli_query($connection, $query2);
                if (!$result2) {
                    $testtag = 1;
                }
                //insert city as tag
                $query3 = "INSERT INTO rtitags (TagName,RTIID)VALUES (LOWER('{$cities}'),'{$rtiid}')";
                $result3 = mysqli_query($connection, $query3);
                if (!$result3) {
                    $testtag = 1;
                }
                //insert taluka as tag
                if ($taluka) {
                    $query4 = "INSERT INTO rtitags (TagName,RTIID)VALUES (LOWER('{$taluka}'),'{$rtiid}')";
                    $result4 = mysqli_query($connection, $query4);
                    if (!$result4) {
                        $testtag = 1;
                    }
                }
                //insert state ministry as tag
                $query5 = "INSERT INTO rtitags (TagName,RTIID)VALUES (LOWER('{$stateministry}'),'{$rtiid}')";
                $result5 = mysqli_query($connection, $query5);
                if (!$result5) {
                    $testtag = 1;
                }
                if ($testtag === 1) {
                    echo "There was some error uploading your tags. Please check them again or contact admin";
                } else {
                    if ($notify_admin_city) {
                        notify_admin_about_new_data('city', $rtiid);
                    }
                    if ($notify_admin_ministry) {
                        notify_admin_about_new_data('state ministry', $rtiid);
                    }
                    redirect_to("uploadrtisuccess.php");
                }
            } else {
                echo "Failed to Upload Application. Please try again or contact administrator.";
            }

            //    redirect_to("uploadrtisuccess.php");
        }
    }
}
?>                


<html lang="en">
    <?php InsertCommonHeader() ?>

    <body>
        <?php InsertNavbar(); ?>

        <div class="bs-docs-header" id="header"><div class="container"><h3>
                    Upload your RTI
                </h3></div></div>

        <div id="content" class="container">              
            <progress id="progress" value='0' max='100' ></progress>
            <span class="progresslabel" id="progressvalue"></span>

            <form id="upload" method="post" class="form-horizontal" role="form">
                <div class="styled-upload">

                    <div id="page1" class="container">
                        <div class="radio-inline" >
                            <label><input type="radio" name="select" id="centerradio" checked=1 value="Center"/> Center </label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="select" id="stateradio" value="State"/> State</label>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <select class="form-control btn" name="center_dropdown" id="center_dropdown">
                                    <?php
                                    $center_set = find_all_central_ministries();
                                    echo "<option value='-1'>" . "Select Department" . "</option>";
                                    while ($centers = mysqli_fetch_array($center_set)) {
                                        if ($cent != "" && $cent == $centers['MinistryName']) {
                                            echo "<option value='" . $centers['MinistryName'] . "' selected=\"\">" . $centers['MinistryName'] . "</option>";
                                        } else {
                                            echo "<option value='" . $centers['MinistryName'] . "'>" . $centers['MinistryName'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">                            
                                <input class="form-control" name="centerlist" id="centerlist" value="" type="text" placeholder="Is the department not listed?" title="Type the name of ministry for example, Ministry of Defence">
                            </div>
                        </div>

                        <div class="form-group"> 
                            <div class="col-sm-4">
                                <select class="form-control btn" name="state" id="state">
                                    <?php
                                    $states_set = find_all_states();
                                    echo "<option value='-1'>" . "Select State" . "</option>";
                                    while ($states = mysqli_fetch_array($states_set)) {
                                        if ($state != "" && $state == $states['StateName']) {
                                            echo "<option value='" . $states['StateName'] . "' selected=\"\">" . $states['StateName'] . "</option>";
                                        } else {
                                            echo "<option value='" . $states['StateName'] . "'>" . $states['StateName'] . "</option>";
                                        }
                                    }
                                    ?>      
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <select class="form-control btn" name="cities" id="cities"><option value='-1'>Select City</option></select>
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control" name="cities_not_listed" id="cities_not_listed" 
                                       type="text" value="" placeholder="Type city name if not listed." 
                                       title="Type city name if not listed." >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4-offset col-sm-4">
                                <input class="form-control" name="taluka" id="taluka" value="" type="text" placeholder="Taluka (Optional)">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <select class="form-control btn" name="stateministries_dropdown" id="stateministries_dropdown">
                                    <?php
                                    $state_ministries_set = find_all_state_ministries();
                                    echo "<option value='-1'>" . "Select Department" . "</option>";
                                    while ($state_ministries = mysqli_fetch_array($state_ministries_set)) {
                                        if ($statem != "" && $statem == $state_ministries['MinistryName']) {
                                            echo "<option value='" . $state_ministries['MinistryName'] . "' selected=\"\">" . $state_ministries['MinistryName'] . "</option>";
                                        } else {
                                            echo "<option value='" . $state_ministries['MinistryName'] . "'>" . $state_ministries['MinistryName'] . "</option>";
                                        }
                                    }
                                    ?>      
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control" name="stateministries" id="stateministries" 
                                       value="" type="text" placeholder="Type department name if not in the list."  
                                       title="Type department name if not in the list. E.g. Revenue Department">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <input class="form-control" name="zipcode" id="zipcode" value="" type="text" placeholder="Pin code" title="This is 6 digit">
                            </div>
                        </div>

                        <div class="form-group"><div class="col-sm-2">
                                <button class="form-control btn-success input-lg btn-lg" type="button" id="next1"/>
                                Next <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                </button>   
                            </div></div>
                    </div>




                    <div id="page2" class="container">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <input class="form-control" name="title" type="text" value="" placeholder="Title for RTI" title="Try to keep title simple and specific" id="title" required="" autofocus>
                            </div></div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <textarea class="form-control" name="summary" placeholder="Description" title="Describe your RTI and share interesting details (try to limit it to 150 words)" id="summary" required=""></textarea>
                            </div></div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <input class="form-control" name="filingdate" id="filingdate" value="" type="text" placeholder="Date of filing RTI" title="Date on which you first filed your RTI" required="">
                            </div></div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <input class="form-control" name="responsedate" id="responsedate" value="" type="text" placeholder="Date of Response on RTI"  title="Date when you received first response on your RTI" required="">
                            </div></div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <label> Were you satisfied with the response? </label>
                                <div id="satisfiedradio">
                                    <div class="radio-inline">
                                        <label><input type="radio" name="selectsatisfied" id="satisfied" checked=1 value="1"/> Yes </label>
                                    </div>
                                    <div class="radio-inline">
                                        <label><input type="radio" name="selectsatisfied" id="notsatisfied" value="0"/> No</label>        
                                    </div>
                                </div>
                            </div></div>

                        <div class="form-group">
                            <div class="col-sm-2">
                                <button class="form-control btn-success  input-lg btn-lg" type="button" id="next2"/>
                                Next <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>    



                    <div id="page3" class="container">

                        <!--All these three options below have to be auto completed from RTI DB table fields and also 
                        from another field of popular entities (a join query will be performed) -->
                        <div class="form-group"><div class="col-sm-4">
                                <input class="form-control" name="tags" id="tags" type="text" value="" title="Was this RTI related to some popular event? Like 2009 elections? CWG scam? " placeholder="Enter popular tags">
                            </div></div>

                        <div class="form-group"><div class="col-sm-4">
                                <input class="form-control" name="benefitsmost" id="benefitsmost" type="text" value="" title="Who would benefit most by learning about this RTI? Example, Students? Or residents of a particular city?" placeholder="Who benefits most">                                               
                            </div></div>

                        <div class="form-group"><div class="col-sm-4">
                                <input class="form-control" name="period" id="period" type="text" value="" title="For what period is this information requested? Enter year. Like 2013 or a range like 2013-2014." placeholder="Period">                    
                            </div></div>

                        <div class="form-group"><div class="col-sm-4">
                                <label>Did you file the RTI Yourself? </label>
                                <label><input class="radio-inline" type="radio" name="selffiled" id="selffiledradio" checked=1 value="1"/> Yes </label>
                                <label><input class="radio-inline" type="radio" name="selffiled" id="notselffiledradio" value="0"/> No</label>        
                                <input class="form-control" name="activistname" id="activistname" value="" type="text" placeholder="Name of Activist (Optional)" title="Enter name of RTI activist here.">
                            </div></div>

                        <div class="form-group"><div class="col-sm-4">
                                <label>Would you like to upload the RTI Anonymously?</label>
                                <label><input class="radio-inline" type="radio" name="selectanon" id="anonyes" checked=1 value="1"/> Yes </label>
                                <label><input class="radio-inline" type="radio" name="selectanon" id="anonno" value="0"/> No</label>        
                            </div></div>

                        <div class="form-group" id="contactdiv"><div class="col-sm-4">
                                <label> Would you like to be contacted by anyone who wants to know more? </label>
                                <label><input class="radio-inline" type="radio" name="selectcontact" id="contactyes" title="Your email ID used for registration will be provided to interested people." value="1"/> Yes </label>
                                <label><input class="radio-inline" type="radio" name="selectcontact" id="contactno" checked=1 value="0"/> No</label>        
                            </div></div>

                        <div class="form-group"><div class="col-sm-2">
                                <button class="form-control col-xs-4 btn-success  input-lg btn-lg" type="button" id="next3"/>Next</button>   
                            </div></div>

                    </div>


                    <div id="page4" class="container">

                        <div class="form-group"><div class="col-sm-4">
                                Please upload the RTI documents here. <br />
                                You can upload multiple documents all less than 5 MB. <br />
                                Click Upload below to add more documents. <br />
                                You can also drag and drop documents here. <br/>
                            </div></div>

                        <div id="fileuploader">Upload RTI Documents</div><br />
                        <p id="fileupload_eventsmessage"></p>                        
                        <input type="hidden" name="fileslist" value="" id="fileslist"/>

                        <div class="form-group"><div class="col-sm-2">
                                <input class="form-control col-xs-4 btn-success input-lg btn-lg" name ="submit" type="submit" value="Submit RTI">
                            </div></div>

                    </div>

                </div>                  
            </form>
        </div>        
    </body>
    <?php InsertCommonJS() ?>
    <script src="scripts/uploadRTIscripts.js"></script> <!--JQuery code for UploadRTI page -->    

</html>
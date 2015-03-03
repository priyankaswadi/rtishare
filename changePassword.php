<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("validation_functions.php");
include("ui_functions.php");
session_start();
if (!isset($_SESSION['user_email'])) { //Check if user is logged in
    $_SESSION['message'] = "You need to login to upload RTIs.";
    redirect_to("login.php");
}
?>

<?php
$errStr = "";
if (isset($_POST["submit"])) {
    $email = $_SESSION['user_email'];
    $pass = mysql_prep(filter_input(INPUT_POST, 'pass'));
    $pass2 = mysql_prep(filter_input(INPUT_POST, 'pass2'));
    $signuperror = 0;
    if ($email == "") {
        $signuperror = 1;
    }
    if ($pass == "") {
        $signuperror = 1;
    }
    if ($pass2 == "") {
        $signuperror = 1;
    }
    if ($signuperror != 1) {
        if ($pass === $pass2) {  //if newly enetered password match then proceed
            $encrypted_pass = password_encrypt($pass);
            $query = "UPDATE users SET Password = '{$encrypted_pass}', temppass_flag = 0 WHERE "
                    . "Email = '{$email}' LIMIT 1 ";
            $result = mysqli_query($connection, $query);
            if ($result && mysqli_affected_rows($connection) == 1) {
                redirect_to("changedpasswordsuccess.php");
            } else { //if query fails
                $errStr = "<div class='error'>Password couldn't be changed. Please try again.</div>";
            }
        } else {
            $errStr = "<div class='error'>Passwords do not match. Please enter them again.</div>";
        }
    } else {
        $errStr = "<div class='error'>Sign Up error! Please try again.</div>";
    }
}
?>

<html>
    <?php InsertCommonHeader() ?>
    <body>
        <?php InsertNavbar(false) ?>
        <form id="resetpass" method="post">
            <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>    
            <div id="content" class="container"> 
                <div class="container col-sm-4">  
                    <h1>Reset Password</h1>
                    <div class="form-group">
                        <input name="pass" type="password" value="" placeholder="New Password" title="A good password should be atleast 8 characters long." id="pass" required="" data-indicator="pwindicator" class="form-control">
                    </div>
                    <div id="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                    </div>
                    <div class="form-group">
                        <input name="pass2" type="password" value="" placeholder="Reenter new Password" title="Reenter your password." id="pass2" required="" class="form-control">
                    </div>
                    <div id="passmatch" class="match"></div><br />
                    <div class="form-group">
                        <input type="submit" value="Submit" name="submit" class="btn btn-success btn-lg"/><br /><br />
                    </div>
                    <?php if (!empty($errStr)) {
                        echo($errStr);
                    } ?>
                </div>                        
            </div>
        </form>        
        <?php InsertFooter(300) ?>               
    </body>
<?php InsertCommonJS() ?>
    <script>
        $(document).tooltip({//http://jqueryui.com/tooltip/                
            position: {my: "left+15 center", at: "right center", collision: "none"}
        });
        $(document).ready(function () {
            jQuery(function ($) {
                $('#pass').pwstrength();
            });
            $("#pass2").keyup(function () {
                var pass = $("#pass").val();
                var pass2 = $("#pass2").val();
                if (pass === pass2) {
                    $('#passmatch').text('Passwords match');
                }
                else {
                    $('#passmatch').text('');
                }

            });
        });
    </script>
</html>
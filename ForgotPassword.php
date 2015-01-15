<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("validation_functions.php");
include_once("ui_functions.php");
session_start();
if (isset($_SESSION['user_email'])) { //Check if user is logged in      
    redirect_to("index.php");
}
?>

<?php
$errStr = "";
if (isset($_POST["submit"])) {
    $email = mysql_prep(filter_input(INPUT_POST, 'email'));
    $user = find_user_by_email($email);
    $errStr = "";
    if (!$user) { //check if user exists 
        $errStr = "<div class='error'>Email ID " . $email . " does not exist.<br /></div>";
    } else {
        if ($user['active'] == 0) { //check if account has been activated before sending temp pass
            $errStr = "<div class='error'>This account has not been activated. Please activate this account before trying to log in."
            . "If you can't find the email to activate your account, please hit resend email below.<br />"
            . "<br /><input type='submit' value='Email Activation Link' name='submit'/></div>";
        } else {
            $pwdsent = send_temp_pass($email);
            if ($pwdsent){
                redirect_to("passwordsent.php");
            } else {
                $errStr = "<div class='error'>"
                        . "Failed to reset passsword or email has not been sent to " . $email 
                        . " Please try again.<br />" . "</div>";
            }
        }
    }
}
?>

<html>
    <?php InsertCommonHeader(); ?>    
    <body>
        <?php InsertNavbar(); ?>
        <form id="forgotpass" method="post">
                        <div class="bs-docs-header" id="header"><div class="container"><h3></h3></div></div>    
            <div id="content" class="container">
                Forgot Password
                <p><input name="email" type="email" value="" placeholder="Email ID" title="Enter your email here" id="email" required="" autofocus></p>
                <input type="submit" value="Submit" name="submit"/><br /><br />
                <?php if (!empty($errStr)) {echo($errStr);} ?>
            </div>

        </form>
    </body>
    <?php InsertCommonJS() ?>
</html>
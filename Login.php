<!DOCTYPE html>
<?php
require_once("dbconnection.php");
include("functions.php");
include("validation_functions.php");
include_once("ui_functions.php");

session_start();
if (isset($_SESSION['user_email'])) { //Check if user is logged in      
    redirect_to("loginsuccess.php");
}
if (!isset($_POST["login"])) {
    $email = "";
    $from_upload = 0;
    if (isset($_SESSION['unlogged_user'])) {//to ensure that this variable is read only when user is directed here from signinheader.php
        $email = $_SESSION['unlogged_user'];
    }
} else {
    $email = mysql_prep(filter_input(INPUT_POST, 'email'));
}
if (isset($_GET['from_upload']) && $_GET['from_upload'] == 1) {
    $from_upload = 1;
}
?>

<?php
$errStr = "";
if (isset($_POST['login'])) {
    $_SESSION['message'] = ""; //to clear message from signinheader.php
    $_SESSION['unlogged_user'] = ""; //to clear email ID from signinheader.php
    $email = mysql_prep(filter_input(INPUT_POST, 'email'));
    $user = find_user_by_email($email);
    if ($user) {
        if ($user['active'] == 0) {
            $errStr = "<div class='error'>This account has not been activated. Please activate this account before trying to log in."
                    . "If you can't find the email to activate your account, please hit resend email below.<br />"
                    . "<br /><input type='submit' value='Email Activation Link' name='submit'/></div>";
        } else {
            $pass = mysql_prep(filter_input(INPUT_POST, 'pass'));
            $hashed_password = password_encrypt($_POST['pass']);
            $found_user = attempt_login($email, $pass);
            if ($found_user) {
                $_SESSION['user_email'] = $email;
                $user = find_user_by_email($email);
                //if redirect was from upload.php and no need to change temp pass, only then go back to upload.php if login successful
                if ($from_upload == 1 && $user['temppass_flag'] == 0) {
                    redirect_to("upload.php");
                } else {
                    redirect_to("loginsuccess.php");
                }
            } else {
                $errStr = "<div class='error'>Login failed. Please try entering your password again.</div>";
            }
        }
    } else {
        $errStr = "<div class='error'>This email ID doesn't exist. Please signup first.</div>";
    }
}
?>


<html lang="en">
    <?php InsertCommonHeader() ?>
    <body>
        <?php InsertNavbar(false); ?>

        <form id="login" method="post">
            <!--Empty header below to headline the body-->
            <div class="bs-docs-header"><div class="container"><h3>
            Please log in   
            </h3></div></div>
            
            <div id="content" class="container"> 
                <div class="container col-sm-4">  
                    <h2>Login</h2>
                    <form>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input name="email" id="email" type="email" class="form-control" 
                                   placeholder="Enter email" value="<?php echo $email; ?>" required="">
                        </div>
                        <div class="form-group">
                            <label for="pass">Password</label>
                            <input name="pass" type="password" class="form-control" id="pass" placeholder="Password">
                        </div>
                        <div class="form-group">

                            <button name="login" type="submit" class="btn btn-success btn-lg">Sign In</button> <text>or</text>
                        </div>
                        <div class="form-group">
                            <a class="btn btn-default" href="signup.php" role="button">SignUp</a>
                            <a class="btn btn-info" href="forgotpassword.php" role="button">Forgot Password?</a>
                        </div>
                    </form>
                </div>
                
                <?php
                if (!empty($errStr)) {
                    echo($errStr);
                }
                ?>

                <?php
                if (isset($_SESSION['message'])) {
                    echo "<div class='error'>" . $_SESSION['message'] . "</div>";
                    $text = "This account has not been activated. Please activate this account before trying to log in."
                            . "If you can't find the email to activate your account, please hit resend email below.<br />";
                    if ($_SESSION['message'] == $text) {
                        echo "<div class='error'><br /><input type='submit' value='Email Activation Link' name='submit'/></div>";
                    }
                }
                ?>
            </div>

        </form>
        <?php InsertFooter(300) ?>    
    </body>
<?php InsertCommonJS() ?>
</html>
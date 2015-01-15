<?php 
// html code for signing in on header of each page
if (isset($_POST['login'])) {
    $email = mysql_prep(filter_input(INPUT_POST, 'email'));
    $user = find_user_by_email($email);
    $_SESSION['unlogged_user'] = $email;
    if ($user) {
        if ($user['active'] == 0) {
            $_SESSION['message'] = "This account has not been activated. Please activate this account before trying to log in."
                    . "If you can't find the email to activate your account, please hit resend email below.<br />";
            redirect_to("login.php");
        } else {

            $pass = mysql_prep(filter_input(INPUT_POST, 'pass'));
            $hashed_password = password_encrypt($pass);
            $found_user = attempt_login($email, $pass);
            if ($found_user) {
                $_SESSION['user_email'] = $email;
                $qs = http_build_query($_GET);
                if ($qs) {
                    redirect_to($_SERVER['PHP_SELF'] . "?" . $qs); //refresh current page to reflect logged in status
                } else {
                    redirect_to($_SERVER['PHP_SELF']);
                }
            } else {
                $_SESSION['message'] = "Login failed. Please try entering your password again.";
                redirect_to("login.php");
            }
        }
    } else {
        $_SESSION['message'] = "This email ID doesn't exist. Please signup first.";
        redirect_to("login.php");
    }
}
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    $qs = http_build_query($_GET);
    if ($qs) {
        redirect_to($_SERVER['PHP_SELF'] . "?" . $qs); //refresh current page to reflect logged out status
    } else {
        redirect_to($_SERVER['PHP_SELF']);
    }
}

print("<div class='signin'>");
print("<form id='signin' method='post' class='navbar-form navbar-right' role='form'>");
if (!isset($_SESSION['user_email'])) {
// // Below is a code which displays a right menubar, and keeps navbar thin            
    print ("<div class='form-group'>
                          <input class='form-control' type='text' placeholder='Email' name='email' >
                          <input class='form-control' type='password' placeholder='Password' name='pass' >
                          <button type='submit' class='btn btn-success' name='login'>SignIn</button>
                          </div>
                          <div class='form-group'>
                          <a href='signup.php' class='btn btn-default' role='button'>SignUp</a>
                          </div>
                          <div class='btn-group'>
                          <button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                            Help <span class='caret'></span>
                          </button>
                          <ul class='dropdown-menu' role='menu'>
                            <li><a href='forgotpassword.php'>Forgot password?</a></li>
                            <li class='divider'></li>
                            <li><a href='faq.php'>FAQ</a></li>
                          </ul>
                        </div>");
}else{
    print("<div class='form-group'>
                   <a href='#'>" . $_SESSION['user_email'] . " </a>
                   <button type='submit' class='btn btn-danger' name='logout'>Logout</button>
                 </div>
                 <div class='btn-group'>
                   <button type='button' class='btn btn-info dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                     Help <span class='caret'></span>
                   </button>
                   <ul class='dropdown-menu' role='menu'>
                     <li><a href='faq.php'>FAQ</a></li>
                   </ul>
                </div>");
}
print("</form>");
print("</div>");
?>
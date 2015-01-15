<?php
    function notify_admin_about_new_data($msg,$id){	
        global $rti_email;
        global $rti_pass;
	$mail = new PHPMailer;
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->Port = 465; 
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $rti_email;                 // SMTP username
	$mail->Password = $rti_pass;                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
	$mail->isHTML(true);
	$mail->From = $rti_email;
	$mail->FromName = 'RTIShare.org';		
	$mail->addAddress($rti_email);     // Add a recipient
	$mail->Subject = 'Update information';
	$mail->Body    = 'New '.$msg.' was added for RTI ID '.$id.'. Please update relevant tables.';		
	$mail->SMTPDebug = 1;
	if(!$mail->send()) {
            return false;
	} 
        else {
            return true;
        }
    }
?>

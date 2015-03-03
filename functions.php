<?php
    require("phpmailer/class.phpmailer.php");
    $rti_email = "youremailID";
    $rti_pass= "yourpassword";
    
    function redirect_to($new_location){
        header("Location: ".$new_location);
        exit;    
    }
    
    function mysql_prep($string){
        global $connection; 
        $escaped_string = mysqli_real_escape_string($connection,$string);
        $escaped_string = htmlspecialchars($escaped_string);
        return $escaped_string;
    }
    function confirm_query($result_set){
	   if(!$result_set)
	       die("Query failed");
    }
    
    function find_all_cities(){
        global $connection;
       	$query = "SELECT * FROM cities";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    function find_cities_by_state($state){
        global $connection;
        $state = mysqli_real_escape_string($connection,$state);        
        $query = "SELECT * FROM cities WHERE UPPER(CityState)=UPPER('{$state}')";
        $cities_set = mysqli_query($connection,$query);
        confirm_query($cities_set);
        return $cities_set;
        
        
    }
    
    function find_all_states(){
        global $connection;
       	$query = "SELECT * FROM states ORDER BY StateName";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    function find_all_central_ministries(){
        global $connection;
       	$query = "SELECT * FROM central_ministries";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    function find_all_state_ministries(){
        global $connection;
       	$query = "SELECT * FROM state_ministries";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    function find_all_cities_by_name($term,$state){
        global $connection;        
        $term = mysql_prep($term);
        $state = mysql_prep($state);
       	//$query = "SELECT CityName FROM cities WHERE CityName LIKE '%".$term."%' ";
        $query = "SELECT CityName FROM cities WHERE CityState='{$state}' AND CityName LIKE '%".$term."%' ";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    function find_all_central_ministries_by_name($term){
        global $connection;      
        $term = mysql_prep($term);
       	$query = "SELECT MinistryName FROM central_ministries WHERE MinistryName LIKE '%".$term."%' ";        
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    function find_all_state_ministries_by_name($term){
        global $connection;      
        $term = mysql_prep($term);        
       	$query = "SELECT MinistryName FROM state_ministries WHERE MinistryName LIKE '%".$term."%' ";        
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    function find_all_tags_by_name($term){
        global $connection;      
        $term = mysql_prep($term);        
       	$query = "SELECT DISTINCT TagName FROM rtitags WHERE TagName LIKE '%".$term."%' AND isParent=0";        
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    function find_rtis_by_tags_only($tags){
        global $connection;          
        //to be able to display relevance value from query below replace DISTINCT ID with * 
        // The results will not remain unique though and this should be done only during testing
        // Dont ever move code with * to production.
        
        $query = "SELECT DISTINCT ID FROM "
                . "(SELECT rti_application.ID,rti_application.Title,SUM(rtitags.relevance*4) FROM rti_application, rtitags, search "
                . "WHERE rti_application.ID=rtitags.rtiid AND rtitags.tagname LIKE CONCAT('%', search.word, '%')GROUP BY RTIID "
                . "UNION "
                . "SELECT rti_application.ID,rti_application.Title,MATCH(Title) AGAINST('{$tags}' WITH QUERY EXPANSION) "
                . "AS RELEVANCE FROM rti_application WHERE MATCH(Title) AGAINST('{$tags}' WITH QUERY EXPANSION) "
                . "UNION "
                . "SELECT rti_application.ID,rti_application.Title,MATCH(Summary) AGAINST('{$tags}' WITH QUERY EXPANSION)*0.5 "
                . "AS RELEVANCE FROM rti_application WHERE MATCH(Summary) AGAINST('{$tags}' WITH QUERY EXPANSION)*0.5 "                
                . "HAVING RELEVANCE > 0.5 ORDER BY 3 DESC) rti_application";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    function find_rti_by_id($id){
        global $connection;
        $id = mysqli_real_escape_string($connection,$id);        
        $query = "SELECT * FROM rti_application WHERE ID='{$id}' LIMIT 1";
        $rti_set = mysqli_query($connection,$query);
        confirm_query($rti_set);
        if($rti = mysqli_fetch_assoc($rti_set)){
            return $rti;
        }
        else{
            return null; 
        }        
        
    }
    
    function find_rtis_by_center($dept,$period){
        global $connection;      
        $dept = mysql_prep($dept);        
        $period = mysql_prep($period);  
        if($dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='Center' AND Period Like '%".$period."%'";        
        }
        else if($period===""){
            $query = "SELECT * FROM rti_application WHERE Center_OR_State='Center' AND Department='{$dept}'";        
        }
        else{
            $query = "SELECT * FROM rti_application WHERE Center_OR_State='Center' AND Department='{$dept}'"
            . "AND Period Like '%".$period."%'";        
        }
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
    }
    //too much code in function below. Need to simplify it.
    function find_rtis_by_state($state,$city,$dept,$period){
        global $connection;      
        $state = mysql_prep($state);        
        $city = mysql_prep($city);         
        $dept = mysql_prep($dept);    
        $period = mysql_prep($period);  
        //various cases for searching when either city or dept or both are left blank
        if($period===""){        
            if($city=="" && $dept!=""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND Department='{$dept}'";        
            }
            else if($city=="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}'";        
            }
            else if($city!="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}'";        
            }
            else{
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}' AND Department='{$dept}'";        
        
            }
        }
        else{
            if($city=="" && $dept=="" && $state=="-1"){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND Period Like '%".$period."%'";        
            }
            else if($city=="" && $dept!=""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND Department='{$dept}' AND Period Like '%".$period."%'";        
            }
            else if($city=="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND Period Like '%".$period."%'";        
            }
            else if($city!="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}' AND Period Like '%".$period."%'";        
            }
            else{
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}' AND Department='{$dept}' AND Period Like '%".$period."%'";        
        
            }
        }
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
    }
    
    function find_rtitags_by_id($id){
        global $connection;
        $id = mysqli_real_escape_string($connection,$id);        
        $query = "SELECT * FROM rtitags WHERE RTIID='{$id}'";
        $rtitags = mysqli_query($connection,$query);
        confirm_query($rtitags);
        return $rtitags;    
    }
    
    function updatepageviews($id){
        global $connection;            
        $query = "UPDATE rti_application SET PageViewCount = PageViewCount + 1 WHERE ID = '{$id}' LIMIT 1 ";
        $result = mysqli_query($connection,$query);
        confirm_query($result);   
        return $result;
    }
        
    function find_all_benefitsmost($term){
        global $connection;      
        $term = mysql_prep($term);
       	$query = "SELECT DISTINCT WhoBenefitsMost FROM rti_application WHERE WhoBenefitsMost LIKE '%".$term."%' ";        
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    //Functions below for signing up and logging related stuff
    
    function password_encrypt($password){
        
        $hash_format = "$2y$10$";
        $salt_length = 22;//blowfish salts should be 22 or more
        $salt = generate_salt($salt_length);
        $format_and_salt = $hash_format.$salt;
        $hash = crypt($password,$format_and_salt);
        return $hash;           
            
    }
    
    function generate_salt($length){
        $unique_random_string = md5(uniqid(mt_rand(),true));//md5 returns 32 characters
        $base64_string = base64_encode($unique_random_string);//valid charatcers for salt are a-zA-Z0-9.
        $modified_base64_string = str_replace('+','.',$base64_string);//but not + which is valid in base64
        $salt = substr($modified_base64_string,0,$length);//truncate string to correct length
        return $salt;
    }
    
    function password_check($password,$existing_hash){
        $hash = crypt($password,$existing_hash);
        if ($hash === $existing_hash){
            return true;            
        }
        else{
            return false;
        }
    }
    
    function attempt_login($email,$password){
        $user = find_user_by_email($email);
        if($user){
            if (password_check($password,$user["Password"])){           
                return $user;                
            }            
            else{
                return false;
            }
            
        }   
        else{
            return false;
        }  
        
    }
    function find_user_by_email($email){
        global $connection;
        $safe_email= mysql_prep($email);
        $query = "SELECT * FROM users WHERE email='{$safe_email}' LIMIT 1";
        $user_set = mysqli_query($connection,$query);
        confirm_query($user_set);
        if($user = mysqli_fetch_assoc($user_set)){
            return $user;
        }
        else
            return null;
       
    }
    //Functions to send email below
    function sendemail($email,$random_hash){	
        global $rti_email;
        global $rti_pass;
	$link = 'http://www.rtishare.org/verifyemail.php?id='.urlencode($random_hash);
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
	$mail->FromName = 'RTIshare.org';		
	$mail->addAddress($email);     // Add a recipient
	$mail->Subject = 'Welcome to RTIshare';
	$mail->Body    = 'Welcome to RTIshare. To activate your account please click on the link below <br />'.$link;		
	$mail->SMTPDebug = 1;
	if(!$mail->send()) {
            return false;
	} 
        else {
            return true;
        }
    }    
    function encrypt_decrypt($action, $string) {
	$output = false;
	$key = 'putyourkeyhere';
	// initialization vector 
	$iv = md5(md5($key));
        if( $action == 'encrypt' ) {
            $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
            $output = base64_encode($output);
	}
	else if( $action == 'decrypt' ){
            $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
            $output = rtrim($output, "");
	}
	return $output;
    }
    
    function activate_user($email){
        global $connection;
        $query = "UPDATE users SET active = 1 WHERE Email = '{$email}' LIMIT 1 ";
	$result = mysqli_query($connection,$query);
        if($result){ return TRUE;}
        else{return NULL;};
	   
        
    }
    //Sign up 
    function verify_signup($email,$pass,$pass2){
        global $connection;
        $hash = mt_rand(); //generate random number
        $random_hash = encrypt_decrypt('encrypt', $hash);
        $random_hash = mysql_prep($random_hash);                                        
        $_SESSION['verifyemail'] = $email;                    
        $signuperror= 0 ;        
        $html = "<div class='error'>";
        if($email==""){ $signuperror = 1;}
        if($pass==""){ $signuperror = 1;}
        if($pass2==""){ $signuperror = 1;}
        if($signuperror!=1){
            if ($pass===$pass2){                        
                $encrypted_pass = password_encrypt($pass);
                $query = "INSERT INTO users (Email,Password,random_hash,active) VALUES "
                       . "('{$email}','{$encrypted_pass}','{$random_hash}',0)";
                $result = mysqli_query($connection,$query);                
                if($result){	
                    if(sendemail($email, $random_hash)){
                        redirect_to("signupsuccess.php");
                    }
                    else{
                        $html.= "Sorry, something just didn't work. Did you enter a valid email address? Please try again.";
                    }
                                
                }
                else{
                    $html.= "Problem with signing up. Please try again.";
                }
            }
            else{
                $html.= "Passwords do not match. Please enter them again.";
            }
        }
        else{
            $html.= "Sign Up error! Please try again.";
        }
        $html.= "</div>";
        return $html;
    }
    
    function verify_signup_again($email){
        global $connection;
        $hash = mt_rand(); //generate random number
        $random_hash = encrypt_decrypt('encrypt', $hash);
        $random_hash = mysql_prep($random_hash);                                        
        $_SESSION['verifyemail'] = $email;                    
        $signuperror= 0 ;
        echo "<div class='error'>";
        if($email==""){ $signuperror = 1;}
        if($signuperror!=1){                            
                $query = "UPDATE users SET random_hash = '{$random_hash}' WHERE Email = '{$email}' LIMIT 1 ";
                $result = mysqli_query($connection,$query);                
                if($result && mysqli_affected_rows($connection)==1){	
                    if(sendemail($email, $random_hash)){
                        redirect_to("signupsuccess.php");
                    }
                    else{
                        echo "Sorry, something just didn't work. Did you enter a valid email address? Please try again.";
                    }
                                
                }
                else{
                    echo "Problem with signing up. Please try again.";
                }                        
        }
        else{
            echo "Sign Up error! Please try again.";
        }
        echo "</div>";
    }
    //functions for restting password
    function send_temp_pass($email){
        global $rti_email;
        global $rti_pass;
	$temppass = randomPassword();
        $encrypted_pass = password_encrypt($temppass);
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
	$mail->addAddress($email);     // Add a recipient
	$mail->Subject = 'Reset Password';
	$mail->Body    = 'Your temporary password is: <br />'.$temppass."<br /><br />Please use this to login to your account."
                        . " You must change your password at the earliest after you login.";		
	$mail->SMTPDebug = 1;
	if(!$mail->send()) {
            return false;
	} 
        else {
            if(set_temppass($email, $encrypted_pass)){//change temporary password in DB
                return true;
            }
            else{
                return false;
            }
        }
    }
    
    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
    function set_temppass($email,$temppass){
        global $connection;
        $query = "UPDATE users SET Password = '{$temppass}', temppass_flag = 1 WHERE Email = '{$email}' LIMIT 1 ";
        $result = mysqli_query($connection,$query);                
        if($result && mysqli_affected_rows($connection)==1){	
            return true;
        }
                
    }
    
    function is_state($state){
        global $connection;
        $state = mysqli_real_escape_string($connection,$state);        
        $query = "SELECT * FROM states WHERE UPPER(StateName)=UPPER('{$state}')";
        $result = mysqli_query($connection,$query);
        if($result && mysqli_affected_rows($connection)==1){	
            return true;
        }
        else{
            return false;
        }
    }
    
    function find_popular_rtis(){
        global $connection;
       	$query = "SELECT * FROM rti_application ORDER BY PageViewCount DESC LIMIT 5";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    function find_average_response_time($state){
        global $connection;
       	$query = "SELECT  AVG(DATEDIFF(ResponseDate,FilingDate)) FROM rti_application WHERE State='{$state}' GROUP BY State LIMIT 1";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        if($avg = mysqli_fetch_assoc($result)){
            return $avg['AVG(DATEDIFF(ResponseDate,FilingDate))'];
        }
        else{
            return null;
        }        
    }
    
    function find_percent_users_satisfied($state){
        global $connection;
       	$query = "SELECT (SUM(SATISFIED)/COUNT(ID))*100 FROM rti_application WHERE State='{$state}' GROUP BY STATE LIMIT 1";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        if($row = mysqli_fetch_assoc($result)){
            return $row['(SUM(SATISFIED)/COUNT(ID))*100'];
        }
        else{
            return null;
        }        
    }
    
    function show_state_info(){
        $state = filter_input(INPUT_GET, 'state');
        $average_response_time =find_average_response_time($state);                    
        if($average_response_time>0){
            echo "<p>Average Response time is <i>".  round($average_response_time)." </i>days. <br />";
        }
        $percent_satisfied_users = find_percent_users_satisfied($state);
        if($percent_satisfied_users){
            echo "<i>".round($percent_satisfied_users)."</i>% users are satisfied with the first response to their RTIs. <br />";
        }
        $sic_website = get_sic_website($state);
        if($sic_website){
            echo "State Information Commision: <a href='$sic_website' target='_blank'>".$sic_website."</a></p>";
        }
    }    
    
    function get_sic_website($state){
        global $connection;
       	$query = "SELECT * FROM sic_websites WHERE StateName='{$state}' LIMIT 1";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        if($row = mysqli_fetch_assoc($result)){
            return $row['Website'];
        }
        else{
            return null;
        }        
    }
    
    function find_popular_rtis_for_state($state){
        global $connection;
       	//$query = "SELECT * FROM rti_application WHERE State='{$state}' ORDER BY PageViewCount DESC LIMIT 5";
        $query="SELECT * FROM rti_application WHERE ID IN (SELECT rtitags.RTIID FROM rtitags "
                . "JOIN (SELECT * FROM rtitags WHERE rtitags.TagName='{$state}') x on x.RTIID = rtitags.RTIID "
                . "AND rtitags.TagName!='{$state}') ORDER BY PageViewCount DESC LIMIT 5";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;
    }
    
    function find_total_rtis_for_state($state){
        global $connection;
       	//$query = "SELECT COUNT(ID) FROM rti_application WHERE State='{$state}' ORDER BY State LIMIT 1";
        $query = "SELECT COUNT(RTIID) FROM rtitags WHERE TagName='{$state}' LIMIT 1";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        if($row = mysqli_fetch_assoc($result)){
            //return $row['COUNT(ID)'];
            return $row['COUNT(RTIID)'];
        }
        else{
            return null;
        }
    }
    
    function find_rtis_by_center_with_paging($dept,$period,$start,$records_per_page){
        global $connection;      
        $dept = mysql_prep($dept);        
        $period = mysql_prep($period);  
        if($dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='Center' AND Period Like '%".$period."%'";        
        }
        else if($period===""){
            $query = "SELECT * FROM rti_application WHERE Center_OR_State='Center' AND Department='{$dept}'";        
        }
        else{
            $query = "SELECT * FROM rti_application WHERE Center_OR_State='Center' AND Department='{$dept}'"
            . "AND Period Like '%".$period."%'";        
        }
        $query.= " LIMIT $start, $records_per_page";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
    }
    
    //too much code in function below. Need to simplify it.
    function find_rtis_by_state_with_paging($state,$city,$dept,$period,$start,$records_per_page){
        global $connection;      
        $state = mysql_prep($state);        
        $city = mysql_prep($city);         
        $dept = mysql_prep($dept);    
        $period = mysql_prep($period);  
        //various cases for searching when either city or dept or both are left blank
        if($period===""){        
            if($city=="" && $dept!=""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND Department='{$dept}'";        
            }
            else if($city=="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}'";        
            }
            else if($city!="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}'";        
            }
            else{
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}' AND Department='{$dept}'";        
        
            }
        }
        else{
            if($city=="" && $dept=="" && $state=="-1"){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND Period Like '%".$period."%'";        
            }
            else if($city=="" && $dept!=""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND Department='{$dept}' AND Period Like '%".$period."%'";        
            }
            else if($city=="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND Period Like '%".$period."%'";        
            }
            else if($city!="" && $dept==""){
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}' AND Period Like '%".$period."%'";        
            }
            else{
                $query = "SELECT * FROM rti_application WHERE Center_OR_State='State' AND "
                . "State='{$state}' AND City='{$city}' AND Department='{$dept}' AND Period Like '%".$period."%'";        
        
            }
        }
        $query.= " LIMIT $start, $records_per_page";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
    }
    
    function find_rtis_by_tags_only_with_paging($tags,$start,$records_per_page){
        global $connection;          
        $query = "SELECT DISTINCT ID, Title, Summary,FilingDate FROM "
                . "(SELECT rti_application.ID,rti_application.Title,rti_application.Summary, rti_application.FilingDate, SUM(rtitags.relevance*4) FROM rti_application, rtitags, search "
                . "WHERE rti_application.ID=rtitags.rtiid AND rtitags.tagname LIKE CONCAT('%', search.word, '%')GROUP BY RTIID "
                . "UNION "
                . "SELECT rti_application.ID,rti_application.Title,rti_application.Summary,rti_application.FilingDate,MATCH(Title) AGAINST('{$tags}' WITH QUERY EXPANSION) "
                . "AS RELEVANCE FROM rti_application WHERE MATCH(Title) AGAINST('{$tags}' WITH QUERY EXPANSION) "
                . "UNION "
                . "SELECT rti_application.ID,rti_application.Title,rti_application.Summary,rti_application.FilingDate,MATCH(Summary) AGAINST('{$tags}' WITH QUERY EXPANSION)*0.5 "
                . "AS RELEVANCE FROM rti_application WHERE MATCH(Summary) AGAINST('{$tags}' WITH QUERY EXPANSION)*0.5 "                
                . "HAVING RELEVANCE > 0.5 ORDER BY 5 DESC) rti_application LIMIT $start,$records_per_page";
        $result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
?>
<?php




function has_presence($value) {
	return isset($value) && $value !== "";
}
// * string length
// max length
function has_max_length($value, $max) {
	return strlen($value) <= $max;
}

function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}

function validateEMAIL($EMAIL) {
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
    return preg_match($regex, $EMAIL);
}
function validatePINCODE($PINCODE) {
    $regex = '#[0-9]{6}#'; 
    return preg_match($regex, $PINCODE);
}

function doValidations_forCenterUpload($centralministry,$title,$summary,$filingdate,$responsedate){
        
    return has_presence($centralministry)&& has_presence($title) && has_presence($summary) && 
           has_presence($filingdate) && has_presence($responsedate);        
}
function doValidations_forStateUpload($state,$cities,$stateministry,$pincode,$title,$summary,$filingdate,$responsedate){
    return has_presence($state) && has_presence($cities)&& has_presence($stateministry)&& 
           has_presence($pincode) && validatePINCODE($pincode)&&
           has_presence($title) && has_presence($summary) && has_presence($filingdate) && has_presence($responsedate);            
}

?>
<?php
$tagname_counter = 1; //use this in heirarchical tag cloud to generate parameter names like tag1, tag2, tag3 etc. in URL
$max_count_for_hierarchy = 5 ;//decides how big a tag whose isParent is 1 should be, in order to display a hierachical tag cloud under it.
$max_count_for_state = 5; //decides how many tags a state must contain under it, in order to display a hierachical tag cloud under it.
function top_tags_front_page(){    
        global $connection;
       	$query = "SELECT TagName, COUNT(TagName) FROM rtitags WHERE isPArent=0 "
                . "GROUP BY TagName ORDER BY COUNT(TagName) DESC LIMIT 30";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
}

function find_rtis_by_tagname($tag){    
        global $connection;
        $tag = mysql_prep($tag);
       	$query = "SELECT * FROM rti_application WHERE ID IN (SELECT RTIID FROM rtitags WHERE TagName='{$tag}')";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
}

function show_tag_cloud($tags_for_cloud){
    $maximum = 0; // $maximum is the highest counter for a search term
    while ($row = mysqli_fetch_array($tags_for_cloud))
    {
        $term = $row['TagName'];
        $counter = $row['COUNT(TagName)'];                    
        $terms[] = array('term' => $term, 'counter' => $counter);
        if ($counter > $maximum) {
            $maximum = $counter;                                    
        } 
    }
    shuffle($terms);
    // start looping through the tags
    foreach ($terms as $term){
        $percent = floor(($term['counter'] / $maximum) * 100); // determine the popularity of this term as a percentage                            
        if ($percent < 20): // determine the class for this term based on the percentage
            $class = 'smallest'; 
        elseif ($percent >= 20 and $percent < 30):
            $class = 'small'; 
        elseif ($percent >= 30 and $percent < 50):
            $class = 'medium';
        elseif ($percent >= 50 and $percent < 80):
            $class = 'large';
        else:
            $class = 'largest';
        endif;                        
        echo "<span class='".$class."'>";
        echo "<a href='searchresults_tags.php?tag=".$term['term']."'>".$term['term']."</a>";
        echo "</span>";
    }           
    
}

function top_tags_for_state($state,$get_array){    
        global $connection;
        $state = mysql_prep($state);        
        //use $get_array to add more joins to the same query as many number of tags
        $alias_table_count =0; //to create different alias tables like x, x1, x2 etc. in the join query
       	$query = "SELECT rtitags.TagName, COUNT(rtitags.TagName),rtitags.isParent FROM rtitags "
                . " JOIN (SELECT * FROM rtitags WHERE rtitags.TagName='{$state}') x on x.RTIID = rtitags.RTIID AND rtitags.TagName!='{$state}' ";
        foreach($get_array as $term) {
            $alias_table_count++;
            $term = mysql_prep($term);
            $query = $query . " JOIN (SELECT * FROM rtitags WHERE rtitags.TagName='{$term}') x{$alias_table_count} on x{$alias_table_count}.RTIID = rtitags.RTIID AND rtitags.TagName!='{$term}' ";
        }                
        $query = $query . " GROUP BY TagName ORDER BY COUNT(rtitags.TagName) DESC LIMIT 30";        
    	$result = mysqli_query($connection,$query);        
        confirm_query($result);
        $rowcount=mysqli_num_rows($result);
        if($rowcount>0){
            return $result; 
        }
        else{
            return false;
        }
        
}

function show_tag_cloud_for_state($state,$get_array){
    $html = "";
    $link ="";
    global $tagname_counter;
    global  $max_count_for_hierarchy;
    global  $max_count_for_state;
    $check_state_max_count_flag = 0; //used to check if a state contains sufficient no. of tags to display a tag cloud.
    if($get_array){//if there are more tags other than state selected i.e hierarchical cloud is to be shown
        $lastkey = end(array_keys($get_array));                
        //get top tags based on state + tags in get_array.
        $tags_for_cloud = top_tags_for_state($state,$get_array);        
        foreach ($get_array as $tagname => $tagvalue){                  
                //create a link to redirect to based on state + tags from get_array                
                $tagname_counter++;
                $link = $link . "&".$tagname."=".$tagvalue;                
        }        
    }
    elseif(!$get_array){    //if no other tags except state i.e. non hierarchical tag cloud
        $tags_for_cloud = top_tags_for_state($state,$get_array); //here get_array is empty
        if($tags_for_cloud){
            if(mysqli_num_rows($tags_for_cloud) < $max_count_for_state){ //this must be done only when there are no other tags except state
                $check_state_max_count_flag = 1;
                }
        }
    }
    //start displaying tag cloud
    $maximum = 0; // $maximum is the highest counter for a search term    
    if($tags_for_cloud){
        if($check_state_max_count_flag===0){
            while ($row = mysqli_fetch_array($tags_for_cloud))
            {
                $term = $row['TagName'];
                $counter = $row['COUNT(rtitags.TagName)'];                    
                $isparent = $row['isParent']; 
                $terms[] = array('term' => $term, 'counter' => $counter, 'isParent'=> $isparent);
                if ($counter > $maximum) {
                    $maximum = $counter;                                    
                } 
            }    
            shuffle($terms);
            // start looping through the tags
            foreach ($terms as $term){
                $percent = floor(($term['counter'] / $maximum) * 100); // determine the popularity of this term as a percentage                            
                if ($percent < 20): // determine the class for this term based on the percentage
                    $class = 'smallest'; 
                elseif ($percent >= 20 and $percent < 30):
                    $class = 'small'; 
                elseif ($percent >= 30 and $percent < 50):
                    $class = 'medium';
                elseif ($percent >= 50 and $percent < 80):
                    $class = 'large';
                else:
                    $class = 'largest';
                endif;              
                //use $link to display links below. If link is empty it will still work.
                //if a tag is heirarchical tag and it has a lot of tags (> 5 for now)then refresh summarystate.php page to show new tag cloud.
                if($term['isParent']==1 && $term['counter']>$max_count_for_hierarchy){ 
                    $html .= ("<span class='".$class."'>");
                    $html .= ("<a href='summarystate.php?state=".$state.$link."&tag".$tagname_counter."=".$term['term']."'>".$term['term']."</a>");
                    $html .= "</span>";
                }
                else{ //if a tag has not a hierarhical tag, redirect to results page.
                    $html .= ("<span class='".$class."'>");
                    $html .= ("<a href='searchresults_tags_states.php?state=".$state.$link."&tag=".$term['term']."'>".$term['term']."</a>");
                    $html .= "</span>";
                }            
            }
        }
        else{
            redirect_to("searchresults_tags_states.php?state=".$state);
        }
    }
    else{
        $html.= "No RTIs found for this selection.";
    }
    return ($html);
}

function find_rtis_by_tagname_and_state($state,$get_array){    
        global $connection;
        $state = mysql_prep($state);
        $alias_table_count =0; //to create different alias tables like x, x1, x2 etc. in the join query
       	$query = "SELECT * FROM rti_application WHERE ID IN (SELECT rtitags.RTIID FROM rtitags "
                . " JOIN (SELECT * FROM rtitags WHERE rtitags.TagName='{$state}') x on x.RTIID = rtitags.RTIID AND rtitags.TagName!='{$state}'";
                foreach($get_array as $term){
                    $alias_table_count++;
                    $term = mysql_prep($term);
                    $query = $query." JOIN (SELECT * FROM rtitags WHERE rtitags.TagName='{$term}') x{$alias_table_count} on x{$alias_table_count}.RTIID = rtitags.RTIID";
                }
                    
        $query = $query. ")";         
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
}

function taglink($get_array_initial,$upto_tag_key){
    $i=0;
    $cnt =0;
    $link="summarystate.php?";    
    foreach ($get_array_initial as $key=>$value){
        $cnt =$i;
        $temp_array = [];
        while($i<=$cnt){
            $temp_array[$key] = $value;
            foreach($temp_array as $tagkey=>$tagvalue){
                $link = $link.$tagkey."=".$tagvalue;                            
                if($tagkey!=$upto_tag_key){                    
                    $link = $link."&";
                }
            }               
            $i++;        
        }    
        if($key==$upto_tag_key){
            return $link;
        }
    }
}

function find_rtis_by_tagname_with_paging($tag,$start,$records_per_page){    
        global $connection;
       	$query = "SELECT * FROM rti_application WHERE ID IN (SELECT RTIID FROM rtitags WHERE TagName='{$tag}')"
                . "LIMIT $start, $records_per_page";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
}

function find_rtis_by_tagname_and_state_with_paging($state,$get_array,$start,$records_per_page){    
        global $connection;
        $state = mysql_prep($state);
        $alias_table_count =0; //to create different alias tables like x, x1, x2 etc. in the join query
       	$query = "SELECT * FROM rti_application WHERE ID IN (SELECT rtitags.RTIID FROM rtitags "
                . " JOIN (SELECT * FROM rtitags WHERE rtitags.TagName='{$state}') x on x.RTIID = rtitags.RTIID AND rtitags.TagName!='{$state}'";
                foreach($get_array as $term){
                    $alias_table_count++;
                    $term = mysql_prep($term);
                    $query = $query." JOIN (SELECT * FROM rtitags WHERE rtitags.TagName='{$term}') x{$alias_table_count} on x{$alias_table_count}.RTIID = rtitags.RTIID";
                }
                    
        $query = $query. ") LIMIT $start, $records_per_page";         
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;    
}

?>
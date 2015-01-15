<?php 
    require_once("dbconnection.php");   
    include("functions.php"); 
?>
<MAP name="map1">
    <area shape="POLY" id="0" href="SummaryState.php?state=Delhi" title="<?php echo "Delhi(".find_total_rtis_for_state("Delhi").")";?>" coords="119,119,127,119,126,127,118,126" alt=""/>
    <area shape="POLY" id="1" href="SummaryState.php?state=haryana" title="<?php echo "Haryana(".find_total_rtis_for_state("Haryana").")";?>" coords="90,105,90,113,102,119,110,132,122,135,128,128,118,128,117,118,124,115,126,105,130,102,122,96,112,107,103,109" alt=""/>
    <area shape="POLY" id="2" href="SummaryState.php?state=Himachal Pradesh" title="<?php echo "Himachal Pradesh(".find_total_rtis_for_state("Himachal Pradesh").")"; ?>" coords="117,62,108,64,105,76,111,86,122,93,129,98,131,91,137,86,145,88,143,76,139,69,131,66" alt=""/>
    <area shape="POLY" id="3" href="SummaryState.php?state=Jammu and kashmir" title="<?php echo "Jammu and Kashmir(".find_total_rtis_for_state("Jammu and Kashmir").")"; ?>" coords="63,22,90,8,107,17,117,27,137,29,153,24,165,30,160,42,147,50,155,68,146,72,131,65,124,65,118,60,108,64,106,70,101,74,78,63,74,43,82,36" alt=""/>
    <area shape="POLY" id="4" href="SummaryState.php?state=Punjab" title=<?php echo "Punjab(".find_total_rtis_for_state("Punjab").")"; ?> coords="102,75,110,86,120,95,116,103,106,108,100,108,96,104,91,104,85,103,81,98,90,91,89,79" alt=""/>
    <area shape="POLY" id="5" href="SummaryState.php?state=Uttaranchal" title="<?php echo "Uttaranchal(".find_total_rtis_for_state("Uttaranchal").")"; ?>" coords="132,99,134,92,140,89,145,89,150,85,174,101,167,106,161,119,147,116,146,111,140,107,135,107" alt=""/>
    <area shape="POLY" id="6" href="SummaryState.php?state=Uttar Pradesh" title="<?php echo "Uttar Pradesh(".find_total_rtis_for_state("Uttar Pradesh").")"; ?>" coords="129,102,125,116,129,128,126,134,132,145,150,150,146,162,138,169,139,177,146,182,146,168,164,167,174,170,182,169,197,174,198,183,203,184,207,174,204,168,213,159,221,159,212,153,215,148,211,138,187,134,169,123,156,121,145,114,140,109,133,109" alt=""/>
    <area shape="POLY" id="7" href="SummaryState.php?state=Rajasthan" title="<?php echo "Rajasthan(".find_total_rtis_for_state("Rajasthan").")"; ?>" coords="80,102,74,105,51,130,43,134,36,131,25,146,32,149,32,157,40,161,46,175,57,174,72,181,77,188,88,197,93,187,94,171,103,172,108,176,104,185,111,181,120,181,121,173,126,170,121,168,115,163,117,158,137,147,129,147,130,143,125,135,118,135,109,133,100,119,89,114,87,105" alt=""/>
    <area shape="POLY" id="8" href="SummaryState.php?state=Bihar" title="<?php echo "Bihar(".find_total_rtis_for_state("Bihar").")"; ?>" coords="212,137,219,148,215,151,223,158,215,160,207,167,208,175,218,178,235,174,242,177,251,171,256,166,261,166,262,153,237,149" alt=""/>
    <area shape="RECT" id="9" href="SummaryState.php?state=Sikkim" title="<?php echo "Sikkim(".find_total_rtis_for_state("Sikkim").")"; ?>" coords="264,126,278,142" alt=""/>
    <area shape="POLY" id="10" href="SummaryState.php?state=West Bengal" title="<?php echo "West Bengal(".find_total_rtis_for_state("West Bengal").")"; ?>" coords="260,216,251,208,245,198,236,193,246,189,257,184,262,178,262,168,263,153,263,142,275,144,287,149,286,156,273,152,269,161,275,167,268,175,274,183,275,193,278,216,268,217" alt=""/>
    <area shape="POLY" id="11" href="SummaryState.php?state=Arunachal Pradesh" title="<?php echo "Arunachal Pradesh(".find_total_rtis_for_state("Arunachal Pradesh").")"; ?>" coords="311,135,333,123,349,113,358,115,369,112,374,119,379,127,388,129,385,139,370,140,360,145,359,140,368,135,364,129,346,133,340,142,320,143" alt=""/>
    <area shape="POLY" id="12" href="SummaryState.php?state=Assam" title="<?php echo "Assam(".find_total_rtis_for_state("Assam").")"; ?>" coords="289,157,289,147,294,144,319,145,340,144,347,134,363,131,364,136,346,149,334,162,331,176,325,179,321,174,327,165,319,156,306,157" alt=""/>
    <area shape="POLY" id="13" href="SummaryState.php?state=Nagaland" title="<?php echo "Nagaland(".find_total_rtis_for_state("Nagaland").")"; ?>" coords="361,149,358,143,346,151,336,161,336,167,343,164,352,164" alt=""/>
    <area shape="POLY" id="14" href="SummaryState.php?state=Manipur" title="<?php echo "Manipur(".find_total_rtis_for_state("Manipur").")"; ?>" coords="336,171,342,165,352,165,346,186,330,181" alt=""/>
    <area shape="POLY" id="15" href="SummaryState.php?state=Mizoram" title="<?php echo "Mizoram(".find_total_rtis_for_state("Mizoram").")"; ?>" coords="327,179,320,188,324,209,330,209,335,192,333,184" alt=""/>
    <area shape="POLY" id="16" href="SummaryState.php?state=Tripura" title="<?php echo "Tripura(".find_total_rtis_for_state("Tripura").")"; ?>" coords="319,175,308,182,305,188,309,197,315,199,318,188,323,181" alt=""/>
    <area shape="POLY" id="17" href="SummaryState.php?state=Meghalaya" title="<?php echo "Meghalaya(".find_total_rtis_for_state("Meghalaya").")"; ?>" coords="286,157,318,158,324,167,319,171,288,167" alt=""/>
    <area shape="POLY" id="18" href="SummaryState.php?state=Jharkhand" title="<?php echo "Jharkhand(".find_total_rtis_for_state("Jharkhand").")"; ?>"coords="208,175,207,183,219,195,215,203,226,204,229,209,238,209,237,204,249,207,236,193,238,188,259,180,259,168,256,168,250,177,242,179,234,176,219,180" alt=""/>
    <area shape="POLY" id="19" href="SummaryState.php?state=Madhya Pradesh" title="<?php echo "Madhya Pradesh(".find_total_rtis_for_state("Madhya Pradesh").")"; ?>" coords="136,148,148,151,146,161,138,167,136,176,145,184,147,169,164,170,181,171,196,176,196,184,190,186,182,186,182,190,188,194,182,204,178,204,171,218,162,216,153,215,141,218,132,218,131,214,122,215,115,223,110,219,90,214,86,210,86,204,92,197,95,188,96,173,102,174,108,176,104,185,112,184,121,184,123,176,129,169,122,166,117,164,119,159" alt=""/>
    <area shape="POLY" id="20" href="SummaryState.php?state=Gujarat" title="<?php echo "Gujarat(".find_total_rtis_for_state("Gujarat").")"; ?>" coords="44,176,56,176,74,186,88,199,83,204,82,213,83,218,79,222,79,229,75,229,71,236,66,233,62,207,58,222,38,229,15,206,33,203,24,200,6,190,6,184,14,183,18,179,26,181,39,180" alt=""/>
    <area shape="POLY" id="21" href="SummaryState.php?state=Chattisgarh" title="<?php echo "Chattisgarh(".find_total_rtis_for_state("Chattisgarh").")"; ?>" coords="183,187,201,185,206,181,208,186,216,196,214,205,209,207,206,220,197,221,194,224,197,238,189,236,193,251,183,264,179,265,179,261,169,255,170,249,177,244,171,239,173,231,170,223,179,206,184,206,190,193" alt=""/>
    <area shape="POLY" id="22" href="SummaryState.php?state=Orissa" title="<?php echo "Orrisa(".find_total_rtis_for_state("Orissa").")"; ?>" coords="259,216,248,209,240,207,236,211,225,211,225,205,216,206,211,209,207,223,198,222,196,226,199,238,192,239,194,251,187,262,194,259,200,259,207,247,213,251,221,250,243,238,252,230,251,221" alt=""/>
    <area shape="POLY" id="23" href="SummaryState.php?state=Maharashtra" title="<?php echo "Maharashtra(".find_total_rtis_for_state("Maharashtra").")"; ?>" coords="85,218,79,231,71,237,67,235,65,242,68,258,76,291,87,292,86,284,102,277,102,271,113,272,125,257,129,259,135,248,141,239,150,243,163,244,164,252,167,253,167,247,173,244,169,236,171,230,168,220,155,218,140,221,132,221,129,217,123,217,115,225,109,221,90,215" alt=""/>
    <area shape="RECT" id="24" href="SummaryState.php?state=Goa" title="<?php echo "Goa(".find_total_rtis_for_state("Goa").")"; ?>" coords="72,294,88,305" alt=""/>
    <area shape="POLY" id="25" href="SummaryState.php?state=Telangana" title="<?php echo "Telangana(".find_total_rtis_for_state("Telangana").")"; ?>" coords="142,240,130,260,127,284,129,291,135,292,148,288,155,282,161,278,172,275,182,270,182,266,176,266,176,260,162,254,162,245" alt=""/>
    <area shape="POLY" id="26" href="SummaryState.php?state=Andhra Pradesh" title="<?php echo "Andhra Pradesh(".find_total_rtis_for_state("Andhra Pradesh").")"; ?>" coords="207,251,200,261,192,260,185,267,182,272,164,279,153,285,147,292,133,293,123,293,120,305,121,314,126,320,133,318,141,325,140,335,148,329,163,325,163,299,173,293,178,286,190,283,192,274,218,253" alt=""/>
    <area shape="POLY" id="27" href="SummaryState.php?state=Karnataka" title="<?php echo "Karnataka(".find_total_rtis_for_state("Karnataka").")"; ?>" coords="125,260,113,275,104,274,104,278,97,279,89,286,88,304,86,310,93,333,101,337,113,346,120,347,130,345,129,337,134,331,137,333,138,328,134,322,122,322,119,313,117,305,121,292,130,292,124,284,129,262" alt=""/>
    <area shape="POLY" id="28" href="SummaryState.php?state=Kerela" title="<?php echo "Kerela(".find_total_rtis_for_state("Kerela").")"; ?>" coords="95,336,114,383,123,394,127,378,126,368,119,368,122,359,111,347" alt=""/>
    <area shape="POLY" id="29" href="SummaryState.php?state=Tamil Nadu" title="<?php echo "Tamil Nadu(".find_total_rtis_for_state("Tamil Nadu").")"; ?>" coords="163,326,154,329,141,336,133,335,132,347,117,349,121,357,124,363,128,366,128,378,125,392,130,396,135,394,139,382,153,384,149,374,157,368" alt=""/> 
    <area shape="POLY" id="30" href="SummaryState.php?state=Andaman and Nicobar ISLANDS" title="<?php echo "Andaman and Nicobar Islands(".find_total_rtis_for_state("Andaman and Nicobar Islands").")"; ?>" coords="326,323,316,355,322,386,342,417,337,388,331,358,351,327" alt=""/>
    <!-- DO NOT REMOVE THE LINE BELOW TO KEEP YOUR LICENSE -->
    <area shape="rect" coords="2,408,116,426" href="http://cmap.comersis.com" target="_blank" alt="clickable map of India">	</MAP>
<!-- Stop copy at the line above -->




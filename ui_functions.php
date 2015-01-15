<?php

// Function in include navigation bar in heading
function InsertNavbar() {
    $base_name = basename($_SERVER['REQUEST_URI']);
    $links = NavbarLink($base_name, 'index.php', 'Home');
    $links .= NavbarLink($base_name, 'upload.php', 'Upload');
    $links .= NavbarLink($base_name, 'aboutus.php', 'About');
    $links .= NavbarLink($base_name, 'contactus.php', 'Contact');

    print <<<EOT
    
    <nav class="navbar navbar-default" role="navigation">
    <!-- <nav class="navbar navbar-default navbar-fixed-top" role="navigation"> -->
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"> <p class="text-left text-primary" ><strong>
                        <big>rtishare <i class='fa fa-bullhorn'></i></big>
                            </strong></p> </a>
                </div>
                <div  id="navbar" class="navbar-collapse collapse ">
                    <ul class="nav navbar-nav">
$links
                    </ul>
            
EOT;

    include_once ('signinheader.php');

    print <<<EOT
                </div>
            </div>
    </nav>

EOT;
}

// Function to output a navbar link with currect href and active class.
function NavbarLink($page_basename, $url_basename, $label) {

    if (strcmp($page_basename, $url_basename) == 0) {
        return("<li class='active'><a href='#'>" . $label . "</a></li>");
    } else {
        return("<li><a href='" . $url_basename . "'>" . $label . "</a></li>");
    }
}

// Function to include common headers using nowdoc strings quoting (using <<<'str' operator)
function InsertCommonHeader() {
    echo <<<'EOT'
   
        <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="images/favicon.ico">

        <title> share your RTI</title>
        <!-- Bootstrap core CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <!--<script src="../../assets/js/ie-emulation-modes-warning.js"></script>-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="stylesheets/uploadfile.min.css">
        <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css"> -->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="stylesheets/tagcloud.css">       
        <link rel="stylesheet" href="stylesheets/jquery.qtip.css">
        <link rel="stylesheet" href="stylesheets/pwstrength.css">        
        <link rel='stylesheet' href='stylesheets/pagination.css' type='text/css'/>    
        <link rel="stylesheet" href="stylesheets/messenger.css">
        <link rel="stylesheet" href="stylesheets/messenger-theme-flat.css">

        <!-- Custom styles for this template -->
        <link href="stylesheets/custom.css" rel="stylesheet">
        <!-- <link href="stylesheets/default.css" rel="stylesheet"> -->
        <!-- <link href="stylesheets/custom.less" rel="stylesheet/less" type="text/css" > -->
        <link href="stylesheets/custom.less.css" rel="stylesheet" type="text/css" >
        <!-- <script src="scripts/less.min.js" type="text/javascript"></script> -->
        </head>
        
EOT;
//Above line must start from the first column for php not to flag an err
}

// Function to include common javascripts as a common footer using heredoc quoting
// Note that $tag needs to be defined
function InsertCommonJS() {
    echo <<<EOT
   
    <!-- Putting all scripts at end to make page load faster -->
    <!-- Latest compiled and minified JavaScript -->
    <!-- <script src="http://code.jquery.com/jquery-2.1.3.js"></script> -->
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.js"></script>
    <!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script> -->
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="scripts/jquery.qtip.js"></script>
    <!-- <script src="http://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.js"></script> -->
    <script src="scripts/indexpagescripts.js"></script>
    <script src="scripts/jquery.maphilight.min.js"></script>                    
    <script src="scripts/jquery.uploadfile.min.js"></script>            
    <script src="scripts/messenger.js"></script>
    <script src="scripts/messenger-theme-flat.js"></script>
    <script src="scripts/jquery.pwstrength.js"></script>
    
EOT;
}

function InsertCustomPagingJS($scriptname, $arg1 = null, $arg2 = null) {
    if (!empty($arg1) and empty($arg2)) {
        $varstr = "var tag = " . $arg1 . ";\n";
        $datastr = ", \"tag\": tag";
    } elseif (!empty($arg1) and ! empty($arg2)) {
        $varstr = "var state = " . $arg1 . ";\n"
                . "var array = " . $arg2 . ";\n";
        $datastr = ", \"state\": state,\"get_array\": array";
    } else {
        $varstr = "";
        $datastr = "";
    }

    echo <<<EOT
        
    <script type="text/javascript">
        var current_page    =   1; 
$varstr
        $(document).ready(function(){
 
            $.ajax({
                'url':'$scriptname',
                'type':'post',
                data: {"p": current_page $datastr},
                success:function(data){
                    var data    =   $.parseJSON(data);
                    $('#rtisbox').html(data.html);
                    $('#pagination').html(data.pagination);
                }
            });
 
            $('body').on('click','#pagination a',function(){
                $(this).addClass('loading');
                current_page    =   $(this).attr('href').split('#').join('');
                $.ajax({
                    'url':'$scriptname',
                    'type':'post',
                    data: {"p": current_page $datastr},
                    success:function(data){
                        var data    =   $.parseJSON(data);
                        $('#rtisbox').html(data.html);
                        $('#pagination').html(data.pagination);
                    }
                });
            });
 
        });
    </script>    
    
EOT;
//Above line must start from the first column for php not to flag an err
}

function InsertPagedRTITable($result, $includedetails = True,$title=null) {
    // Inserts two kinds of RTI tables. Detailed for search results, 
    // and names only for top page and state summary pages.
    $html = "";
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        if(!empty($title)){
            $html.="<h2>" .$title. "</h2>";
        }
        $html.= "<table class='table table-hover table-condensed'>";
        if ($includedetails == True) {
            $html.= "<thead><tr><th>RTI</th>";
            $html.= "<th>Filing Date</th>";
            $html.="</tr></thead>";
        };
        $html.= "<tbody>";
        for ($x = 1; $x <= $count && $rti = mysqli_fetch_assoc($result); $x++) {
            $html.= "<tr>";
            $html.= "<td>";
            $html.= ("<a href=\"rtiprofile.php?id=" . $rti['ID']
                    . "\"><b>" . $rti['Title'] . "</b></a>");
            if ($includedetails == True) {
                $html.= ( "<br/>" . substr($rti['Summary'], 0, 100) . "</td>"
                        . "<td>" . $rti['FilingDate'] );
            };
            $html.= "</td>";
            $html.= "</tr>";
        }
        $html.= "</tbody></table>";
    }
    return($html);
}

function CreateHeaderFromUrltags() {
    $get_array = filter_input_array(INPUT_GET);
    if ($get_array) {
        $headertext = "Search results for ";
        foreach ($get_array as $keyvalue => $term) {
            $headertext .= (" <em>" . $term . "</em> +");
        }
        $headertext = substr_replace($headertext, "", -1); //remove + at end
    }
    return($headertext);
}

function InsertFooter(){
    echo '     <div id="footer"> © RTISHARE.org. This is a work under progress. Please <a href="contactus.php">contact us </a> if you would like to know more.</div></div> ';
}

?>
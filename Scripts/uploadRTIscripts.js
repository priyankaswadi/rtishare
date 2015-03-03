        $( document ).tooltip({  //http://jqueryui.com/tooltip/                
                 position: { my: "left+15 center", at: "right center",collision: "none" }                 
        });
        
        $(document).ready(function(){
            $('#progressvalue').text('1 of 4');
            $("#page1").keypress(function(event){//preventing controls on page1 to submit on enter key
                return event.keyCode !== 13;
            });
            
            $("#title").keypress(function(event){ //preventing controls on page2 to submit on enter key
                return event.keyCode !== 13;        //doing seperately for each control as text area present
            });
            $("#responsedate").keypress(function(event){
                return event.keyCode !== 13;
            });
            $("#filingdate").keypress(function(event){
                return event.keyCode !== 13;
            });
            $("#satisfiedradio").keypress(function(event){
                return event.keyCode !== 13;
            });
   
            $("#page3").keypress(function(event){//preventing controls on page3 to submit on enter key
                return event.keyCode !== 13;
            });
            $("#page4").keypress(function(event){//preventing controls on page4 to submit on enter key
                return event.keyCode !== 13;
            });
            //initial view of page begins
            $("#page2").hide();                 
            $("#page3").hide();                 
            $("#page4").hide();  
            $("#state").hide();                 
            $("#cities").hide();                 
            $("#stateministries").hide();  
            $("#stateministries_dropdown").hide();  
            $("#taluka").hide();                 
            $("#cities_not_listed").hide();                 
            $("#zipcode").hide();  
            $("#activistname").hide();  
            $("#contactdiv").hide();  
            //events that may occur and actions to perform
            $("#next1").click(
                function()
                    {  
                        var error1_flag = 0;
                        var select =$('input:radio[name=select]:checked').val();                        
                        if (select==="Center"){                            
                            var centerlist = $("#centerlist").val();
                            var center_dropdown = $("#center_dropdown").val();
                            if (center_dropdown==="-1"){
                                if (centerlist===""){
                                    error1_flag = 1; 
                                    ErrorMsg("Please enter central ministry");
                                }                                
                            }                            
                        }
                        else if(select==="State"){                            
                            var state = $("#state").val();
                            var city = $("#cities").val();  
                            var state_dropdown = $("#stateministries_dropdown").val();
                            var stateministry = $("#stateministries").val();
                            var zipcode = $("#zipcode").val();                    
                            if(state==="-1"){
                                error1_flag = 1;
                                ErrorMsg("Please enter state");
                            }
                            if (city==="-1"){
                                error1_flag = 1;
                                ErrorMsg("Please enter city");
                            }
                            if (state_dropdown==="-1"){
                                if (stateministry===""){
                                    error1_flag = 1;
                                    ErrorMsg("Please enter state ministry");
                                }
                            }
                            if (zipcode===""){
                                error1_flag = 1;
                                ErrorMsg("Please enter Pin Code");
                            }
                            else{
                                var pinregex = /\d{6}/;
                                if(!pinregex.test(zipcode)){
                                    error1_flag = 1;
                                    ErrorMsg("Pin code should be six digit.");
                                }
                            }
                            
                        }
                        if(error1_flag===0){
                            $("#page1").hide();                 
                            $("#page2").show();                 
                            animateProgress(parseInt($(this).data()));
                            $('#progressvalue').text('2 of 4');
                        }                                    
                    });
            $("#next2").click(
                function()
                    {                        
                        var error2_flag=0;
                        var title = $("#title").val();
                        var summary = $("#summary").val();
                        var filingdate = $("#filingdate").val();
                        var responsedate = $("#responsedate").val();
                        if(title===""){
                            error2_flag = 1;
                            ErrorMsg("Please enter a title");                        }
                        if(summary===""){
                            error2_flag = 1;
                            ErrorMsg("Pease enter a short summary");
                        }
                        if(filingdate===""){
                            error2_flag = 1;
                            ErrorMsg("Please enter a filing date");
                        }     
                        else
                        {
                            var dateregex = /^(\d{1,2})\-(\d{1,2})\-(\d{4})$/;
                            if(!dateregex.test(filingdate)){
                                error2_flag = 1;
                                ErrorMsg("Enter valid filing date");
                            }
                        }
                        if(responsedate===""){
                            error2_flag = 1;
                            ErrorMsg("Please enter a response date");
                        }
                        else
                        {
                            var dateregex = /^(\d{1,2})\-(\d{1,2})\-(\d{4})$/;
                            if(!dateregex.test(responsedate)){
                                error2_flag = 1;
                                ErrorMsg("Enter valid response date");
                            }
                        }
                        var dateFirst = filingdate.split('-');
                        var dateSecond = responsedate.split('-');
                        var fdate = new Date(dateFirst[2], dateFirst[1], dateFirst[0]); //Year, Month, Date
                        var rdate = new Date(dateSecond[2], dateSecond[1], dateSecond[0]);
                        if(fdate>rdate){
                                error2_flag = 1;
                                ErrorMsg("Response Date should be later than filing date.");
                        }
                        if (error2_flag===0){
                            $("#page2").hide();                 
                            $("#page3").show();               
                            animateProgress(parseInt($(this).data()));
                            $('#progressvalue').text('3 of 4');
                        }                        
            
                    });
            $("#next3").click(
                function()
                    {                        
                        var error3_flag=0;
                        var period = $("#period").val();
                        var periodregex = /^(\d{4})(\-(\d{4}))?$/;                        
                        if(!periodregex.test(period)&& period!==""){
                            error3_flag = 1;
                            ErrorMsg("Please specify the period in a correct format. Only numbers and - allowed.");
                        }
                        if (error3_flag===0){
                            $("#page3").hide();                 
                            $("#page4").show();               
                            animateProgress(parseInt($(this).data()));
                            $('#progressvalue').text('4 of 4');
                        }              
                            
                    });
            
            $("#back1").click(
                function()
                    {
                        $("#page2").hide();                 
                        $("#page1").show();                 
                        animateProgress_back(parseInt($(this).data()));
                        $('#progressvalue').text('1 of 4');
                    });            
            $("#back2").click(
                function()
                    {
                        $("#page3").hide();                 
                        $("#page2").show();                 
                        animateProgress_back(parseInt($(this).data()));
                        $('#progressvalue').text('2 of 4');
                    });                            
            $("#back3").click(
                function()
                    {
                        $("#page4").hide();                 
                        $("#page3").show();                 
                        animateProgress_back(parseInt($(this).data()));
                        $('#progressvalue').text('3 of 4');
                    });                                
            $("#anonno").click(
                function()
                    {                        
                        $("#contactdiv").animate( { "opacity": "show"} , 750 );                        
                    });
            $("#anonyes").click(
                function()
                    {                        
                        $("#contactdiv").hide();
                    });            
            $("#notselffiledradio").click(
                function()
                    {                        
                        $("#activistname").animate( { "opacity": "show"} , 750 );                        
                    });
            $("#selffiledradio").click(
                function()
                    {                        
                        $("#activistname").hide();                        
                    });
            
            $("#centerradio").click(
                function()
                    {
                        $("#state").hide();
                        $("#cities").hide();                 
                        $("#stateministries").hide();              
                        $("#stateministries_dropdown").hide();              
                        $("#taluka").hide();
                        $("#zipcode").hide();                 
                        $("#cities_not_listed").hide();                                      
                        $("#centerlist").animate( { "opacity": "show"} , 750 );
                        $("#center_dropdown").animate( { "opacity": "show"} , 750 );
                        
                    });
            $("#stateradio").click(
                function()
                    {
                        $("#centerlist").hide();                        
                        $("#center_dropdown").hide();  
                        $("#state").animate( { "opacity": "show"} , 750 );
                        $("#cities").animate( { "opacity": "show"} , 750 );
                        $("#stateministries").animate( { "opacity": "show"} , 750 );
                        $("#stateministries_dropdown").animate( { "opacity": "show"} , 750 );
                        $("#taluka").animate( { "opacity": "show"} , 750 );
                        $("#cities_not_listed").animate( { "opacity": "show"} , 750 );
                        $("#zipcode").animate( { "opacity": "show"} , 750 );
                    });
            $( "#centerlist" ).autocomplete({ 
                    source: "search_central.php",                    
                    minLength: 2
                });            
            $( "#stateministries" ).autocomplete({ 
                    source: "search_state_ministries.php",                    
                    minLength: 2
                }); 
           $("select#state").change(function(){
                    var state_id = $("select#state option:selected").attr('value');                                   
                    $("#cities").html( "" );
                    if (state_id.length > 0 ) {

                    $.ajax({
                            type: "POST",
                            url: "fetch_city.php",
                            data: "state_id="+state_id,
                            cache: false,
                            beforeSend: function () {
                                    $('#cities').html('<img src="loader.gif" alt="" width="24" height="24">');
                            },
                            success: function(html) {                                
                                    $("#cities").html( html );
                                    
                            }
                    });
                    }
            
            });     
            $( "#cities_not_listed" ).autocomplete({
                source: function(request, response) {
                    $.getJSON ('search_city.php',
                        { term: request.term,state:$('#state').val()},
                        response );
                },
                minLength: 2            
            });   
            $( "#stateministries" ).autocomplete({ 
                    source: "search_state_ministries.php",                    
                    minLength: 2
            });
            $( "#benefitsmost" ).autocomplete({ 
                    source: "fetch_benefitsmost.php",                                        
                    minLength: 2
            });
            //code to auto-complete tags            
            function split( val ) {
                    return val.split( /,\s*/ );
            }
            function extractLast( term ) {
                    return split( term ).pop();
            } 
            $( "#tags" ).autocomplete({ 
                    source: function( request, response ) {
                        $.getJSON( "searchtags.php", {
                            term: extractLast( request.term )
                        }, response );
                    },
                    focus: function() {
                            return false;
                    },
                    select: function( event, ui ) {
                        $(this).html(ui.item.value);                                              
                        $(this).css("font-weight","bold");
                        $(this).css("font-style","italic");
                        $(this).css("color","gray");
                        var terms = split( this.value );
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push( ui.item.value );
                        // add placeholder to get the comma-and-space at the end
                        terms.push( "" );
                        this.value = terms.join( ", " );
                        return false;
                    },
                    minLength: 2
            });
            //$( "#filingdate" ).datepicker({ dateFormat: "dd-mm-yy" });           
            $(function() {
                    $( "#filingdate" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true, changeYear: true });                    
            });
            $(function() {               
                    $( "#responsedate" ).datepicker({ dateFormat: "dd-mm-yy", changeMonth: true, changeYear: true});
            });
            function ErrorMsg(message){//http://github.hubspot.com/messenger/docs/welcome/
                Messenger.options = {theme: 'flat'};
                 Messenger().post({
                        message: message,
                        type: 'error',
                        showCloseButton: true,
                        hideAfter: 3                                                
                });
            }            
            // animate progress by 25 each time next is clicked
            function animateProgress() {
                var currValue = $("#progress").val();
                var toValue = currValue + 25;
    
                toValue = toValue < 0 ? 0 : toValue;
                toValue = toValue > 100 ? 100 : toValue;

                $("#progress").animate({'value': toValue}, 500);
            }
            // animate progress by -25 each time back is clicked
            function animateProgress_back() {
                var currValue = $("#progress").val();
                var toValue = currValue - 25;
    
                toValue = toValue < 0 ? 0 : toValue;
                toValue = toValue > 100 ? 100 : toValue;

                $("#progress").animate({'value': toValue}, 500);
            }
              
        });

        $(document).ready(function()
        {
            file_names=[];
            $("#fileuploader").uploadFile({
                url: "getfile.php",
                dragDrop: true,
                fileName: "myfile",
                returnType: "json",
                maxFileSize:1024*1024*5,
                maxFileCount:5,
                multiple: true,
                showDelete: true,
                showDone:false,
                allowedTypes:"png,gif,jpg,jpeg,doc,docx,pdf",
                deleteCallback: function (data, pd) {
                    for (var i = 0; i < data.length; i++) {
                    $.post("deletefile.php", {op: "delete",name: data[i]},
                    function (resp,textStatus, jqXHR) {
                     //Show Message	
                        $("#fileupload_eventsmessage").html($("#fileupload_eventsmessage").html()+"<br/>Deleted file: "+JSON.stringify(data));                    
                        var itemtoRemove = data;                    
                        file_names.splice($.inArray(itemtoRemove, file_names),1); //find index of itemtoRemove and use splice to remove 1 element at that index and shift the rest of the array
                        
                    });
                    }
                    pd.statusbar.hide();
                },
                sizeErrorStr:"File greater than ",
                extErrorStr:"Extension not supported<br />Please upload files with extension ",
                maxFileCountErrorStr: " is not allowed. Maximum allowed files are:",
                onSuccess:function(files,data,xhr)
                {
                    $("#fileupload_eventsmessage").html($("#fileupload_eventsmessage").html()+"<br/>Upload complete: "+JSON.stringify(data));
                    file_names.push(data);                
                },
                afterUploadAll:function()
                {
                    $("#fileupload_eventsmessage").html($("#fileupload_eventsmessage").html()+"<br/>All files are uploaded");               
                    
                },
                onError: function(files,status,errMsg)
                {
                    $("#fileupload_eventsmessage").html($("#fileupload_eventsmessage").html()+"<br/>Error uploading: "+JSON.stringify(files));
                }
            });
            $("input[name='submit']").click(
                function()
                    {   
                        $("#fileslist").val(file_names);                     
                    });            
            
        });

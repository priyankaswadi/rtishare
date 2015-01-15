$( document ).tooltip({  //http://jqueryui.com/tooltip/                
                 position: { my: "left+15 center", at: "right center",collision: "none" }                 
});

$(document).ready(function(){
            $("#state").hide();                 
            $("#cities").hide();                 
            $("#stateministries").hide();  
            $("#centerradio").click(
                function()
                    {
                        $("#state").hide();
                        $("#cities").hide();                 
                        $("#stateministries").hide();              
                        $("#centerlist").animate( { "opacity": "show"} , 750 );
                        
                    });
            $("#stateradio").click(
                function()
                    {
                        $("#centerlist").hide();
                        $("#state").animate( { "opacity": "show"} , 750 );
                        $("#cities").animate( { "opacity": "show"} , 750 );
                        $("#stateministries").animate( { "opacity": "show"} , 750 );
                    });
            $( "#centerlist" ).autocomplete({ 
                    source: "search_central.php",                    
                    minLength: 2
                });
            $( "#cities" ).autocomplete({
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
});
    
$(document).ready(function(){            
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
            
            $('area').qtip({
                style: { classes: 'qtip-blue' },
                position: {
                     target: 'mouse', // Track the mouse as the positioning target
                     adjust: { x: 15, y: 10 } // Offset it slightly from under the mouse
                }    
            });
            

    $(function() {
		$('.imgmap').maphilight({
                    stroke: false,
                    fillColor: '0000FF',
                    fillOpacity: 0.5
                });
            });
            
});
    
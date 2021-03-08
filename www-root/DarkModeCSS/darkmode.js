$( ".inner-switch" ).on("click", function() {
    if( $( "body" ).hasClass( "dark" )) {
      $( "body" ).removeClass( "dark" );
      $( ".inner-switch" ).text( "off" );
    } else {
      $( "body" ).addClass( "dark" );
      $( ".inner-switch" ).text( "on" );
    }
});
$( ".inner-switch2" ).on("click", function() {
    	window.alert("sometext");
    if( $( ".inner-switch2" ).text == "All Readings") {
    	window.alert("sometext2");
      $( "body" ).removeClass( "dark" );
      $( ".inner-switch" ).text( "off" );
    } else {
      $( "body" ).addClass( "dark" );
      $( ".inner-switch" ).text( "on" );
    }
});
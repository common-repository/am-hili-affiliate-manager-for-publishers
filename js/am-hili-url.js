function getAmHiliUrl ( jsfile ) { 
var scriptElements = document.getElementsByTagName('script');
var i, element, amhilifile; 
	for( i = 0; element = scriptElements[i]; i++ ) { 
		amhilifile = element.src;
		if( amhilifile.indexOf( jsfile ) >= 0 ) {
			var amhiliurl = amhilifile.substring( 0, amhilifile.indexOf( jsfile ) ); 
		}			
	}
return amhiliurl;
}
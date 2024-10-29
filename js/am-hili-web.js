/*part of am-hili plugin*/
jQuery(document).ready(function(e) {
		var amHiliUrl = getAmHiliUrl('/js/am-hili-web.js');	

		jQuery( ".am-hili" ).css("cursor","pointer");
		jQuery( ".am-hili" ).each(function(index, element) {
		jQuery(this).html('<a>'+jQuery(this).html()+'</a>')
		});	
	
	jQuery( ".am-hili" ).click(function() {
			
			var am_hili_data = {
			'action': 'am_hili_web_ajax',
			'inc': 'redirect',
			'id': jQuery(this).attr('id')
		};

		jQuery.post(ajaxurl,am_hili_data,function(data){
		if(data)
		window.location = data;
	  });
	});

});
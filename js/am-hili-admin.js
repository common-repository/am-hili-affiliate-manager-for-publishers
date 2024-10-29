/*part of AM-HILI affiliate manager*/

/*switching pages*/
function amhiliSwitchPage(obj){
			
			var objName = jQuery(obj).attr('name');
			var objVal = jQuery(obj).val();
			var url = location.search;
			var getItems = {};
			
			var urlGets = url.split('&');
			
			for (i=1;i<urlGets.length;i++) {
				var gItems = urlGets[i].split('=');
				getItems[gItems[0]] = ['&'+urlGets[i]];
			}
			
			if (getItems[objName])
			url = url.replace(getItems[objName],'');
			
			if (objVal != "")
			url = url + '&'+objName+'='+objVal;
			
			if (objVal == "reset")
			url = '?page=am-hili' + getItems['inc'];

			window.location = url;
			
		}


/*delete affiliate link*/
function deleteAmHiLi(link_id){
	if (confirm('Are you sure! Delete link','am_hili')==1){
		
		var am_hili_data = {
			'action': 'am_hili_ajax',
			'inc': 'links',
			'act':'delete',
			'_am_hili_':am_hili_nonce,
			'link_id':link_id
		};
		
		jQuery.post(ajaxurl,am_hili_data,function(data){
		if(data){
		if (data=="done")
		location.reload(true)
		else
		alert(data);
		};
		})
	}
	
	return false;
}

/*
change affiliate link status (active/not active)
when is not active the default link will fill the space 
if there is one chosen as default
*/
function change_hili_status(obj){
	var active = jQuery(obj).attr('data-status');
	var link_id = jQuery(obj).attr('data-id');
	var resultActive = "no";
	var resultClass = "fa fa-toggle-off";
	var resultColor = "red";
	var actActive = "no";
	
	if (active=="no"){
		resultActive = "yes";
		resultClass = "fa fa-toggle-on";
		resultColor = "green";
		actActive = "yes";
	}
	
	var am_hili_data = {
			'action': 'am_hili_ajax',
			'inc': 'links',
			'act':'status',
			'_am_hili_':am_hili_nonce,
			'link_id':link_id,
			'active':actActive
		};
		
		jQuery.post(ajaxurl,am_hili_data,function(data){
		if(data){
		if (data=="done"){
			jQuery(obj).attr('data-status',resultActive);
			jQuery(obj).attr('class',resultClass);
			jQuery(obj).css('color',resultColor);
		} else {
				alert(data);
				}
		};
		})
}

/* getting a list of affiliate advertisers to choose from*/
function getAmHiLiadvertisers(link_id,adv_id){
		
		var am_hili_data = {
			'action': 'am_hili_ajax',
			'inc': 'advertisers',
			'act':'get_advertisers',
			'_am_hili_':am_hili_nonce,
			'link_id':link_id,
			'adv_id':adv_id
		};
		
		jQuery.post(ajaxurl,am_hili_data,function(data){
			if(data){
				jQuery("#advertisers_"+link_id).html(data)
			};
		})
}

/* getting a list of affiliate categories */
function getAmHiLiCategories(link_id,cat_id){
	
	var am_hili_data = {
			'action': 'am_hili_ajax',
			'inc': 'categories',
			'act':'get_categories',
			'_am_hili_':am_hili_nonce,
			'link_id':link_id,
			'cat_id':cat_id
		};
		
		jQuery.post(ajaxurl,am_hili_data,function(data){
			if(data){
				jQuery("#categories_"+link_id).html(data)
			};
		})
}

/* save the chosen category / advertiser */
function saveAmHiLiCatAdv(obj){
	
	var form_data = jQuery(obj).serialize();
	
		jQuery.post(ajaxurl,form_data,function(data){
			if(data){
				if (data=="done")
				location.reload(true)
				else
				alert(data);
			};
	})
	return false;
}

/*check am-hili api key*/
function checkAmHiliApiKey(obj){
	
	var form_data = jQuery(obj).serialize();

		jQuery.post(ajaxurl,form_data,function(data){
			if(data){
				if (data=="done")
				location.reload(true)
				else
				jQuery("#am_hili_api_key_info").html(data.replace('info|',''));
			};
	})
	return false;
}

jQuery(document).ready(function(e) {

});


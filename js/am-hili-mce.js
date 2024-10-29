/*am-hili plugin*/
var amHiliUrl = getAmHiliUrl('/js/am-hili-mce.js');

(function() {
	if(typeof(tinymce)!='undefined'){
    tinymce.PluginManager.add('am_hili_mce_button', function( editor, url ) {
        editor.addButton( 'am_hili_mce_button', {
            title: 'AM-HiLi',
            image: amHiliUrl+'/images/tinymce-logo.png',
            onclick: function() {
				if (editor.selection.getNode().nodeName.toLowerCase() == 'a'){
					editor.selection.select(editor.selection.getNode());
					var amhiliTxt = editor.selection.getContent( {format : 'html'} );
					
					var am_hili_data = {
						'action': 'am_hili_ajax',
						'inc': 'make-id',
						'_am_hili_':am_hili_nonce
					};
		
						jQuery.post(ajaxurl,am_hili_data,function(data){
							if (data)
							editor.selection.setContent('[amhili id="'+data+'"]'+amhiliTxt+'[/amhili]');
						});
				}
            }
        });
    });
	}
})();
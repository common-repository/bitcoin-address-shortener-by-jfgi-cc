jQuery(document).ready(function(){ //alert();
	
	
	jQuery("#jfgi_setup").click(function(){		
		jQuery("#jfgi_setup").removeClass("jfgiMenuShadow");
		//$("#jfgi_setup").addClass("jfgiMenuNoShadow");
		
	});
	jQuery("#jfgi_clickLogs").click(function(){		
		jQuery("#jfgi_clickLogs").removeClass("jfgiMenuShadow");
		//$("#jfgi_clickLogs").addClass("jfgiMenuNoShadow");
		
	});
	jQuery("#jfgi_advanced").click(function(){		
		jQuery("#jfgi_advanced").removeClass("jfgiMenuShadow");
		//$("#jfgi_advanced").addClass("jfgiMenuNoShadow");
		
	});
	jQuery("#jfgi_upgrade").click(function(){		
		jQuery("#jfgi_upgrade").removeClass("jfgiMenuShadow");
		//$("#jfgi_upgrade").addClass("jfgiMenuNoShadow");
		//$(".jfgiMenu").addClass("jfgiMenu jfgiMenuShadow");
	});
	jQuery("#jfgi_support").click(function(){		
		jQuery("#jfgi_support").removeClass("jfgiMenuShadow");
		//$("#jfgi_support").addClass("jfgiMenuNoShadow");
		
	});
	jQuery('.jfgiLinkContainer').on('click', '.jfgiCopy', function(){
        var idies = jQuery(this).attr('id');
	    idies = jQuery("#"+idies).prev().attr('id');		
		jQuery("#"+idies).select();
		document.execCommand("copy");
		alert('Copied the text:' + jQuery("#"+idies).val());
	});
    
});
jQuery(function() {
    jQuery('input[name="generateUpgrade"]').on('click', function() {
        if (jQuery(this).val() == '11' || jQuery(this).val() == '22' || jQuery(this).val() == '44' || jQuery(this).val() == '99' || jQuery(this).val() == '999') {
            window.location.href='https://www.jfgi.cc/';
        }
        
    });
});


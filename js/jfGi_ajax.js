function doConfirm(msg, yesFn, noFn) { 
    var confirmBox = jQuery("#confirmBox"); 
    confirmBox.find(".message").text(msg); 
    confirmBox.find(".yes,.no").unbind().click(function () {
        confirmBox.hide();
    });
    confirmBox.find(".yes").click(yesFn);
    confirmBox.find(".no").click(noFn);
    confirmBox.show();
}


function jfgideleteForm(){ 
	//e.preventDefault();

	doConfirm("Are you sure?", function yes() {
        var ecpt_BTCAddressDelete_nonce_field = jQuery('#ecpt_BTCAddressDelete_nonce_field').val();
		var data = {
			'action': 'deletejfGiAddress',
            'nonce' : ecpt_BTCAddressDelete_nonce_field
		};
        jQuery.ajax({
		    type: "post",
		    //dataType: "json",
		    url: jfGi_ajax_object.jfGi_ajaxurl,
		    data: data,
		    success: function(msg){
                window.location.reload();
		        console.log(msg);
		    }
        });
    }, function no() {
        console.log("BTC Address is not deleted.");
    });

}
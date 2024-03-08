	/* ----------------- Start Document ----------------- */
(function($) {


   	window.getRecaptcha = function() {
    grecaptcha.ready(function() {
        grecaptcha.execute(truelysell_core.recaptcha_sitekey3, {action: 'login'}).then(function(token) {
            $('.truelysell-registration-form #token').val(token);
        });
    });
	}
	
	$(document).ready(function(){ 
	    if(truelysell_core.recaptcha_status){
	        if(truelysell_core.recaptcha_version == 'v3'){
	            getRecaptcha();        
	        }
	    }
	});
    
// ------------------ End Document ------------------ //


})(jQuery);
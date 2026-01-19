$(function () {
	
	jQuery.support.placeholder = false;
	test = document.createElement('input');
	if('placeholder' in test) jQuery.support.placeholder = true;
	
	if (!$.support.placeholder) {
		
		$('.field').find ('label').show ();
		
	}
	
});

$(document).ready(function(){
	jQuery("#UsersLoginForm").submit(function(){
		if(jQuery("#loginUsername").val()==""){
			jQuery("#loginUsername").addClass('error-field');
			return false;
		} else {
			jQuery("#loginUsername").removeClass('error-field');
		}
		if(jQuery("#loginPassword").val()==""){
			jQuery("#loginPassword").addClass('error-field');
			return false;
		}else {
			jQuery("#loginPassword").removeClass('error-field');
		}
		return true;
	});
});

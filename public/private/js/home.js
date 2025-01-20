jQuery(function($)
{
	$('#contactType').change(function()
	{
		var phones = $(this).val();
		
		switch(phones){
			case "BORROWER" :
				$("#borrowerPhones").show();
				$("#spousePhones").hide();
				$("#keyContactPhones").hide();
				
				break;
			case "SPOUSE" : 
				$("#borrowerPhones").hide();
				$("#spousePhones").show();
				$("#keyContactPhones").hide();
				
				break;
			case "KEY_CONTACT" :
				$("#borrowerPhones").hide();
				$("#spousePhones").hide();
				$("#keyContactPhones").show();
				
				break;
		}
		
		//Disable that already dialed
		//disableButton();
	});
	
	$("#dial_button").click(function()
	{
		phoneButtonClick($("input[name=phone-number-radio]:checked"));
	});
		
	$('#frmDialMenu').submit(function ()
	{
		//$("html, body").animate({ scrollTop: 310 }, 500);
		return false;
	});
	
});
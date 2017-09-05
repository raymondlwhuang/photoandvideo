$(function ()
{
	if (!$.cbexp.isCookieEnabled())
		$('#noCookieContainer').css("display","inline");

	var qs = $.cbexp.getQueryString();

	var paramCT = qs['theme'];
	if (paramCT == "green")
		loadGreenTheme();
	else if (paramCT == "blue")
		loadBlueTheme();

	var paramHF = qs['headerfooter'];
	if (paramHF == 'header')
		loadPageHeader();
	else if (paramHF == 'footer')
		loadPageFooter();
	else if (paramHF == 'both')
	{
		loadPageHeader();
		loadPageFooter();
	}
});
$(document).ready(function() {
	$.cbexp.setDirtyFlag(true);
	$(".tab").hover(function(){$.cbexp.setDirtyFlag(false);}); 
	$(".tab").blur(function(){$.cbexp.setDirtyFlag(true);}); 
});

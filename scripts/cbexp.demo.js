$(function ()
{
	if ($.cbexp.isCookieEnabled())
		$('#pageContainer').fadeIn();
	else
		$('#noCookieContainer').fadeIn();

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

function loadPageHeader()
{
	$.cbexp.loadPageCSS('css/cbexp.header.css');
	$('#pageHeader').load('html/_header.html');
}

function loadPageFooter()
{
	$.cbexp.loadPageCSS('css/cbexp.footer.css');
	$('#pageFooter').load('html/_footer.html');
}

function loadGreenTheme()
{
	$.cbexp.loadPageCSS('css/cbexp.demo.green.css');
	$.cbexp.loadPageCSS('css/layout/cbexp.layout.2column.css');
}

function loadBlueTheme()
{
	$.cbexp.loadPageCSS('css/cbexp.demo.blue.css');
	$.cbexp.loadPageCSS('css/layout/cbexp.layout.2column_r.css');
}
jQuery.cbexp = {
	isDirty: false,

	refreshContent: function ()
	{
		$.cbexp.isDirty = false;
		window.location.reload(true);
	},

	setDirtyFlag: function (docDirty)
	{
		$.cbexp.isDirty = docDirty;
		if (docDirty)
			$(window).bind('beforeunload', $.cbexp.confirmExit);
		else
			$(window).unbind('beforeunload', $.cbexp.confirmExit);
	},

	getDirtyFlag: function ()
	{
		return $.cbexp.isDirty;
	},

	redirectNewUser: function ()
	{
		$.cbexp.isDirty = false;
		window.location.href = "index.html";
	},

	confirmExit: function ()
	{
		if ($.cbexp.isDirty)
			return "Exiting this page will end your session. If you haven't saved your info, it could be lost.";
	},

	getPageName: function ()
	{
		var requestedPage = location.href;
		requestedPage = requestedPage.substr(requestedPage.lastIndexOf("/") + 1); //extract page name from URL
		requestedPage = requestedPage.substr(0, requestedPage.lastIndexOf(".")); //strip off the file extension
		return requestedPage;
	},

	loadPageScript: function (jsUrl, onSuccess)
	{
		$.getScript(jsUrl, onSuccess);
	},

	loadPageCSS: function (cssUrl)
	{
		$('<link>').appendTo('head').attr({
			rel: "stylesheet",
			type: "text/css",
			href: cssUrl
		});
	},
	
	loadDivHTML: function(divID, htmlURL, onSuccess)
	{
		$('#' + divID).load(jsUrl, htmlURL, onSuccess);
	},

	isCookieEnabled: function ()
	{
		$.cookie('cbexp_cookie_detector', 'cbexp_test');
		var retVal = (null != $.cookie('cbexp_cookie_detector'));

		$.cookie('cbexp_cookie_detector', null);

		return retVal;
	},

	getQueryString: function ()
	{
		var qs = window.location.search;
		if (qs.length <= 1)
			return new Array();
		qs = qs.substring(1, qs.length);
		var a = qs.split("&");
		var b = new Array();
		for (var i = 0; i < a.length; ++i)
		{
			var p = a[i].split('=');
			b[p[0]] = decodeURIComponent(p[1]);
		}
		return b;
	},

	postJson: function (postURL, jsDataObj, onSuccess, onError, httpMethodOverride)
	{
		var beforeSendCallback = null;

		try
		{
			if (httpMethodOverride != undefined)
			{
				httpMethodOverride = httpMethodOverride.toUpperCase();
				if (httpMethodOverride == "GET" || httpMethodOverride == "PUT" || httpMethodOverride == "DELETE")
				{
					beforeSendCallback = function (xhr)
					{
						xhr.setRequestHeader("X-HTTP-Method-Override", httpMethodOverride);
						xhr.withCredentials = true;
					};
				}
			}
			else
			{
				beforeSendCallback = function (xhr)
				{
					xhr.withCredentials = true
				};
			}

			$.ajax({
				type: "POST",
				beforeSend: beforeSendCallback,
				contentType: "application/json",
				dataType: "json",
				url: postURL,
				data: $.toJSON(jsDataObj),
				async: true,
				success: onSuccess,
				error: onError
			});
		}
		catch (err)
		{
			alert("Ajax call error : " + err.description);
		}
	}
};

 

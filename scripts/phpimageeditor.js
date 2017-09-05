$(document).ready(function()
{
    var ImageMaxWidth = parseInt($('#const-imagemaxwidth').val());
    var ImageMaxHeight = parseInt($('#const-imagemaxheight').val());
    var ImageWidth = parseInt($('#const-imagewidth').val());
    var ImageHeight = parseInt($('#const-imageheight').val());
    var TextIsRequired = $('#const-textisrequired').val();
    var TextMustBeNumeric = $('#const-textmustbenumeric').val();
    var TextWidth = $('#const-textwidth').val();
    var TextHeight = $('#const-textheight').val();
    var TextNotNegative = $('#const-textnotnegative').val();
    var TextNotInRange = $('#const-textnotinrange').val();
    var TextCantBeLargerThen = $('#const-textcantbelargerthen').val();
    var TextAnUnexpectedError = $('#const-textanunexpectederror').val();
    var TextStartPositionX = $('#const-textstartpositionx').val();
    var TextStartPositionY = $('#const-textstartpositiony').val();
    var TextColorPickerSubmit = $('#const-textcolorpickersubmit').val();
    var TextSaveAs = $('#const-textsaveas').val();
    var TextFilenameError = $('#const-textfilenameerror').val();
    var Brightness = parseInt($('#const-brightness').val());
    var Contrast = parseInt($('#const-contrast').val());
    var BrightnessMax = parseInt($('#const-brightnessmax').val());
    var ContrastMax = parseInt($('#const-contrastmax').val());
    var FormId = $('#const-formid').val();
    var ActionUpdate = $('#const-actionupdate').val();
    var ActionUndo = $('#const-actionundo').val();
    var ActionReset = $('#const-actionreset').val();
    var ActionSaveAndClose = $('#const-actionsaveandclose').val();
    var ActionRotateLeft = $('#const-actionrotateleft').val();
    var ActionRotateRight = $('#const-actionrotateright').val();
    var ActionRotateLeftText = $('#const-actionrotatelefttext').val();
    var ActionRotateRightText = $('#const-actionrotaterighttext').val();
    var ActionSaveAndClose = $('#const-actionsaveandclose').val();
    var ActionChangeText = $('#const-actionchangetext').val();
    var ActionDeleteText = $('#const-actiondeletetext').val();
    var ActionFlipHorizontal = $('#const-actionfliphorizontal').val();
    var ActionFlipVertical = $('#const-actionflipvertical').val();
    var MenuResize = $('#const-menuresize').val();
    var MenuRotate = $('#const-menurotate').val();
    var MenuCrop = $('#const-menucrop').val();
    var MenuEffects = $('#const-menueffects').val();
    var MenuText = $('#const-menutext').val();
    var MenuSaveAs = $('#const-menusaveas').val();
    var AjaxPostTimeoutMs = parseInt($('#const-ajaxposttimeoutms').val()); 
    var FontsizeMin = parseInt($('#const-fontsizemin').val());
    var FontsizeMax = parseInt($('#const-fontsizemax').val());
	
	var objCrop = null;
	
	function ajax_post()
	{
		if ($('#loading').css('display') == 'none') 
		{
			$('#width').attr('disabled','disabled');
			$('#height').attr('disabled','disabled');
			$('#keepproportions').attr('disabled','disabled');
			$('#btnrotateleft').attr('disabled','disabled');
			$('#btnrotateright').attr('disabled','disabled');
			$('#btnfliphorizontal').attr('disabled','disabled');
			$('#btnflipvertical').attr('disabled','disabled');
			$('#btnrotateright').attr('disabled','disabled');
			$('#cropx').attr('disabled','disabled');
			$('#cropy').attr('disabled','disabled');
			$('#cropwidth').attr('disabled','disabled');
			$('#cropheight').attr('disabled','disabled');
			$('#grayscale').attr('disabled','disabled');
			$('#roundedcorners').attr('disabled','disabled');
			$('#roundedcorners-radius').attr('disabled','disabled');
			$('#roundedcorners-colorpicker').attr('disabled','disabled');
			$('#btnupdate').attr('disabled','disabled');
			$('#btnsave').attr('disabled','disabled');
			$('#btnundo').attr('disabled','disabled');
			$('#btnreset').attr('disabled','disabled');
			$('#brightness_slider_track').slider('disable');
			$('#contrast_slider_track').slider('disable');
			$('#quality_slider_track').slider('disable');
			$('#text').attr('disabled','disabled');
			$('#textlist').attr('disabled','disabled');
			$('#textposx').attr('disabled','disabled');
			$('#textposy').attr('disabled','disabled');
			$('#textcolor').attr('disabled','disabled');
			$('#font').attr('disabled','disabled');
			$('#fontsize').slider('disable');
			$('#btnrotateleft_text').attr('disabled','disabled');
			$('#btnrotateright_text').attr('disabled','disabled');
			$('#btnDelete_text').attr('disabled','disabled');
			$('#loading').css('display','block');
			$('#loading_bar').width(0);
			$('#loading_bar').animate({width: document.getElementById('loading').offsetWidth-30}, document.getElementById('loading').offsetWidth*10);
			
			$.ajax({timeout: AjaxPostTimeoutMs, type: "POST", url: "index.php", data: "grayscaleval="+$('#grayscaleval').val()+"&keepproportionsval="+$('#keepproportionsval').val()+"&width="+$('#width').val()+"&widthoriginal="+$('#widthoriginal').val()+"&height="+$('#height').val()+"&heightoriginal="+$('#heightoriginal').val()+"&rotate="+$('#rotate').val()+"&croptop="+$('#croptop').val()+"&cropright="+$('#cropright').val()+"&cropbottom="+$('#cropbottom').val()+"&cropleft="+$('#cropleft').val()+"&contrast="+$('#contrast').val()+"&brightness="+$('#brightness').val()+"&actiontype="+$('#actiontype').val()+"&panel="+$('#panel').val()+"&language="+$('#language').val()+"&actions="+$('#actions').val()+"&widthlast="+$('#widthlast').val()+"&heightlast="+$('#heightlast').val()+"&userid="+$('#userid').val()+"&contrastlast="+$('#contrastlast').val()+"&brightnesslast="+$('#brightnesslast').val()+"&widthlastbeforeresize="+$('#widthlastbeforeresize').val()+"&heightlastbeforeresize="+$('#heightlastbeforeresize').val()+"&cropkeepproportionsval="+$('#cropkeepproportionsval').val()+"&cropkeepproportionsratiow="+$('#cropkeepproportionsratiow').val()+"&cropkeepproportionsratioh="+$('#cropkeepproportionsratioh').val()+"&text="+$('#text').val()+"&textlist="+$('#textlist').val()+"&text="+$('#text').val()+"&textposx="+$('#textposx').val()+"&textposy="+$('#textposy').val()+"&textcolor="+$('#textcolor').val()+"&fontsize="+$('#fontsize').val()+"&font="+$('#font').val()+"&rotatetext="+$('#rotatetext').val()+"&deletetext="+$('#deletetext').val()+"&imagesrc="+$('#imagesrc').val()+"&imagesrcoriginal="+$('#imagesrcoriginal').val()+"&code="+$('#code').val()+"&version="+$('#version').val()+"&roundedcornersval="+$('#roundedcornersval').val()+"&roundedcornerscolor="+$('#roundedcorners-colorpicker').val()+"&roundedcornersradius="+$('#roundedcorners-radius').val()+"&saveas="+$('#saveas').val()+"&saveasorg="+$('#saveasorg').val()+"&quality="+$('#quality').val()+"&flip="+$('#flip').val()+"&isajaxpost=true",    	
			success: function(data)
			{	
				$('#phpImageEditor').html(data);
				activate_form();
				phpimageeditor_crop_activator(parseInt($('#panel').val()));
				phpimageeditor_init();
				$('#loading_bar').stop();
				$('#loading').css('display','none');
			},
		    error: function(XMLHttpRequest, textStatus, errorThrown)
		    {
				activate_form();
				$('#ulJsErrors').html("");
				adderror(TextAnUnexpectedError);
				$('#divJsErrors').css('display','block');
				$('#ulJsErrors').css('display','block');
				phpimageeditor_crop_activator(parseInt($('#panel').val()));
				$('#loading_bar').stop();
				$('#loading').css('display','none');
		    }
			});
			
		}
	}
	
	function isinteger(s)
	{
	    var i;
	
	    if (isempty(s))
	    if (isinteger.arguments.length == 1) return 0;
	    else return (isinteger.arguments[1] == true);
	
	    for (i = 0; i < s.length; i++)
	    {
	       var c = s.charAt(i);
	
	       if (!isdigit(c)) return false;
	    }
	
	    return true;
	}
	
	function focus_on_enter(element, event)
	{
		if(event.keyCode == 13)
			element.focus();
	}
	
	function reload_mouse_crop()
	{
		objCrop.destroy();
		objCrop = $.Jcrop('#image',{onChange: set_crop_values,onSelect: set_crop_values, aspectRatio: $("input#cropkeepproportions").attr('checked') ? $("#cropkeepproportionsratiow").val() : 0});		
		$(".jcrop-holder").css("display", "none");
		$("#image").css("display", "block");			
	}
	
	function update_width(InputWidth, EditForm)
	{
		if (isinteger(InputWidth.val()))
		{
			var Width = parseInt($('#width').val());
			var Height = parseInt($('#height').val());
			
			$('#image').css('width',Width+'px');
			
			if ($("input#keepproportions").attr('checked'))
			{
				$('#image').css('height', get_proportional_height(Width, EditForm) + "px");
				$('#height').val(get_proportional_height(Width, EditForm));
			}
			
			update_mouse_resizer();
			reload_mouse_crop();
		}
	}
	
	function update_mouse_resizer()
	{
		$('#imageResizerKeepProportions').css('width',$('#width').val()+'px');	
		$('#imageResizerKeepProportions').css('height',$('#height').val()+'px');
		$('#imageResizerNoProportions').css('width',$('#width').val()+'px');	
		$('#imageResizerNoProportions').css('height',$('#height').val()+'px');
	}
	
	function update_height(InputHeight, EditForm)
	{
		if (isinteger(InputHeight.val()))
		{
			var Height = parseInt($('#height').val());
			var Width = parseInt($('#width').val());
			
			$('#image').css('height',$('#height').val()+'px');
			
			if ($("input#keepproportions").attr('checked'))
			{
				$('#image').css('width',get_proportional_width(Height, EditForm)+'px');
				$('#width').val(get_proportional_width(Height, EditForm));
			}
			
			update_mouse_resizer();
			reload_mouse_crop();
		}
	}
	
	function isempty(s)
	{
		return ((s == null) || (s.length == 0))
	}
	
	function isdigit (c)
	{
		return ((c >= "0") && (c <= "9"))
	}
	
	function isintegerinrange(s, a, b)
	{   
		if (isempty(s))
	         if (isintegerinrange.arguments.length == 1) return false;
	         else return (isintegerinrange.arguments[1] == true);
	
	      // Catch non-integer strings to avoid creating a NaN below,
	      // which isn't available on JavaScript 1.0 for Windows.
	      if (!isinteger(s, false)) return false;
	
	      // Now, explicitly change the type to integer via parseInt
	      // so that the comparison code below will work both on
	      // JavaScript 1.2 (which typechecks in equality comparisons)
	      // and JavaScript 1.1 and before (which doesn't).
	      var num = parseInt (s);
	      return ((num >= a) && (num <= b));
	}
	
	function validate_form()
	{
		var sendForm = true;
		
		var width = $('#width').val();
		var height = $('#height').val();
		var textposx = parseInt($('#textposx').val());
		var textposy = parseInt($('#textposy').val());
		
		$('#divJsErrors').css('display','none');
		$('#ulJsErrors').css('display','none');
		
		$('#ulJsErrors').html("");
	
		if (width == "")
		{
			adderror("\"" + TextWidth + "\" " + TextIsRequired)
			sendForm = false;
		}
		
		if (height == "")
		{
			adderror("\"" + TextHeight + "\" " + TextIsRequired)
			sendForm = false;
		}
		
		if (!sendForm)
		{
			$('#divJsErrors').css('display','block');
			$('#ulJsErrors').css('display','block');
	
			return sendForm;
		}
		
		if (!isinteger(width))
		{
			adderror("\"" + TextWidth + "\" " + TextMustBeNumeric)
			sendForm = false;
		}
		
		if (!isinteger(height))
		{
			adderror("\"" + TextHeight + "\" " + TextMustBeNumeric)
			sendForm = false;
		}
		
		if (!sendForm)
		{
			$('#divJsErrors').css('display','block');
			$('#ulJsErrors').css('display','block');
			return sendForm;
		}
	
		width = parseInt(width);
		height = parseInt(height);
	
		if (!isintegerinrange(width, 1, ImageMaxWidth))
		{
			adderror("\"" + TextWidth + "\" " + TextNotInRange + ": 1 - " + ImageMaxWidth)
			sendForm = false;
		}
		
		if (!isintegerinrange(height, 1, ImageMaxHeight))
		{
			adderror("\"" + TextHeight + "\" " + TextNotInRange + ": 1 - " + ImageMaxHeight)
			sendForm = false;
		}
		
		if ($('#textlist').val() == '-1' && $('#text').val() != '')
		{
			if (!isintegerinrange(textposx, 0, width))
			{
				adderror("\"" + TextStartPositionX + "\" " + TextNotInRange + ": 1 - " + width)
				sendForm = false;
			}
			
			if (!isintegerinrange(textposy, 0, height))
			{
				adderror("\"" + TextStartPositionY + "\" " + TextNotInRange + ": 1 - " + height)
				sendForm = false;
			}
		}
		
		if ($('#saveas').val() != $('#saveasorg').val())
		{
			$('#saveas').val(jQuery.trim($('#saveas').val()));
			
			if ($('#saveas').val() == '')
			{
				adderror("\"" + TextSaveAs + "\" " + TextIsRequired)
				sendForm = false;
			}
			else if ($('#saveas').val().match(/[^abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUWWXYZ0123456789_-]/g))
			{
				adderror(TextFilenameError)
				sendForm = false;
			}
		}
		
		if (!sendForm)
		{
			$('#divJsErrors').css('display','block');
			$('#ulJsErrors').css('display','block');
		}
	
		return sendForm;
	}
	
	function adderror(ErrorText)
	{
		$('#ulJsErrors').html($('#ulJsErrors').html()+'<li>'+ErrorText+'</li>');
	}
	
	function get_proportional_width(Height, EditForm)
	{
		var HeightOriginal = parseInt($("#heightlastbeforeresize").val());
		var WidthOriginal = parseInt($("#widthlastbeforeresize").val());
		
		return Math.round((WidthOriginal/HeightOriginal)*Height);
	}
	
	function get_proportional_height(Width, EditForm)
	{
		var HeightOriginal = parseInt($("#heightlastbeforeresize").val());
		var WidthOriginal = parseInt($("#widthlastbeforeresize").val());
		
		return Math.round((HeightOriginal/WidthOriginal)*Width);
	}
	
	function remove_px(Value)
	{
		return Value.replace("px","");
	}
	
	function activate_form()
	{
		$('#width').removeAttr('disabled');
		$('#height').removeAttr('disabled');
		$('#keepproportions').removeAttr('disabled');
		$('#btnrotateleft').removeAttr('disabled');
		$('#btnrotateright').removeAttr('disabled');
		$('#btnflipvertical').removeAttr('disabled');
		$('#btnfliphorizontal').removeAttr('disabled');
		$('#cropx').removeAttr('disabled');
		$('#cropy').removeAttr('disabled');
		$('#cropwidth').removeAttr('disabled');
		$('#cropheight').removeAttr('disabled');
		$('#grayscale').removeAttr('disabled');
		$('#roundedcorners').removeAttr('disabled');
		$('#roundedcorners-colorpicker').removeAttr('disabled');
		$('#btnupdate').removeAttr('disabled');
		$('#btnsave').removeAttr('disabled');
		$('#btnundo').removeAttr('disabled');
		$('#btnreset').removeAttr('disabled');
		$('#brightness_slider_track').slider('enable');
		$('#contrast_slider_track').slider('enable');
		$('#quality_slider_track').slider('enable');
		$('#text').removeAttr('disabled');
		$('#textlist').removeAttr('disabled');
		$('#textposx').removeAttr('disabled');
		$('#textposy').removeAttr('disabled');
		$('#textcolor').removeAttr('disabled');
		$('#font').removeAttr('disabled');
		$('#fontsize').slider('enable');
		$('#btnrotateleft_text').removeAttr('disabled');
		$('#btnrotateright_text').removeAttr('disabled');
		$('#btnDelete_text').removeAttr('disabled');
		
		if ($('#actions').val() == '')
			$('#btnundo').attr('disabled','disabled');
		else
			$('#btnundo').removeAttr('disabled');
	}
	
	
	function set_crop_values(c)
	{
		if (isinteger($("#height").val()) && isinteger($("#width").val()))
		{
			$("#croptop").val(c.y);
			$("#cropbottom").val(parseInt($("#height").val()) - (c.y + c.h));
			$("#cropleft").val(c.x);
			$("#cropright").val(parseInt($("#width").val()) - (c.x + c.w));
	
			$("#cropx").val(c.x);
			$("#cropy").val(c.y);
			$("#cropwidth").val(c.w);
			$("#cropheight").val(c.h);
		}
	}
	
	function set_crop_area(event, type) 
	{
		if (event.keyCode != 37 && event.keyCode != 39)
		{
			var v_w = $("#cropwidth").val();
			var v_h = $("#cropheight").val();
			var v_x = $("#cropx").val();
			var v_y = $("#cropy").val();
		
			var noCrop = false;
	
			if (v_w != '' && v_h != '' && v_x != '' && v_y != '')
			{
				var w = parseInt(v_w);
				var h = parseInt(v_h);
				var x = parseInt(v_x);
				var y = parseInt(v_y);
		
			    objCrop.setOptions({ aspectRatio: 0 });
	
				if ($('#cropkeepproportions').attr('checked'))
				{
					if (type == 'cropwidth')
						h = Math.round(w * parseFloat($("#cropkeepproportionsratioh").val()));
					else if (type == 'cropheight')
						w = Math.round(h * parseFloat($("#cropkeepproportionsratiow").val()));
				}
	
				var area = [x,y,x+w,y+h];
				
				objCrop.setSelect(area);
	
	            if (w == 0 || h == 0)
					noCrop = true;
	            
	            if ($('#cropkeepproportions').attr('checked'))
	            	objCrop.setOptions({ aspectRatio: parseFloat($("#cropkeepproportionsratiow").val()) });
			}
			else
				noCrop = true;
				
			if (noCrop)
			{
				$("#croptop").val('0');
				$("#cropright").val('0');
				$("#cropbottom").val('0');
				$("#cropleft").val('0');
			}	
		}
	};
	
	function set_crop_aspect_ratio(el) 
	{
	    if (objCrop != null)
	    {
	        if (el.attr('checked') && parseFloat($("#cropwidth").val()) != 0 && parseFloat($("#cropheight").val()) != 0)
	        {   
	            var aspectRatio = parseFloat($("#cropwidth").val()) / parseFloat($("#cropheight").val());
	            objCrop.setOptions({ aspectRatio: aspectRatio });
	            $("#cropkeepproportionsratiow").val(aspectRatio);
	            $("#cropkeepproportionsratioh").val(parseFloat($("#cropheight").val()) / parseFloat($("#cropwidth").val()));
	        }
	        else if (el.attr('checked') && parseFloat($("#cropwidth").val()) == 0 && parseFloat($("#cropheight").val()) == 0)
	        {
	            objCrop.setOptions({ aspectRatio: $("#cropkeepproportionsratiow").val() });
	        }   
	        else
	        {   
	            objCrop.setOptions({ aspectRatio: 0 });
	        }
	    }
	};
	
	function phpimageeditor_resize_activator(selectedIndex)
	{
		if (selectedIndex == MenuResize)
		{
			if ($('#keepproportionsval').val() == "1")
			{
				$("#imageResizerKeepProportions").css("display", "block");
				$("#imageResizerNoProportions").css("display", "none");
			}
			else
			{
				$("#imageResizerKeepProportions").css("display", "none");
				$("#imageResizerNoProportions").css("display", "block");
			}
		}
		else
		{
			$("#imageResizerKeepProportions").css("display", "none");
			$("#imageResizerNoProportions").css("display", "none");
		}
	}
	
	function phpimageeditor_panelfade(selectedIndex)
	{
		var panels = $('div.panel');
	
		for (i = 0; i < panels.length; i++)
		{
			if ($('#panel_'+i) != null)
			{
				$("#panel_"+i).css('opacity','0.0');
		
				if (i == selectedIndex)
				{
					$("#menuitem_"+i).removeClass("not-selected");				
					$("#menuitem_"+i).addClass("selected");
					$("#panel_"+i).css('display','block');
					$("#panel_"+i).fadeTo("normal", 1.0);
				}
				else
				{
					$("#menuitem_"+i).removeClass("selected");
					$("#menuitem_"+i).addClass("not-selected");				
					$("#panel_"+i).css('display','none');
				}
			}
		}
		
		if (selectedIndex != MenuCrop)
		{
			$("#cropleft").val("0");
			$("#cropright").val("0");
			$("#croptop").val("0");
			$("#cropbottom").val("0");
	
			$("#cropx").val("0");
			$("#cropy").val("0");
			$("#cropwidth").val("0");
			$("#cropheight").val("0");
		}
		
		phpimageeditor_resize_activator(selectedIndex);
		phpimageeditor_crop_activator(selectedIndex);
			
		$("#panel").val(selectedIndex);
	}
	
	function phpimageeditor_crop_activator(selectedIndex)
	{
		if (objCrop != null)
		{	
			if (selectedIndex != MenuCrop)
			{
				objCrop.release();
				objCrop.disable();
				$(".jcrop-holder").css("display", "none");
				$("#image").css("display", "block");
			}
			else
			{
				objCrop.release();
				objCrop.enable();
				$(".jcrop-holder").css("display", "block");
				$(".jcrop-holder").css("background-color", "white"); //To avoid black background on transparent images.
				$("#image").css("display", "block");
			}
		}
	}
	
	function phpimageeditor_init()
	{
	    objCrop = $.Jcrop('#image',{onChange: set_crop_values,onSelect: set_crop_values, aspectRatio: $("input#cropkeepproportions").attr('checked') ? $("input#cropkeepproportionsratiow").val() : 0});
	    
	    $("#imageResizerKeepProportions").resizable(
	    {
	        aspectRatio: parseFloat($("input#widthlastbeforeresize").val()) / parseFloat($("input#heightlastbeforeresize").val()),
	        stop: function(event,ui)
	        {        
	            var resize_width = parseInt(remove_px($("#imageResizerKeepProportions").css("width")));
	            var resize_height = parseInt(remove_px($("#imageResizerKeepProportions").css("height")));
	                        
	            $("#image").css("width", resize_width+"px");
	            $("#image").css("height", resize_height+"px");
	            $("#width").val(resize_width);
	            $("#height").val(resize_height);
	
	            update_mouse_resizer();
	
	            $("#imageResizerKeepProportions").css("opacity", "0.0");
	                        
	            reload_mouse_crop();
	        },
	        start: function(event,ui)
	        {        
	            $("#imageResizerKeepProportions").css("opacity", "0.5");
	        }
	    });
	                
	    $("#imageResizerNoProportions").resizable(
	    {
	        aspectRatio: false,
	        stop: function(event,ui)
	        {        
	            var resize_width = parseInt(remove_px($("#imageResizerNoProportions").css("width")));
	            var resize_height = parseInt(remove_px($("#imageResizerNoProportions").css("height")));
	                        
	            $("#image").css("width", resize_width+"px");
	            $("#image").css("height", resize_height+"px");
	            $("#width").val(resize_width);
	            $("#height").val(resize_height);
	                        
	            update_mouse_resizer();
	
	            $("#widthlastbeforeresize").val(resize_width);
	            $("#heightlastbeforeresize").val(resize_height);
	            $("#imageResizerKeepProportions").resizable('option','aspectRatio',parseFloat($('#widthlastbeforeresize').val())/parseFloat($('#heightlastbeforeresize').val()));
	
	            $("#imageResizerNoProportions").css("opacity", "0.0");
	
	            reload_mouse_crop();
	        },
	        start: function(event,ui)
	        {        
	            $("#imageResizerNoProportions").css("opacity", "0.5");
	        }
	    });
	    
		var contrast_number = $("#contrast_number");
	
	    $("#contrast_slider_track").slider({value: parseInt($("#contrast").val()) + ContrastMax, min: 1, max: ((ContrastMax*2)+1), step: 1,
	    	stop: function(event,ui)
	        {        
	            if (validate_form())
	                ajax_post();
	        },
	        slide: function(event, ui) 
	        {
	            var contrast = parseInt(ui.value)-((ContrastMax)+1);
	            $("#contrast").val(contrast);
	        	contrast_number.html(contrast);
	        }
	    });
	    
		var brightness_number = $("#brightness_number");
	    
	    $("#brightness_slider_track").slider({value: (parseInt($("input#brightness").val())+BrightnessMax),min: 1,max: ((BrightnessMax*2)+1),step: 1,
	    	stop: function(event,ui)
	        {        
	            if (validate_form())
	                ajax_post();
	        },
	        slide: function(event, ui) 
	        {
	            var brightness = parseInt(ui.value)-(BrightnessMax+1);
	            $("#brightness").val(brightness);
	        	brightness_number.html(brightness);
	        }
	    });
	                
		var fontsize = $("#fontsize");
		var fontsize_number = $("#fontsize_number");
	
		$("#fontsize_slider_track").slider({value: parseInt(fontsize.val()), min: FontsizeMin, max: FontsizeMax,step: 1,
	    	stop: function(event,ui)
	        {        
	        },
	        slide: function(event, ui) 
	        {
	        	fontsize.val(ui.value);
	        	fontsize_number.html(ui.value);
	        }
	    });
	
		var quality = $("#quality");
		var quality_number = $("#quality_number");
	
		$("#quality_slider_track").slider({value: parseInt(quality.val()), min: 0, max: 100,step: 1,
	    	stop: function(event,ui)
	        {        
	        },
	        slide: function(event, ui) 
	        {
	        	quality.val(ui.value);
	        	quality_number.html(ui.value);
	        }
	    });
	
	    $("#grayscale").click(function()
	    {
	        if (validate_form())
	        {
	            $("#grayscale").attr('checked') ? $('#grayscaleval').val('1') : $('#grayscaleval').val('0');
	            ajax_post();
	        }
	    });
	                
	    $("#roundedcorners").click(function()
	    {
	        if (validate_form())
	        {
	            $("#roundedcorners").attr('checked') ? $('#roundedcornersval').val('1') : $('#roundedcornersval').val('0');
	            ajax_post();
	        }
	    });
	
		$('#textcolor').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
		
		$('#roundedcorners-colorpicker').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
	
	    $("input#textposx").numeric();
	    $("input#textposy").numeric();
	    $("input#roundedcorners-radius").numeric();
	
	    $("input#cropx").numeric();
	    $("input#cropy").numeric();
	    $("input#cropwidth").numeric();
	    $("input#cropheight").numeric();
	
	    $("#btnupdate").click(function()
	    {
	        if (validate_form())
	        {
	        	$("#actiontype").val(ActionUpdate);                                      
	            ajax_post();
	        }
	    });
	                
	    $("#btnundo").click(function()
	    {
	        if (validate_form())
	        {
	            $("#actiontype").val(ActionUndo);                                    
	            ajax_post();
	        }
	    });
	
	    $("#btnreset").click(function()
	    {
	        if (validate_form())
	        {
	            $("#actiontype").val(ActionReset);                                    
	            ajax_post();
	        }
	    });
	
	    $("#btnsave").click(function()
	    {
	        if (validate_form())
	        {
	            $("#actiontype").val(ActionSaveAndClose);                                    
	            ajax_post();
	        }
	    });
	
	    $("#btnrotateleft").click(function()
	    {
	        if (validate_form())
	        {
	            $("#rotate").val(ActionRotateLeft);
	            ajax_post();
	        }
	    });
	
	    $("#btnrotateright").click(function()
	    {
	        if (validate_form())
	        {
	            $("#rotate").val(ActionRotateRight);
	            ajax_post();
	        }
	    });
	                
	    $("#btnflipvertical").click(function()
	    {
	        if (validate_form())
	        {
	            $("#flip").val(ActionFlipVertical);
	            ajax_post();
	        }
	    });
	
	    $("#btnfliphorizontal").click(function()
	    {
	        if (validate_form())
	        {
	            $("#flip").val(ActionFlipHorizontal);
	            ajax_post();
	        }
	    });

	    $("#btnrotateleft_text").click(function()
	    {
	        if (validate_form())
	        {
	            $("#rotatetext").val(ActionRotateLeftText);
	            ajax_post();
	        }
	    });
	
	    $("#btnrotateright_text").click(function()
	    {
	        if (validate_form())
	        {
	            $("#rotatetext").val(ActionRotateRightText);
	            ajax_post();
	        }
	    });
	
	    $("#btnDelete_text").click(function()
	    {
	        if (validate_form())
	        {
	            $("#deletetext").val(ActionDeleteText);
	            ajax_post();
	        }
	    });
	
	    $("form#" + FormId).submit(function()
	    {
	        if (validate_form())
	        {
	            $("#actiontype").val(ActionSaveAndClose);
	            return true;
	        }
	        return false;
	    });
	    
	    $("#textlist").change(function()
	    {
	        if (validate_form())
	        {
	            $("#actiontype").val(ActionChangeText);
	            ajax_post();
	        }
	    });
	                
	    $("#width").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	                
	    $("#width").keyup(function()
	    {
	        update_width($(this),$("form#" + FormId));
	    });
	                
	    $("#height").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	                
	    $("#height").keyup(function()
	    {
	        update_height($(this),$("form#" + FormId));
	    });
	    
	    $("#roundedcorners-radius").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	    
	    $("#roundedcorners-colorpicker").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	                
	    $("#saveas").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	    
	    $("#textposx").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	    
	    $("#textposy").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	    
	    $("#textcolor").keydown(function(event)
	    {
	        focus_on_enter($("input#btnupdate"), event);
	    });
	
	    $("#cropx").keyup(function(event)
	    {
	    	set_crop_area(event, 'cropx');
	    });
	
	    $("#cropy").keyup(function(event)
	    {
	    	set_crop_area(event, 'cropy');
	    });
	
	    $("#cropwidth").keyup(function(event)
	    {
	    	set_crop_area(event, 'cropwidth');
	    });
	
	    $("#cropheight").keyup(function(event)
	    {
	    	set_crop_area(event, 'cropheight');
	    });
	
	    $("#keepproportions").click(function()
	    {
	        if ($(this).attr('checked'))
	        {
	            $('#keepproportionsval').val('1');
	            $('#imageResizerKeepProportions').css('display','block');
	            $('#imageResizerNoProportions').css('display','none');  
	        }
	        else
	        {
	            $('#keepproportionsval').val('0'); 
	            $('#imageResizerKeepProportions').css('display','none');
	            $('#imageResizerNoProportions').css('display','block'); 
	        }
	    });
	         
	
	    $("#cropkeepproportions").click(function()
	    {
	        if ($(this).attr('checked'))
	        {
	            $('#cropkeepproportionsval').val('1');
	        }
	        else
	        {
	            $('#cropkeepproportionsval').val('0'); 
	        }
	    });
	    
	    $("#menuitem_" + MenuResize).click(function()
	    {
	        if ($('#panel').val() != MenuResize)
	        {
	            phpimageeditor_panelfade(MenuResize);
	        }
	    });
	                
	   $("#menuitem_" + MenuRotate).click(function()
	   {
	       if ($('#panel').val() != MenuRotate)
	       {
	            phpimageeditor_panelfade(MenuRotate);
	        }
	    });
	            
	    $("#menuitem_" + MenuCrop).click(function()
	    {
	        if ($('#panel').val() != MenuCrop)
	        {
	            phpimageeditor_panelfade(MenuCrop);
	        }
	    });
	            
	    if ($("#menuitem_" + MenuEffects) != null)
	    {
	        $("#menuitem_" + MenuEffects).click(function()
	        {
	            if ($('#panel').val() != MenuEffects)
	            {
	                phpimageeditor_panelfade(MenuEffects);
	            }
	        });
	    }
	
	    if ($("#menuitem_" + MenuText) != null)
	    {
	        $("#menuitem_" + MenuText).click(function()
	        {
	            if ($('#panel').val() != MenuText)
	            {
	                phpimageeditor_panelfade(MenuText);
	            }
	        });
	    }
	
	    if ($("#menuitem_" + MenuSaveAs) != null)
	    {
	        $("#menuitem_" + MenuSaveAs).click(function()
	        {
	            if ($('#panel').val() != MenuSaveAs)
	            {
	                phpimageeditor_panelfade(MenuSaveAs);
	            }
	        });
	    }
	
	    $('#cropkeepproportions').change(function(e) 
	    {
			set_crop_aspect_ratio($(this));    
	    });
	
	    $("input#width").numeric();
	    $("input#height").numeric();
	                
	    var selectedIndex = parseInt($('#panel').val());
	    var panels = $('div.panel');
	    
	    for (i = 0; i < panels.length; i++)
	    {
	        if ($('#panel_'+i) != null)
	        {
	            if (i == selectedIndex)
	            {
	                $("#panel_"+i).css('opacity','1.0');
	                $("#panel_"+i).css('display','block');
	            }
	            else
	            {
	                $("#panel_"+i).css('opacity','0.0');
	                $("#panel_"+i).css('display','none');
	            }
	        }
	    }
	
	    phpimageeditor_textpos_activator(selectedIndex);
		phpimageeditor_crop_activator(selectedIndex);
	    phpimageeditor_resize_activator(selectedIndex);
	}
	
	function phpimageeditor_textpos_activator(selectedIndex)
	{
	    var panel = $('#panel');
	
	    var editimage = $("#editimage");
		var textposx = $("#textposx");
		var textposy = $("#textposy");
		var textposx_number = $("#textposx_number");
		var textposy_number = $("#textposy_number");
	
		$("#image").mousemove(function(e)
	    {
			if (parseInt(panel.val()) == MenuText)
			{
				var x = e.pageX - editimage[0].offsetLeft;
		    	var y = e.pageY - editimage[0].offsetTop;
		    	
		    	textposx_number.html("(" + x + ")");
		    	textposy_number.html("(" + y + ")");
			}
		});
	    
		$("#image").mouseleave(function(e)
	    {
			if (parseInt(panel.val()) == MenuText)
			{
		    	textposx_number.html("");
		    	textposy_number.html("");
			}
	    });
	    
		$("#image").click(function(e)
	    {
			if (parseInt(panel.val()) == MenuText)
			{
				var x = e.pageX - editimage[0].offsetLeft;
		    	var y = e.pageY - editimage[0].offsetTop;
		    	
		    	textposx.val(x);
		    	textposy.val(y);
			}
	    });
	}

	phpimageeditor_init();
});
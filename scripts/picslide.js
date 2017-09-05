var fxcount=0,startingSlide=0;
var fx_value = new Array('zoom','fade','blindX','blindY','blindZ','cover','curtainX','curtainY','fadeZoom','growX','growY','none','scrollUp','scrollDown','scrollLeft','scrollRight','scrollHorz','scrollVert','shuffle','slideX','slideY','toss','turnUp','turnDown','turnLeft','turnRight','uncover','wipe');
var backforward=0;
$(document).ready(function() {
	if(pictureCount>1) {
		$('#slideshow').cycle({
			fx: 'zoom', // choose your transition type, ex: fade, scrollUp, shuffle,scrollLeft,zoom etc...
			startingSlide: startingSlide,
			after:   onAfter
		});
	}
	else document.getElementById('stop').style.display = "none";
});
	function start() {
		if(document.getElementById('backward')) document.getElementById('backward').style.display = "none";
		if(document.getElementById('forward')) document.getElementById('forward').style.display = "none";
		if(document.getElementById('play')) document.getElementById('play').style.display = "none";
		if(document.getElementById('stop')) document.getElementById('stop').style.display = "inline-block";
	
		$('#slideshow').cycle('stop').remove();
        $('#show').append(markup);
		$('#slideshow').cycle({
			fx: fx,
			startingSlide: startingSlide,
			speed:  2000,
			timeout: 5000,
			pause:         1,		
			pauseOnPagerHover: 1,
			after: onAfter,
			delay:  -4000
		});
	}
	function backforward2() {
		if(backforward==1) {
			backforward=0;
			backforward3();
		}
	}	
	function backforward3() {
		$('#slideshow').cycle('stop').remove();
        $('#show').append(markup);
		$('#slideshow').cycle({
			startingSlide: startingSlide,
			fx:     'fade',
			speed:  'fast',
			timeout: 0,
			next:   '#next2',
			prev:   '#prev2'
		});
		backforward=0;
	}
	function onAfter(curr,next,opts) {
		startingSlide++;
		if((opts.currSlide+1) >= opts.slideCount){
			startingSlide=0;
			fxcount++;
			fx=fx_value[fxcount];
			if(fxcount>28) fxcount=0; 
			start();
		}			
	};
	function stop_show() {
		$('#slideshow').cycle('stop');
		document.getElementById('backward').style.display = "inline-block";
		document.getElementById('forward').style.display = "inline-block";
		document.getElementById('play').style.display = "inline-block";
		document.getElementById('stop').style.display = "none";
		backforward=1;
		//backforward2();
	};
	function start_show() {
		fx=fx_value[fxcount];
		start();
	};	
function Action(url,affect_id,videoID,video_name) {
	video_id=videoID;
	document.getElementById("video_id").value=video_id;
	document.getElementById("video_name").value=video_name;
	var id = "#"+affect_id;
	$(document).ready(function() {
	   $(id).load(url);
	   $.ajaxSetup({ cache: false });
	});
	CommentList3('first',video_id,1,1);
}


if(Videocount<=Videopage_rows) {
	if(document.getElementById('VideoNav')) document.getElementById('VideoNav').style.display = "none";
	if(document.getElementById('Videofirst')) document.getElementById('Videofirst').style.display = "none";
	if(document.getElementById('Videoprevious')) document.getElementById('Videoprevious').style.display = "none";
	if(document.getElementById('Videonext')) document.getElementById('Videonext').style.display = "none";
	if(document.getElementById('Videolast')) document.getElementById('Videolast').style.display = "none";
}
if(VideoComcount<=VideoCompage_rows) {
	if(document.getElementById('VideoComfirst')) document.getElementById('VideoComfirst').style.display = "none";
	if(document.getElementById('VideoComprevious')) document.getElementById('VideoComprevious').style.display = "none";
	if(document.getElementById('VideoComnext')) document.getElementById('VideoComnext').style.display = "none";
	if(document.getElementById('VideoComlast')) document.getElementById('VideoComlast').style.display = "none";
}
if(OwnerComcount<=Ownerpage_rows) {
	if(document.getElementById('OwnerComNav')) document.getElementById('OwnerComNav').style.display = "none";
	if(document.getElementById('Ownerfirst')) document.getElementById('Ownerfirst').style.display = "none";
	if(document.getElementById('Ownerprevious')) document.getElementById('Ownerprevious').style.display = "none";
	if(document.getElementById('Ownernext')) document.getElementById('Ownernext').style.display = "none";
	if(document.getElementById('Ownerlast')) document.getElementById('Ownerlast').style.display = "none";
}
if(PictureComcount<=Picturepage_rows) {
	if(document.getElementById('PicNav')) document.getElementById('PicNav').style.display = "none";
	if(document.getElementById('Picturefirst')) document.getElementById('Picturefirst').style.display = "none";
	if(document.getElementById('Pictureprevious')) document.getElementById('Pictureprevious').style.display = "none";
	if(document.getElementById('Picturenext')) document.getElementById('Picturenext').style.display = "none";
	if(document.getElementById('Picturelast')) document.getElementById('Picturelast').style.display = "none";
}
function VideoShow(videoID)  
{  
	VideoWidth=600;
	document.getElementById("OnShow").width=VideoWidth;
	document.getElementById("pictures").style.display="none";
	document.getElementById("PictureOwnerComment").style.display="none";
	document.getElementById("video_id").value=videoID;
	window.parent.scroll(0,0);
}
function CommentList(require,FriendID,type)  
{  
	if(require=='first') Ownerpagenum=1;
	else if(require=='previous') {
	  Ownerpagenum = Ownerpagenum - 1;
	} 
	else if(require=='next') {
	  Ownerpagenum = Ownerpagenum + 1;
	} 
	else if(require=='last') Ownerpagenum=Ownerlast;

	if(Ownerpagenum > 1 && Ownerpagenum < Ownerlast) {
		document.getElementById('Ownerfirst').src = "/images/first_up.png";
		document.getElementById('Ownerprevious').src = "/images/previous_up.png";
		document.getElementById('Ownernext').src = "/images/next_up.png";
		document.getElementById('Ownerlast').src = "/images/last_up.png";
	}
	else if(Ownerpagenum<=1) {
		Ownerpagenum = 1;
		document.getElementById('Ownerfirst').src = "/images/first_up2.png";
		document.getElementById('Ownerprevious').src = "/images/previous_up2.png";
		document.getElementById('Ownernext').src = "/images/next_up.png";
		document.getElementById('Ownerlast').src = "/images/last_up.png";
	}
	 else if(Ownerpagenum>=Ownerlast) {
		Ownerpagenum = Ownerlast;
		document.getElementById('Ownerfirst').src = "/images/first_up.png";
		document.getElementById('Ownerprevious').src = "/images/previous_up.png";
		document.getElementById('Ownernext').src = "/images/next_up2.png";
		document.getElementById('Ownerlast').src = "/images/last_up2.png";
	 }	
	var url = base_url+'CommentList?FriendID='+FriendID+'&viewer_id='+viewer_id+'&pagenum='+Ownerpagenum+'&type='+type;
	$(document).ready(function() {
	   $("#comments").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function CommentList2(require,upload_id,type)  
{  
	if(require=='first') Picturepagenum=1;
	else if(require=='previous') {
	  Picturepagenum = Picturepagenum - 1;
	} 
	else if(require=='next') {
	  Picturepagenum = Picturepagenum + 1;
	} 
	else if(require=='last') Picturepagenum=Picturelast;
	if(Picturepagenum > 1 && Picturepagenum < Picturelast) {
		document.getElementById('Picturefirst').src = "/images/first_up.png";
		document.getElementById('Pictureprevious').src = "/images/previous_up.png";
		document.getElementById('Picturenext').src = "/images/next_up.png";
		document.getElementById('Picturelast').src = "/images/last_up.png";
	}
	else if(Picturepagenum<=1) {
		Picturepagenum = 1;
		document.getElementById('Picturefirst').src = "/images/first_up2.png";
		document.getElementById('Pictureprevious').src = "/images/previous_up2.png";
		document.getElementById('Picturenext').src = "/images/next_up.png";
		document.getElementById('Picturelast').src = "/images/last_up.png";
	}
	 else if(Picturepagenum>=Picturelast) {
		Picturepagenum = Picturelast;
		document.getElementById('Picturefirst').src = "/images/first_up.png";
		document.getElementById('Pictureprevious').src = "/images/previous_up.png";
		document.getElementById('Picturenext').src = "/images/next_up2.png";
		document.getElementById('Picturelast').src = "/images/last_up2.png";
	 }
	var url = base_url+'CommentList2?upload_id='+upload_id+'&viewer_id='+viewer_id+'&pagenum='+Picturepagenum+'&type='+type;
	$(document).ready(function() {
	   $("#CurrComment").load(url);
	   $.ajaxSetup({ cache: false });
	});		
}
function CommentList3(require,upload_id,type,reset)  
{  
	if(require=='first') VideoCompagenum=1;
	else if(require=='previous') {
	  VideoCompagenum = VideoCompagenum - 1;
	} 
	else if(require=='next') {
	  VideoCompagenum = VideoCompagenum + 1;
	} 
	else if(require=='last') VideoCompagenum=VideoComlast;
	if(VideoCompagenum > 1 && VideoCompagenum < VideoComlast) {
		if(document.getElementById('VideoComfirst')) document.getElementById('VideoComfirst').src = "/images/first_up.png";
		if(document.getElementById('VideoComprevious')) document.getElementById('VideoComprevious').src = "/images/previous_up.png";
		if(document.getElementById('VideoComnext')) document.getElementById('VideoComnext').src = "/images/next_up.png";
		if(document.getElementById('VideoComlast')) document.getElementById('VideoComlast').src = "/images/last_up.png";
	}
	else if(VideoCompagenum<=1) {
		VideoCompagenum = 1;
		if(document.getElementById('VideoComfirst')) document.getElementById('VideoComfirst').src = "/images/first_up2.png";
		if(document.getElementById('VideoComprevious')) document.getElementById('VideoComprevious').src = "/images/previous_up2.png";
		if(document.getElementById('VideoComnext')) document.getElementById('VideoComnext').src = "/images/next_up.png";
		if(document.getElementById('VideoComlast')) document.getElementById('VideoComlast').src = "/images/last_up.png";
	}
	 else if(VideoCompagenum>=VideoComlast) {
		VideoCompagenum = VideoComlast;
		if(document.getElementById('VideoComfirst')) document.getElementById('VideoComfirst').src = "/images/first_up.png";
		if(document.getElementById('VideoComprevious')) document.getElementById('VideoComprevious').src = "/images/previous_up.png";
		if(document.getElementById('VideoComnext')) document.getElementById('VideoComnext').src = "/images/next_up2.png";
		if(document.getElementById('VideoComlast')) document.getElementById('VideoComlast').src = "/images/last_up2.png";
	 }

	 if(reset==0) {	
		 var url = base_url+'CommentList2?upload_id='+upload_id+'&viewer_id='+viewer_id+'&pagenum='+VideoCompagenum+'&type='+type;
		$(document).ready(function() {
		   $("#CurrVideoComment").load(url);
		   $.ajaxSetup({ cache: false });
		});		
	 }
	 else {
		var url = base_url+'CommentList3?upload_id='+upload_id+'&viewer_id='+viewer_id+'&pagenum='+VideoCompagenum+'&type='+type;
		$(document).ready(function() {
		   $("#VDComment").load(url);
		   $.ajaxSetup({ cache: false });
		});		
	 }
}
function VideoList(require,FriendID)  
{  
	if(require=='first') Videopagenum=0;
	else if(require=='previous') {
	  Videopagenum = Videopagenum - 1;
	} 
	else if(require=='next') {
	  Videopagenum = Videopagenum + 1;
	} 
	else if(require=='last') Videopagenum=Videolast;
	if(Videopagenum > 0 && Videopagenum < Videolast) {
		document.getElementById('Videofirst').src = "/images/first.png";
		document.getElementById('Videoprevious').src = "/images/previous.png";
		document.getElementById('Videonext').src = "/images/next1.png";
		document.getElementById('Videolast').src = "/images/last.png";
	}
	else if(Videopagenum<=0) {
		Videopagenum = 0;
		document.getElementById('Videofirst').src = "/images/first2.png";
		document.getElementById('Videoprevious').src = "/images/previous2.png";
		document.getElementById('Videonext').src = "/images/next1.png";
		document.getElementById('Videolast').src = "/images/last.png";
	}
	 else if(Videopagenum>=Videolast) {
		Videopagenum = Videolast;
		document.getElementById('Videofirst').src = "/images/first.png";
		document.getElementById('Videoprevious').src = "/images/previous.png";
		document.getElementById('Videonext').src = "/images/next2.png";
		document.getElementById('Videolast').src = "/images/last2.png";
	 }		

	var OnShow = document.getElementById('OnShow');
	document.getElementById('mp4').src = video[Videopagenum]+"mp4";	
	document.getElementById('ogg').src = video[Videopagenum]+"ogg";	
	document.getElementById('ogv').src = video[Videopagenum]+"ogv";	
	document.getElementById('webm').src = video[Videopagenum]+"webm";	
	document.getElementById('mp4video').src = video[Videopagenum]+"mp4video.mp4";	
	document.getElementById('theora').src = video[Videopagenum]+"theora.ogv";	
	document.getElementById('webmvp8').src = video[Videopagenum]+"webmvp8.webm";	
	document.getElementById('object').src = video[Videopagenum]+"swf";	
	document.getElementById('embed').src = video[Videopagenum]+"swf";	
	OnShow.load();
}

$("#backward").attr('title', 'Previous'); 
$("#play").attr('title', 'Play'); 
$("#to_view").attr('title', 'View'); 
$("#stop").attr('title', 'Stop'); 
$("#forward").attr('title', 'Next'); 
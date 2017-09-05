<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPFv2FygupVsAsQeJPpfOU1ilSJBAXKTs&sensor=true">
    </script>
    <script type="text/javascript">
		var map;
		function initialize() {
		  var myLatlng = new google.maps.LatLng(-25.363882,131.044922);
		  var mapOptions = {
			zoom: 4,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		  }

		  map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

		  // Add 5 markers to the map at random locations.
		  var southWest = new google.maps.LatLng(-31.203405,125.244141);
		  var northEast = new google.maps.LatLng(-25.363882,131.044922);
		  var bounds = new google.maps.LatLngBounds(southWest,northEast);
		  map.fitBounds(bounds);
		  var lngSpan = northEast.lng() - southWest.lng();
		  var latSpan = northEast.lat() - southWest.lat();
		  for (var i = 0; i < 5; i++) {
			var location = new google.maps.LatLng(southWest.lat() + latSpan * Math.random(),
				southWest.lng() + lngSpan * Math.random());
			var marker = new google.maps.Marker({
				position: location,
				map: map
			});
			var j = i + 1;
			marker.setTitle(j.toString());
			attachSecretMessage(marker, i);
		  }
		}

		// The five markers show a secret message when clicked
		// but that message is not within the marker's instance data.
		function attachSecretMessage(marker, number) {
		  var message = ["This","is","the","secret","message"];
		  var infowindow = new google.maps.InfoWindow(
			  { content: message[number],
				size: new google.maps.Size(50,50)
			  });
		  google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		  });
		}
    </script>
  </head>
  <body onload="initialize()">
    <div id="map_canvas" style="width:100%; height:100%"></div>
  </body>
</html>
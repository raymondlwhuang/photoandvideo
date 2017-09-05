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
		  var mapOptions = {
			zoom: 4,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		  }

		  map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

			var country = "Germany";
			var geocoder;
			geocoder = new google.maps.Geocoder();
			geocoder.geocode( {'address' : country}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
				}
			});			  
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
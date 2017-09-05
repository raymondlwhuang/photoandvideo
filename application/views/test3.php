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
var geocoder = new google.maps.Geocoder();

function getState(zipcode) {
    geocoder.geocode( { 'address': zipcode}, function (result, status) {
        var state = "N/A";
        for (var component in result[0]['address_components']) {
            for (var i in result[0]['address_components'][component]['types']) {
                if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1") {
                    state = result[0]['address_components'][component]['short_name'];
                    // do stuff with the state here!
                    document.getElementById('state').innerHTML = state;
                    return;
                }
            }
        }
    });
}
    </script>
  </head>
  <body >
 <p style="font-size: 125%; line-height: 25px">City/Zip: <input id="zip" type="text"> <input onclick="javascript:getState(document.getElementById('zip').value);" type="submit" value="Get State!"><br> State:    <span id="state" style="font-weight: bold"></span></p>	
  </body>
</html>
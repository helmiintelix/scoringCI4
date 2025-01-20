$("#map").css("height", "70vh");

var initMap = function () {
  var bounds = new google.maps.LatLngBounds();
  var infowindow = new google.maps.InfoWindow();

  var mapOptions = {
    zoom: 15,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    //mapTypeControl: false
  };

  map = new google.maps.Map(document.getElementById("map"), mapOptions);

  console.log(pos);

  for (i = 0; i < loc; i++) {
    var lat = pos[i].latitude;
    var lng = pos[i].longitude;
    console.log(lat);
    console.log(lng);
    console.log(pos[i].agent_id);
    if (typeof pos[i].debitur !== "undefined") {
      var log = pos[i].agent_id;
    } else {
      if (pos[i].agent_id !== "") {
        var log = pos[i].agent_id + " at " + pos[i].time;
      } else {
        var log = "Address type: " + pos[i].time;
      }
    }

    var icon = "testing";
    var no_rekening = "087813988";

    if (i == 0) {
      var geolocate;

      if (!geolocate) {
        geolocate = new google.maps.LatLng(lat, lng);

        map.setCenter(geolocate);
      }
    }

    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(lat, lng),
      map: map,
      // title: 'Click Me ' + i
      icon: {
        url: "assets/img/" + icon + ".png",
        scaledSize: new google.maps.Size(20, 20), // scaled size
        origin: new google.maps.Point(0, 0), // origin
        anchor: new google.maps.Point(0, 0), // anchor
      },
    });
    bounds.extend(marker.position);
    if (no_rekening != "") var info = log;
    else var info = log;

    // process multiple info windows
    (function (marker, i) {
      // add click event
      google.maps.event.addListener(marker, "click", function () {
        infowindow = new google.maps.InfoWindow({
          content: info,
        });
        infowindow.open(map, marker);
      });
    })(marker, i);

    google.maps.event.trigger(marker, "click");
  }
  map.fitBounds(bounds);
};

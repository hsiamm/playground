var map;

function initialize() {
    
    var ascc_style = [ {
        featureType: "landscape", 
        elementType: "all", 
        stylers: [ {
            visibility: "on"
        }, {
            saturation: -90
        }, {
            hue: "#a4a69d"
        }, {
            lightness: 0
        } ]
    },{
        featureType: "road.highway", 
        elementType: "geometry", 
        stylers: [ {
            visibility: "simplified"
        }, {
            hue: "#355271"
        }, {
            saturation: -28
        }, {
            lightness: -66
        } ]
    },{
        featureType: "road.highway", 
        elementType: "labels", 
        stylers: [ {
            hue: "#355271"
        }, {
            visibility: "simplified"
        }, {
            saturation: 100
        } ]
    },{
        featureType: "road.arterial", 
        elementType: "geometry", 
        stylers: [ {
            visibility: "on"
        }, {
            hue: "#e9e7d1"
        }, {
            saturation: -30
        }, {
            lightness: 54
        } ]
    },{
        featureType: "road.arterial", 
        elementType: "labels", 
        stylers: [ {
            visibility: "on"
        }, {
            hue: "#e9e7d1"
        }, {
            saturation: -100
        }, {
            lightness: 0
        } ]
    },{
        featureType: "poi", 
        elementType: "all", 
        stylers: [ {
            visibility: "off"
        } ]
    },{
        featureType: "administrative.neighborhood", 
        elementType: "all", 
        stylers: [ {
            visibility: "simplified"
        } ]
    },{
        featureType: "water", 
        elementType: "all", 
        stylers: [ {
            visibility: "simplified"
        } ]
    } ]; 
    
    //Map Styles 
    var styledMapOptions = {
        name: "ASCC"
    }

    var asccMapType = new google.maps.StyledMapType(
        ascc_style, styledMapOptions);
    
    var myOptions = {
        zoom: 12,
        center: new google.maps.LatLng(30.31006007422863, -97.70605089765621),
        panControl: false,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'ascc']
        }
    };
    
    map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
    
    map.mapTypes.set('ascc', asccMapType);
    map.setMapTypeId('ascc');
    
    /* Markers */
    var austin_high = new google.maps.LatLng(30.272142, -97.764577);
    var austin_high_image = '/images/downtown-newmarker1.png';
    var st_johns = new google.maps.LatLng(30.3340649, -97.7067932);
    var st_johns_image = '/images/stjohn-newmarker1.png';
    var west_campus = new google.maps.LatLng(30.286642, -97.773685);
    var west_campus_image = '/images/west-newmarker1.png';
    
    var austin_high_marker = new google.maps.Marker({
        position: austin_high, 
        map: map, 
        title:"Austin High Campus",
        icon: austin_high_image
    }); 
    
    var st_johns_marker = new google.maps.Marker({
        position: st_johns, 
        map: map, 
        title:"St John's Campus",
        icon: st_johns_image
    }); 
    
    var west_campus_marker = new google.maps.Marker({
        position: west_campus, 
        map: map, 
        title:"West Campus",
        icon: west_campus_image
    });
}

function handleNoGeolocation(errorFlag) {
    if (errorFlag) {
        var content = 'Error: The Geolocation service failed.';
    } else {
        var content = 'Error: Your browser doesn\'t support geolocation.';
    }
    
    var options = {
        map: map,
        position: new google.maps.LatLng(60, 105),
        content: content
    };

    var infowindow = new google.maps.InfoWindow(options);
    map.setCenter(options.position);
}

function pan() {
    var center = new google.maps.LatLng(30.315246813076392, -97.6106071720702);
    map.panTo(center);
    map.setZoom(12);
}

//google.maps.event.addDomListener(window, 'load', initialize);

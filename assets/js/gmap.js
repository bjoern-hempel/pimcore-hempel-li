const loadGoogleMapsApi = require('load-google-maps-api');

loadGoogleMapsApi({key: 'AIzaSyDYv-yqfJxpfpZbkT1qve_txGdTfrKO6Fg'}).then(function (googleMaps) {

    /** @type {!Array} */
    let styles = [
        {
            stylers : [{
                saturation : -100
            }]
        },
        {
            featureType : "road",
            elementType : "geometry",
            stylers : [
                {
                    hue : "#74b7b0"
                }, {
                    visibility : "simplified"
                }
            ]
        },
        {
            featureType : "road",
            elementType : "labels",
            stylers : [{
                visibility : "on"
            }]
        }
    ];

    var lat = 51.060927;
    var lng = 13.740781;

    let styledMap = new googleMaps.StyledMapType(styles, {
        name : "Styled Map"
    });

    let map = new googleMaps.Map(document.getElementById('block-map'), {
        center: {
            lat: lat,
            lng: lng
        },
        zoom: 14,
        scrollwheel : false,
        disableDefaultUI : true
    });
    let oneLatLng = new googleMaps.LatLng(lat, lng);
    new googleMaps.Marker({
        position : oneLatLng,
        map : map,
        icon : {
            url : "images/marker.png",
            scaledSize : new googleMaps.Size(45, 45)
        }
    });

    map.mapTypes.set("map_style", styledMap);
    map.setMapTypeId("map_style");
}).catch(function (error) {
    console.error(error)
});
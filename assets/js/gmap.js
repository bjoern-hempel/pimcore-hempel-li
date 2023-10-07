/**
 * Load Google Maps API
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-10-07)
 * @since 0.1.0 (2023-10-07) First version.
 */
const loadGoogleMapsApi = require('load-google-maps-api');

loadGoogleMapsApi({key: 'AIzaSyDYv-yqfJxpfpZbkT1qve_txGdTfrKO6Fg'}).then(function (googleMaps) {

    let blockMap = document.getElementById('block-map');

    /* Check if element block-map exists. */
    if (blockMap === null) {
        return;
    }

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

    let lat = 51.060927;
    let lng = 13.740781;

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
    console.error('Unable to load the Google Maps API: "' + error + '"');
});

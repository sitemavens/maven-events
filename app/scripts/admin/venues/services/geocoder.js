var app = angular.module('mavenEventsApp');

app.service('geocoder',function() {
            this.geocode=function(address, outerCallback) {
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode( { 'address': address}, function(results, status) {
                            console.log(results);
                            if (status == google.maps.GeocoderStatus.OK) {
                                    outerCallback({success: true, err: null, results: results});
                            } else {
                                    outerCallback({success:false, err: new Error('Geocode was not successful for the following reason: ' + status), results: null});
                            }
                    });
            };
    });
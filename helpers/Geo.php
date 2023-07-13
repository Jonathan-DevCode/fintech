<?php

//Filter::pre( Geo::getLatLonCep("11701-090") );exit;
//<img src="'.Geo::getMapCep("11701-090")['map'] . '" />

Class Geo {

    static function getLatLon($address) {
        $url = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($address) . "&sensor=false&region=Brazil";
        $json = file_get_contents($url);
        $json = json_decode($json);
        if (isset($json->status) && $json->status == "OK") {
            $lat = $json->results[0]->geometry->location->lat;
            $lon = $json->results[0]->geometry->location->lng;
            return array('lat' => $lat, 'lon' => $lon, 'json' => "{\"lat\":\"$lat\",\"lon\":\"$lon\"}");
        } else {
            return array('lat' => '', 'lon' => '', 'json' => '');
        }
    }



    static function getCity($lat, $lon, $withState = false, $separator = '-') {
        $url_geo = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lon}&sensor=false";
        $geocode = file_get_contents($url_geo);
        $output = json_decode($geocode);
        $city = "";
        $state = "";
        if (isset($output->results[0])) {
            foreach ($output->results[0]->address_components as $gg) {
                if ($withState === true) {
                    if (in_array("administrative_area_level_1", $gg->types)) {
                        $state = $gg->short_name;
                    }
                }
                if (in_array("administrative_area_level_2", $gg->types)) {
                    $city = $gg->long_name;
                }
            }
        }
        if ($withState === true) {
            return "$city{$separator}$state";
        } else {
            return $city;
        }
    }

    static function geoReverse($lat,$lon) {
        $url_geo = "http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lon}&sensor=false";
        $geocode = file_get_contents($url_geo);
        $output = json_decode($geocode);
        if (isset($output->results[0])) {
            return $output->results[0];
        } else {
            return false;
        }
    }

    static function getLatLonCep($zip) {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($zip) . "&sensor=false&key=AIzaSyAUbm2zFMtcoPNI2ixx9E122Ub-B-gRMeY";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        return array('lat' => $result3[0]['lat'], 'lon' => $result3[0]['lng']);
    }

    static function getMapCep($zip, $w = 800, $h = 500, $type = 'satellite') { //sattelite OR hybrid
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($zip) . "&sensor=false&key=AIzaSyAUbm2zFMtcoPNI2ixx9E122Ub-B-gRMeY";
        $result_string = file_get_contents($url);
        $result = json_decode($result_string, true);
        $result1[] = $result['results'][0];
        $result2[] = $result1[0]['geometry'];
        $result3[] = $result2[0]['location'];
        $url = "https://maps.googleapis.com/maps/api/staticmap?center=" . $result3[0]['lat'] . "," . $result3[0]['lng'] . "&zoom=12&size={$w}x{$h}&maptype=$type&markers=$lat,$lon&sensor=false&key=AIzaSyAUbm2zFMtcoPNI2ixx9E122Ub-B-gRMeY";
        return array('lat' => $result3[0]['lat'], 'lon' => $result3[0]['lng'], 'map' => $url);
    }

    static function getMapLatLon($lat,$lon, $w = 500, $h = 500, $type = 'satellite') { //sattelite OR hybrid
        $url = "https://maps.googleapis.com/maps/api/staticmap?center=" . $lat . "," . $lon . "&zoom=16&size={$w}x{$h}&maptype=$type&markers=$lat,$lon&sensor=false&key=AIzaSyAUbm2zFMtcoPNI2ixx9E122Ub-B-gRMeY";
        return  $url;
    }

    // captura coordenada lat/lon de arquivos com o exif_read_data
    static function gps($coordenada, $hemisfero){
        for ($i = 0; $i < 3; $i ++){
            $part = explode('/', $coordenada[$i]);
            if (count ($part) == 1) {
                $coordenada [$i] = $part[0];
            }
            else if (count($part) == 2){
                $coordenada[$i] = floatval($part[0])/ floatval($part[1]);
            }
            else{
                $coordenada[$i] = '';
            }
        }
        list($degrees, $minutes, $seconds) = $coordenada;
        $sign = ($hemisfero == 'W' || $hemisfero == 'S') ? -1 : 1;
        $data = $sign * ($degrees + $minutes / 60 + $seconds / 3600);
        return str_replace(',', '.', $data);
    }

}

<?php
/*
    WISPBill a PHP based ISP billing platform
    Copyright (C) 2015  Turtles2

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

	@turtles2 on ubiquiti community, DSLReports and Netonix 
 */
function geocode($add, $city, $state, $zip){
	/* HTML Query String Parameters */
	
	$options = array
	(
		'street' => "$add",
		'city' => "$city",
		'state' => "$state",
		'zip' => "$zip",
		'benchmark' => 'Public_AR_Current',
		'format' => 'json'
	);
	/* Build a URL */
	
	$url = 'http://geocoding.geo.census.gov/geocoder/locations/address?'.http_build_query($options);
	/* Run query using cURL */
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);

    $geo = json_decode($result, true);
     $data= $geo["result"];
        $data2= $data["addressMatches"];
        if(empty($data2)){
            $gps ='No Match';
            return $gps;
        }else{
            // We have a Match
            $data3= $data2["0"];
        $data4= $data3["coordinates"];
        $lon= $data4["x"];
        $lat= $data4["y"];
        $gps = array(
    "lat" => "$lat",
    "lon" => "$lon",
);
        return $gps;
        }
}
?>

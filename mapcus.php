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
require_once('./session.php');
require_once('./fileloader.php');
?>
<html>
<head>
  <link rel="stylesheet" href="leaflet/leaflet.css"/>
  <script src="leaflet/leaflet.js"></script>
  <style>
    #map{ width: 900px; height: 500px; }
  </style>
</head>
<body>

  <div id="map"></div>

  <script>

  // initialize the map
  <?php
  echo "
  var map = L.map('map').setView([$mapcenterlat, $mapcenterlon], $mapzoom);";
?>
  // load a tile layer
  L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      
    }).addTo(map);

<?php
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE
                             `idcustomer_users` is not NULL
                             and `idcustomer_plans` is not NULL")) {
      /* fetch associative array */
    
    while ($row = $result->fetch_assoc()) {
     $lat= $row["lat"];
     $lon = $row["lon"];
     $email = $row["email"];
     $phone = $row["phone"];
     $fname = $row["fname"];
     $lname = $row["lname"];
     echo" 
       var marker = L.marker([$lat, $lon])
        .addTo(map)
            .bindPopup('Name: $fname $lname Email: $email Phone: $phone');
  ";
    }}
?>
  </script>
  <br><a href='index.php'>Back</a></br>
</body>
</html>
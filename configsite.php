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
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

$error = $_SESSION['exitcodev2'];
echo "<h1>$error</h1>";
$_SESSION['exitcodev2'] ='';

if ($result = $mysqli->query("SELECT * FROM `devices` WHERE `type` = 'router'
                             and `location_idlocation`
                             is NULL and `librenms_id` is not NUll")) {
      /* fetch associative array */
      echo'<h1>All Devices Will Be Reconfigured LibreNMS is needed for this config</h1>
      <h1>Select the Sites Router</h1>
      <form action="configsite2.php"method="post">
      <table border="1" style="width:100%">
      <tr>
    <td>Select</td>
    <td>Name</td> 
    <td>Mac Address</td>
    <td>Serial Number</td> 
    <td>Model</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $id= $row["iddevices"];
     $name= $row["name"];
     $mac= $row["mac"];
     $sn= $row["serial_number"];
     $model= $row["model"];
     echo" <tr>
    <td><input type='radio' name='id' value=$id unchecked></td>
    <td>$name</td> 
    <td>$mac</td>
    <td>$sn</td> 
    <td>$model</td>
  </tr>
  ";
    }
}
if ($result = $mysqli->query("SELECT * FROM `devices` WHERE `type`
                             != 'router' and `location_idlocation`
                             is NULL and `librenms_id` is not NUll
                             and `type` = 'ap' ")) {
      /* fetch associative array */
      echo'</table>
              <br></br>
      <h1>Select the Sites Access Points</h1>
      <form action="configsite2.php"method="post">
      <table border="1" style="width:100%">
      <tr>
    <td>Select</td>
    <td>Name</td> 
    <td>Mac Address</td>
    <td>Serial Number</td> 
    <td>Model</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $id= $row["iddevices"];
     $name= $row["name"];
     $mac= $row["mac"];
     $sn= $row["serial_number"];
     $model= $row["model"];
     echo" <tr>
    <td><input type='checkbox' name='id3[]' value=$id unchecked></td>
    <td>$name</td> 
    <td>$mac</td>
    <td>$sn</td> 
    <td>$model</td>
    </tr>    
";
    }
}
if ($result = $mysqli->query("SELECT * FROM `location` WHERE `ifconfig` = 'no'")) {
      /* fetch associative array */
      echo'

  </table>
              <br></br>
      <h1>Select the Site</h1>
      <table border="1" style="width:100%">
      <tr>
    <td>Select</td>
    <td>Name</td> 
    <td>Latitude</td>
    <td>Longitude</td> 
    <td>Type</td>
  </tr>
  ';
              
    while ($row = $result->fetch_assoc()) {
     $id2= $row["idlocation"];
     $name2= $row["name"];
     $lat= $row["latitude"];
     $lon= $row["longitude"];
     $type= $row["type"];
     echo" <tr>
    <td><input type='radio' name='id2' value=$id2 unchecked></td>
    <td>$name2</td> 
    <td>$lat</td>
    <td>$lon</td> 
    <td>$type</td>
  </tr>";
    }
}
    
    echo' </table>
              <br></br>             
  	            <br><button type="submit">
  	                Next Step
  	            </button></br>   
  	    </fieldset>
  	    </form>';
?>
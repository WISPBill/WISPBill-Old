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
$mysqlil = new mysqli("$ipl", "$usernamel", "$passwordl", "$dbl");
// start of post
$id= $_POST["id"];
if (empty($id)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'dev';
    header('Location: linkdevice.php');
    exit;
} else {
    // Nothing
}

$id = inputcleaner($id,$mysqli);

if ($result = $mysqli->query("SELECT * FROM `devices` WHERE `iddevices` = $id")) {
      /* fetch associative array */
      
    while ($row = $result->fetch_assoc()) {
    $mac= $row["mac"];
     }
     }else{
        echo'Error with DB';
     }
     // Put in right format
     $mac = strtolower($mac);
     $mac = str_replace(":","",$mac);

if ($result = $mysqlil->query("SELECT * FROM `ports` WHERE `ifPhysAddress` = '$mac'")) {
      /* fetch associative array */
      
    while ($row = $result->fetch_assoc()) {
  $lid= $row["device_id"];
    }
    }else{
        echo'Error with DB';
    }
if ($result = $mysqli->query("UPDATE `$db`.`devices` SET `librenms_id` = '$lid' WHERE `devices`.`iddevices` = $id;")) {
      /* fetch associative array */

    }else{
        echo'Error with DB';
    }
     header('Location: index.php');
?>
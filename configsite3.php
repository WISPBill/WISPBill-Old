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
//Router
$id= $_SESSION['id'];
//Site
$id2= $_SESSION['id2'];
//AP
$id3 = $_SESSION['id3'];

if ($result = $mysqli->query("SELECT * FROM `devices` WHERE `iddevices` = $id")) {
      /* fetch associative array */
     
    while ($row = $result->fetch_assoc()) {
     $nms= $row["librenms_id"];
    }
}

if ($result2 = $mysqlil->query("SELECT * FROM `ports` WHERE `device_id` = $nms")) {
  
        while ($row = $result2->fetch_assoc()) {
     $port = $row["port_id"];
     $use = $_POST["$port"];
     
     $use = inputcleaner($use,$mysqli);

     
    if ($result = $mysqli->query("INSERT INTO `$db`.`device_ports`
                                 (`iddevice_ports`, `port id`, `use`,
                                 `devices_iddevices`) VALUES
                                 (NULL, '$port', '$use', '$id');")) {

}
    }    
}

foreach($id3 as $ap){
 if ($result3 = $mysqli->query("UPDATE
                              `$db`.`devices` SET `location_idlocation` = '$id2',
                              `field_status` = 'tower' WHERE `devices`.`iddevices` = $ap;")) {

}
}
 if ($result4 = $mysqli->query("UPDATE
                              `$db`.`devices` SET `location_idlocation` = '$id2',
                              `field_status` = 'tower' WHERE `devices`.`iddevices` = $id;")){

}
 
if ($result5 = $mysqli->query("UPDATE `$db`.`location`
                             SET `ifconfig` = 'yes' WHERE `location`.`idlocation` = $id2;
")) {

}
header('Location: index.php');
?>
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
	
    $site = $mysqli->real_escape_string($_GET['choice']);

    if ($result2 = $mysqli->query("SELECT * FROM `devices` WHERE `location_idlocation` = '$site'and `type` = 'router'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $did= $row2["iddevices"];
					 }
					 if ($result3 = $mysqli->query("SELECT * FROM `device_ports` WHERE `devices_iddevices` = '$did'")) {
				/* fetch associative array */
                 echo'<option value="" selected disabled>Please Select a Port</option>';
				 while ($row3 = $result3->fetch_assoc()) {
					 $name = $row3["name"];
                     $pid = $row3["port id"];
                     if ($result2 = $mysqlil->query("SELECT * FROM `ports` WHERE `port_id` = '$pid'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $name= $row2["ifDescr"];
					 }
                     if ($resultl = $mysqlil->query("SELECT * FROM `ipv4_addresses` WHERE `port_id` = '$pid'")) {
				/* fetch associative array */
				 while ($rowl = $resultl->fetch_assoc()) {
					 $routerip= $rowl["ipv4_address"];
					 }
                    echo"<option value=$pid>$routerip on port $name</option>";
                    $routerip = '';
					 }
                 }}
                     }
    }

?>
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
	
    $site = $_GET['choice'];
	
	$site = inputcleaner($site,$mysqli);

    if ($result2 = $mysqli->query("SELECT * FROM `devices` WHERE `location_idlocation` = '$site' and `type` = 'router'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $did= $row2["iddevices"];
					 }
					 if ($result3 = $mysqli->query("SELECT * FROM `device_ports` WHERE `use` = 'ap' and `devices_iddevices` = '$did'")) {
				/* fetch associative array */
                 echo'<option value="" selected disabled>Please Select a Port</option>';
				 while ($row3 = $result3->fetch_assoc()) {
					 $name = $row3["name"];
                     $pid = $row3["iddevice_ports"];
                     $routerip = $row3["ip_address"];
                     $mask = $row3["network"];
					 
                    echo"<option value=$pid>$routerip$mask on port $name</option>";
					 
                 }}
                     }
?>
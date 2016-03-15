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

	
    $cusinfoid = $_GET['choice'];
	
	$cusinfoid = inputcleaner($cusinfoid,$mysqli);

if(empty($cusinfoid)){
    //No id
    echo "No Data Available";
    exit;
}else{
    if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = '$cusinfoid'")) {
     while ($row = $result->fetch_assoc()) {
     $cusdevid= $row["devices_iddevices"];
     }
     }
     
     if ($result = $mysqli->query("SELECT * FROM `devices` WHERE `iddevices` = '$cusdevid'")) {
     while ($row = $result->fetch_assoc()) {
     $site= $row["location_idlocation"];
     $mac = $row["mac"];
     }
     }
     
     if ($result = $mysqli->query("SELECT * FROM  `devices` WHERE  `type` =  'router'
AND  `location_idlocation` =  '$site'")) {
     while ($row = $result->fetch_assoc()) {
     $routerid= $row["iddevices"];
     }
     }
     
     if ($result = $mysqli->query("SELECT * FROM `device_credentials` WHERE `devices_iddevices` = '$routerid'")) {
     while ($row = $result->fetch_assoc()) {
     $eusername= $row["username"];
     $epassword= $row["password"];
     $iv= $row["IV"];
     }
     }
     
     if ($result = $mysqli->query("SELECT * FROM `device_ports` WHERE `use` = 'mgmt' and `devices_iddevices` = '$routerid'")) {
     while ($row = $result->fetch_assoc()) {
     $routerip= $row["ip_address"];
     }
     }
     
     $rpass= mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$epassword","ofb","$iv");
	 $rname = mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$eusername","ofb","$iv");
						 
	 $mac = strtolower($mac);
	 $radioip = getdhcpip($routerip,$rname,$rpass,$mac);
     
     if ($radioip["error"] =='router error'){
		 // Could not SSH into router
		echo "<h4>Could not SSH into Router the IP is $routerip</h4>";						  			   
	 }elseif($radioip["mac"] == "$mac"){
      
      $radiosship = $radioip["ip"];
      
     $pinghost = ping("$radiosship", "$pingport", "$pingtimeout");
     
     if($pinghost == 'down'){
      echo "<h4>The Radio at $radiosship did not respond to ping</h4>";
     }else{
       echo "<h4>The Radio at $radiosship responded to ping it took $pinghost MS</h4>";
     }
     }
}
?>
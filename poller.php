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
require_once('./fileloader.php');
	 $mysqli = new mysqli("$ip", "$username", "$password", "$db");
	 $mysqlil = new mysqli("$ipl", "$usernamel", "$passwordl", "$dbl");
 if ($result = $mysqli->query("SELECT * FROM `devices` WHERE `type`
                              = 'cpe' and `field_status` = 'customer'")) {
    /* fetch associative array */
    foreach ($result as $row){
         $site = $row["location_idlocation"];
         $mac = $row["mac"];
		  $cpeid= $row["iddevices"];         
         if ($result2 = $mysqli->query("SELECT * FROM `devices` WHERE `location_idlocation` = '$site'and `type` = 'router'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $lid= $row2["librenms_id"];
					 $did= $row2["iddevices"];
					 }
					 if ($result3 = $mysqli->query("SELECT * FROM `device_ports` WHERE `use` = 'mgmt' and `devices_iddevices` = '$did'")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
					 $portid= $row3["port id"];
					 }
						 if ($resultl = $mysqlil->query("SELECT * FROM `ipv4_addresses` WHERE `port_id` = '$portid'")) {
				/* fetch associative array */
				 while ($rowl = $resultl->fetch_assoc()) {
					 $routerip= $rowl["ipv4_address"];
					 }
						 $radioip = getdhcpip($routerip,$rname,$rpass,$mac);
						 
						 if ($radioip["error"] =='router error'){
							  // Could not SSH into router
								  
								   if ($result5 = $mysqli->query("SELECT * FROM `notifications`
								   WHERE `readyn` = '0' and `content` = 'The Router at $routerip did not allow SSH on
								   Management VLAN IP. Reason Unknown. Some Stats were
								   not collected. ID of CPE AFFECTED = $cpeid'")) {
										if ($result5->num_rows == 1){
										// Error is open no need to make again
										
										} elseif ($result5->num_rows == 0){
										// No open error have to make one
											  if ($mysqli->query("INSERT INTO `$db`.
								   `notifications` (`idnotifications`, `readyn`,
								   `content`, `date`, `fromwho`, `towho`) VALUES (NULL,
								   '0', 'The Router at $routerip did not allow SSH on Management VLAN IP. Reason Unknown. Some Stats were not collected. ID of CPE AFFECTED = $cpeid', CURRENT_TIMESTAMP, 'system', 'all');")
								   === TRUE) {
								   //Will notify admins of error 
								   }
										}
										/* free result set */
										$result5->close();
								   }
						 }elseif($radioip["mac"] == "$mac"){
							  // Found IP
							  $radiosship = $radioip["ip"];
							  $stat = getAirOSstat("$radiosship", "$radiouname", "$radiopass");
							  if ($stat["error"] == 'none'){
								   // DATA entry and calc
								   $freq = $stat["frequency"];
								   $txpower = $stat["txPower"];
								   $signal =  $stat["signal"];
								   $noise = $stat["noise"];
								   $ccq = $stat["ccq"];
								   $latency = $stat["latency"];
								   $rxbytes = $stat["rxbtyes"];
								   $txbytes =  $stat["txbtyes"];
								   $time =  $stat["time"];
								   
								   if ($result4 = $mysqli->query("SELECT * FROM  `cpe_data`
								   WHERE  `devices_iddevices` =  '$cpeid' ORDER BY  `cpe_data`.`idcpedata` DESC LIMIT 0 , 1")) {
										/* fetch associative array */
										while ($row4 = $result4->fetch_assoc()) {
										$orxbytes= $row4["rxbtyes"];
										$otxbytes= $row4["txbtyes"];
										$otime = $row4["datetime"];
										}
										
								   if($rxbytes < $orxbytes){
										$orxbytes = '0';
								   } elseif($txbytes < $otxbytes){
										$otxbytes = '0';
								   }
								   //Time diffrence
								       $etime = $time - $otime;
								   //Get Bytes diffrence
								   $drx = $rxbytes - $orxbytes;
								   $dtx = $txbytes - $otxbytes;
								   //Bytes to bit
								   $rxbits = $drx*8;
								   $txbits = $dtx*8;
								   //Bit to bit per sec
								   $rxrate = $rxbits/$etime;
								   $txrate = $txbits/$etime;
							  
								   //DATE ENTRY
								   if ($mysqli->query("INSERT INTO `$db`.`cpe_data`
								   (`frequency`, `txpower`, `signallev`, `noise`, `ccq`,
								   `latency`, `rxbtyes`, `txbtyes`, `rxrate`, `txrate`,
								   `datetime`, `idcpedata`, `devices_iddevices`) VALUES
								   ('$freq', '$txpower', '$signal', '$noise', '$ccq', '$latency',
								   '$rxbytes', '$txbytes', '$rxrate', '$txrate', '$time', NULL, '$cpeid');")
								   === TRUE) {
								   // Nothing
								   }
								   if ($result5 = $mysqli->query("SELECT * FROM `notifications`
								   WHERE `readyn` = '0' and `content` = 'A Radio is offline ID of CPE AFFECTED = $cpeid'")) {
										if ($result5->num_rows == 1){
										// Error is open should be closed
										while ($row5 = $result5->fetch_assoc()) {
										$notiid= $row5["idnotifications"];
											 if ($mysqli->query("UPDATE `$db`.`notifications`
											 SET `readyn` = '1' WHERE `notifications`.`idnotifications` = '$notiid';")
								   === TRUE) {
								   // Nothing
								   }
										}
										} elseif ($result->num_rows == 0){
										// No open error nothing to do
										}
										/* free result set */
										$result5->close();
										}
								   }   
							  } elseif($stat["error"] == 'conerror'){
								   // Radio is offline
								   if ($result5 = $mysqli->query("SELECT * FROM `notifications`
								   WHERE `readyn` = '0' and `content` = 'A Radio is offline ID of CPE AFFECTED = $cpeid'")) {
										if ($result5->num_rows == 1){
										// Error is open no need to make again
										
										} elseif ($result5->num_rows == 0){
										// No open error have to make one
											 if ($mysqli->query("INSERT INTO `$db`.
								   `notifications` (`idnotifications`, `readyn`,
								   `content`, `date`, `fromwho`, `towho`) VALUES (NULL,
								   '0', 'A Radio is offline ID of CPE AFFECTED = $cpeid', CURRENT_TIMESTAMP, 'system', 'all');")
								   === TRUE) {
								   //Will notify admins of error 
								   }
										}
										/* free result set */
										$result5->close();
								   }
								   
							  }elseif($stat["error"] == 'autherror'){
								   // Radio has wrong auth info
								   
								   if ($result5 = $mysqli->query("SELECT * FROM `notifications`
								   WHERE `readyn` = '0' and `content` = 'A Radio has a ssh error it is online and did not allow login ID of CPE AFFECTED = $cpeid'")) {
										if ($result5->num_rows == 1){
										// Error is open no need to make again
										
										} elseif ($result5->num_rows == 0){
										// No open error have to make one
											 if ($mysqli->query("INSERT INTO `$db`.
								   `notifications` (`idnotifications`, `readyn`,
								   `content`, `date`, `fromwho`, `towho`) VALUES (NULL,
								   '0', 'A Radio has a ssh error it is online and did not allow login ID of CPE AFFECTED = $cpeid', CURRENT_TIMESTAMP, 'system', 'all');")
								   === TRUE) {
								   //Will notify admins of error 
								   }
										}
										/* free result set */
										$result5->close();
								   }
							  }elseif($stat["error"] == 'exeerror'){
								   // Radio had error with command
								   
								   if ($result5 = $mysqli->query("SELECT * FROM `notifications`
								   WHERE `readyn` = '0' and `content` = 'A Radio has a unknown ssh error it is online and did allow login ID of CPE AFFECTED = $cpeid'")) {
										if ($result5->num_rows == 1){
										// Error is open no need to make again
										
										} elseif ($result5->num_rows == 0){
										// No open error have to make one
											 if ($mysqli->query("INSERT INTO `$db`.
								   `notifications` (`idnotifications`, `readyn`,
								   `content`, `date`, `fromwho`, `towho`) VALUES (NULL,
								   '0', 'A Radio has a unknown ssh error it is online and did allow login ID of CPE AFFECTED = $cpeid', CURRENT_TIMESTAMP, 'system', 'all');")
								   === TRUE) {
								   //Will notify admins of error 
								   }
										}
										/* free result set */
										$result5->close();
								   }
							  }
						 }else{
							  //SSH works but IP not found
							  
								   if ($result5 = $mysqli->query("SELECT * FROM `notifications`
								   WHERE `readyn` = '0' and `content` = 'The Router at $routerip did not have IP lease data for a radio ID of CPE AFFECTED = $cpeid'")) {
										if ($result5->num_rows == 1){
										// Error is open no need to make again
										
										} elseif ($result5->num_rows == 0){
										// No open error have to make one
											 if ($mysqli->query("INSERT INTO `$db`.
								   `notifications` (`idnotifications`, `readyn`,
								   `content`, `date`, `fromwho`, `towho`) VALUES (NULL,
								   '0', 'The Router at $routerip did not have IP lease data for a radio ID of CPE AFFECTED = $cpeid', CURRENT_TIMESTAMP, 'system', 'all');")
								   === TRUE) {
								   //Will notify admins of error 
								   }
										}
										/* free result set */
										$result5->close();
								   }
						 }
						 }// end if
					 } // End of If	 
         } // End of If
    }// End of Loop
 } // End of IF 
?>
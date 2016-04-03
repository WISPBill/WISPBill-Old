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
$email = $_POST["email"];
$pin= $_POST["pin"];
$lid= $_POST["site"];

$workflow = $_POST["workflow"];

$workflow = inputcleaner($workflow,$mysqli);

// end of post

if($workflow == 'false'){
 // DO nothing
}else{
 
}

if (empty($id)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'dev';
    header('Location: linkcusdevice.php');
    exit;
}elseif (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: linkcusdevice.php');
    exit;
}elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pin';
    header('Location: linkcusdevice.php');
    exit;
} elseif(empty($lid)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'site';
    header('Location: linkcusdevice.php');
    exit;
}  else {
    // Nothing
}

$emailc = inputcleaner($email,$mysqli);
$pinc= inputcleaner($pin,$mysqli);
$site = inputcleaner($lid,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
         $_SESSION['exitcodev2'] = 'email';
    header('Location: linkcusdevice.php');
    exit;
  }
else{
  //do nothing 
  }


if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$emailc'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $uid= $row["idcustomer_users"];
     $iid= $row["customer_info_idcustomer_info"];
}
       /* free result set */
    $result2->close();
}// end if

$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
         if ($result = $mysqli->query("UPDATE `$db`.`customer_info`
                                      SET `devices_iddevices` = '$id'
                                      WHERE `customer_info`.`idcustomer_info` = $iid;")) {
 
         }// end if
         if ($result = $mysqli->query("UPDATE `$db`.`devices` SET
                                      `location_idlocation` = '$site',
                                      `field_status` = 'customer' WHERE
                                      `devices`.`iddevices` = $id;")) {
 
         }// end if
         
         if ($result14 = $mysqli->query("SELECT * FROM `devices` WHERE `iddevices` = '$id' AND  `manufacturer` =  'Ubiquiti Networks'")) {
    /* fetch associative array */
    $numsrows = $result14->num_rows;
    if ($numsrows == 0){
		if($workflow == 'false'){
header('Location: index.php');
exit;
}elseif($workflow == 'lead1C'){
 header('Location: activatecustomer.php?workflow=lead1D');
exit;
}else{
 echo "Workflow Error";
 exit;
}					
	 } elseif($numsrows == 1){
		   while ($row14 = $result14->fetch_assoc()) {
     $mac= $row14["mac"];
  
}
       /* free result set */
    $result14->close();
}// end if
         }
         
         $cpeid = $id;
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
                           $mac = strtolower($mac);
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
								   
								   //DATE ENTRY
								   if ($mysqli->query("INSERT INTO `$db`.`cpe_data`
								   (`frequency`, `txpower`, `signallev`, `noise`, `ccq`,
								   `latency`, `rxbtyes`, `txbtyes`, `rxrate`, `txrate`,
								   `datetime`, `idcpedata`, `devices_iddevices`) VALUES
								   ('$freq', '$txpower', '$signal', '$noise', '$ccq', '$latency',
								   '$rxbytes', '$txbytes', NULL, NULL, '$time', NULL, '$cpeid');")
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

}elseif($isuser === false){
       $_SESSION['exitcodev2']  = 'pin';
       header('linkcusdevice.php');
    exit;
}else{
  echo 'Error with userverify';
  exit;
}

if($workflow == 'false'){
header('Location: index.php');
exit;
}elseif($workflow == 'lead1C'){
 header('Location: activatecustomer.php?workflow=lead1D');

}else{
 echo "Workflow Error";
}

?>
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
 
  Timer this script is hard to time as each network and server is diffrent if it
 goes over 4 MIN and 30 SEC it will alert you so that a new one can be made I run this on a 5 MIN
 CHRON but as not stats are being collected you can run it at any interval you want
  */
 $time_start = microtime(true);
require_once('./fileloader.php');

	 $mysqli = new mysqli("$ip", "$username", "$password", "$db");

 if ($result = $mysqli->query("SELECT * FROM  `ip_networks` ")) {
    
    foreach ($result as $row){
      $network = $row["network"];
      $networkmask = $row["subnet"];
      $networkid = $row["idip_networks"];
	  
      exec("nmap -sn $network/$networkmask",$ping);
      
      $ping = implode("", $ping);
      
      // This will filter any non IPs from NMAP
      preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $ping, $match);
      
      // This will make an array of ips that are up
      $upips = $match[0];
  
	  // Start of adding new IPs to DB
	  $dbip = array(); // Fill this in later
  
      if ($result2 = $mysqli->query("SELECT `address` FROM `ip_address` WHERE `ip_networks_idip_networks` = '$networkid'")) {
		 while ($row2 = $result2->fetch_assoc()) {
    
		 $rowip = $row2["address"];
    
		 array_push($dbip,$rowip);
    
		 }
	  } 	  
		 	  // This will get all IPs that are not in DB that NMAP found
	  $newdbips = array_diff($upips, $dbip);
	  
	  foreach($newdbips as $newdbip){
		 
		 if ($mysqli->query("INSERT INTO `$db`.`ip_address` (`idip_address`, `address`, `status`,
		 `ip_networks_idip_networks`, `devices_iddevices`, `dhcp_mac`) VALUES (NULL, '$newdbip', '1', '$networkid', NULL, NULL);") === TRUE) {
			
		 } else{
			
		 echo'Something went wrong with the database please contact your webmaster';
		 exit;
		 
		 }
		 
	  } // end of loop
	  
	  // End of adding news IPs to DB
    // Start of marking IPs Down
	   $updbip = array(); // Fill this in later
  
      if ($result2 = $mysqli->query("SELECT `address` FROM `ip_address` WHERE `ip_networks_idip_networks` = '$networkid' and `status` = '1'")) {
		 while ($row2 = $result2->fetch_assoc()) {
    
		 $rowip = $row2["address"];
    
		 array_push($updbip,$rowip);
    
		 }
	  } 	  
	  // This will get all IPs that the DB thinks are up but NMAP thinks are down
	  $downips = array_diff($updbip,$upips);
	  
	  foreach($downips as $downip){
		 
		 if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `status` =  '0' WHERE `ip_networks_idip_networks` = '$networkid' and `address` = '$downip'") === TRUE) {
			
		 } else{
			
		 echo'Something went wrong with the database please contact your webmaster';
		 exit;
		 
		 }
		 
	  } // end of loop
	// End of marking IPs down
	// Start of Marking IPs UP
	  $downdbip = array(); // Fill this in later
  
      if ($result3 = $mysqli->query("SELECT `address` FROM `ip_address` WHERE `ip_networks_idip_networks` = '$networkid' and `status` = '0'")) {
		 while ($row3 = $result3->fetch_assoc()) {
    
		 $rowip = $row3["address"];
    
		 array_push($downdbip,$rowip);
    
		 }
	  } 	  
		 	  // This will get all IPs that the DB thinks are down but NMAP thinks are up
	  $newupips = array_intersect($downdbip,$upips);
	  
	  foreach($newupips as $upip){
		 
		 if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `status` =  '1' WHERE `ip_networks_idip_networks` = '$networkid' and `address` = '$upip'") === TRUE) {
			
		 } else{
			
		 echo'Something went wrong with the database please contact your webmaster';
		 exit;
		 
		 }
		 
	  } // end of loop
	// End of Marking IPs UP
    }// End of Loop
 }
   // Timeing
$time_end = microtime(true);
$time = $time_end - $time_start;
   
   if($time > 270000000){
     if ($mysqli->query("INSERT INTO `$db`.
				`notifications` (`idnotifications`, `readyn`,
				`content`, `date`, `fromwho`, `towho`) VALUES (NULL,
				'0', 'networkmonitor.php took over 4.5 MIN to run please make a copy', CURRENT_TIMESTAMP, 'system', 'all');")
				=== TRUE) {
				//Will notify admins of error 
				}
   }
   // Run DHCP Reconciler
   $cmd = '	wget --no-check-certificate https://127.0.0.1/dhcpreconciler.php -O /dev/null';
   exec($cmd . " > /dev/null &");
?>
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
 
  This script is ran be exec on networkmonitor.php it will send output to dev/null
  */
 $time_start = microtime(true);
require_once('./fileloader.php');

	 $mysqli = new mysqli("$ip", "$username", "$password", "$db");

 if ($result = $mysqli->query("SELECT * 
FROM  `ip_address`  ")) {
    
    foreach ($result as $row){
      $ipid= $row["idip_address"];
	  $ip= $row["address"];

				
				if ($result4 = $mysqli->query("SELECT * FROM `device_ports` WHERE `ip_address` = '$ip'")) {
			/* fetch associative array */
			 $rows = $result4->num_rows;
			}
			
			if($rows == '1'){
				if ($result5 = $mysqli->query("SELECT * FROM `device_ports` WHERE `ip_address` = '$ip'")) {
			/* fetch associative array */
			 
				while ($row5 = $result5->fetch_assoc()) {
					$did = $row5["devices_iddevices"];
				}
				}
				// This will unset link so that we can relink
				if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `devices_iddevices` = NULL WHERE `idip_address` = '$ipid';") === TRUE) {
			
							} else{
			
							echo'Something went wrong with the database please contact your webmaster';
							exit;
		 
							}
							
				if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `devices_iddevices` = '$did' WHERE `idip_address` = '$ipid';") === TRUE) {
			
				} else{
			
					echo'Something went wrong with the database please contact your webmaster';
					exit;
		 
				}
			}elseif($rows == '0'){
				
			}else{
				// Whoops someting has broke
					if ($mysqli->query("INSERT INTO `$db`.
					`notifications` (`idnotifications`, `readyn`,
					`content`, `date`, `fromwho`, `towho`) VALUES (NULL,
					'0', 'dhcpreconciler.php has had an unexpected error', CURRENT_TIMESTAMP, 'system', 'all');")
					=== TRUE) {
					//Will notify admins of error 
					}
			}
			} // end of loop 
    }// End of Loop
 
   // Timeing
$time_end = microtime(true);
$time = $time_end - $time_start;
   
   if($time > 270000000){
     if ($mysqli->query("INSERT INTO `$db`.
				`notifications` (`idnotifications`, `readyn`,
				`content`, `date`, `fromwho`, `towho`) VALUES (NULL,
				'0', 'dhcpreconciler.php took over 4.5 MIN to run please make a copy', CURRENT_TIMESTAMP, 'system', 'all');")
				=== TRUE) {
				//Will notify admins of error 
				}
   }

?>
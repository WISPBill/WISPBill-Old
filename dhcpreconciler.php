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
FROM  `routermgmt` ")) {
    
    foreach ($result as $row){
      $routerip = $row["ip_address"];
	  $eusername= $row["username"];
	  $epassword= $row["password"];
	  $iv= $row["IV"];
      
      $rpass= mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$epassword","ofb","$iv");
	  $rname = mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$eusername","ofb","$iv");
      
        $ssh = new Net_SSH2("$routerip");
        if (!$ssh->login("$rname", "$rpass")) {
        exit;
        }

     $data = $ssh->exec("/opt/vyatta/bin/vyatta-op-cmd-wrapper show dhcp leases");
		
     // this will pull IP and MAC
			 preg_match_all('/((?|\d{1,3}\.){3}\d{1,3}\s{1,9}(?|[a-z0-9]{2}\:){5}[a-z0-9]{2})/', $data, $match);
			
			$dhcpipmac = $match[0];
			
			foreach($dhcpipmac as $ipmac){
				
				preg_match_all('/(?|\d{1,3}\.){3}\d{1,3}/', $ipmac, $match2);
				
				$ipmatch = $match2[0];
				$dhcpip = $ipmatch[0];
				
				preg_match_all('/((?|[a-z0-9]{2}\:){5}[a-z0-9]{2})/', $ipmac, $match3);
				
				$macmatch = $match3[0];
				$dhcpmac = $macmatch[0];
			
			if ($result2 = $mysqli->query("SELECT * FROM `ip_address` WHERE `address` = '$dhcpip' and `dhcp_mac` = '$dhcpmac'")) {
			/* fetch associative array */
			 $ifindb = $result2->num_rows;
			}
				
				if($ifindb == '1'){
					// IN DB with both MAC and IP in the same row
				}elseif($ifindb == '0'){
					// Not in DB where rows match
					if ($result3 = $mysqli->query("SELECT * FROM `ip_address` WHERE `address` = '$dhcpip'")) {
					/* fetch associative array */
					$ifipindb = $result3->num_rows;
					}
						
						if($ifipindb == '1'){
							if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `dhcp_mac` = NULL WHERE `ip_address`.`dhcp_mac` ='$dhcpmac';") === TRUE) {
			
							} else{
			
							echo'Something went wrong with the database please contact your webmaster';
							exit;
		 
							}
							
							if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `dhcp_mac` = '$dhcpmac' WHERE `ip_address`.`address` ='$dhcpip';") === TRUE) {
			
							} else{
			
							echo'Something went wrong with the database please contact your webmaster';
							exit;
		 
							}
							
						}else{
							// IP is not in DB 
						}
						
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
				
				if ($result4 = $mysqli->query("SELECT * FROM `devices` WHERE `mac` = '$dhcpmac'")) {
			/* fetch associative array */
			 $macrows = $result4->num_rows;
			}
			
			if($macrows == '1'){
				if ($result5 = $mysqli->query("SELECT * FROM `devices` WHERE `mac` = '$dhcpmac'")) {
			/* fetch associative array */
			 
				while ($row5 = $result5->fetch_assoc()) {
					$did = $row5["iddevices"];
				}
				}
				// This will unset link so that we can relink
				if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `devices_iddevices` = NULL WHERE `ip_address`.`dhcp_mac` ='$dhcpmac';") === TRUE) {
			
							} else{
			
							echo'Something went wrong with the database please contact your webmaster';
							exit;
		 
							}
							
				if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `devices_iddevices` = '$did' WHERE `ip_address`.`dhcp_mac` ='$dhcpmac';") === TRUE) {
			
				} else{
			
					echo'Something went wrong with the database please contact your webmaster';
					exit;
		 
				}
			}elseif($macrows == '0'){
				// This is verify that it is not linked
				if ($mysqli->query("UPDATE  `$db`.`ip_address` SET  `devices_iddevices` = NULL WHERE `ip_address`.`dhcp_mac` ='$dhcpmac';") === TRUE) {
			
							} else{
			
							echo'Something went wrong with the database please contact your webmaster';
							exit;
		 
							}
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
 }
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
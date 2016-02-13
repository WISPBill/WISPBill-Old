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

 // ! This will take 7 secs per device if you have allot make copies and adjust cron and limit 
 
require_once('./fileloader.php');
	 $mysqli = new mysqli("$ip", "$username", "$password", "$db");
 if ($result = $mysqli->query("SELECT * FROM  `device_ports` WHERE  `use` !=  'no'
LIMIT 0 , 25")) {
    /* fetch associative array */
    foreach ($result as $row){
         $did = $row["devices_iddevices"];
         $portid = $row["iddevice_ports"];
         $name = $row["name"];
         
					 if ($result3 = $mysqli->query("SELECT * FROM `device_ports` WHERE `use` = 'mgmt' and `devices_iddevices` = '$did'")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
					 $routerip= $row3["ip_address"];
					 }
						 if ($result4 = $mysqli->query("SELECT * FROM `device_credentials` WHERE `devices_iddevices` = '$did'")) {
						 /* fetch associative array */
						 while ($row4 = $result4->fetch_assoc()) {
						 $eusername= $row4["username"];
						 $epassword= $row4["password"];
						 $iv= $row4["IV"];
						 }
						 
						 $rpass= mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$epassword","ofb","$iv");
						 $rname = mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$eusername","ofb","$iv");
						 
						  $time = time();
	$ssh = new Net_SSH2("$routerip");
	if (!$ssh->login("$rname", "$rpass")) {
        if ($result5 = $mysqli->query("SELECT * FROM `notifications` WHERE `readyn` = '0' and `content` = 'The Router at $routerip did not allow SSH on Management VLAN IP. Reason Unknown. Some Stats were not collected. ID of Port AFFECTED = $portid'")) {
			if ($result5->num_rows == 1){
			// Error is open no need to make again
										
			} elseif ($result5->num_rows == 0){
			// No open error have to make one
				if ($mysqli->query("INSERT INTO `$db`.
				`notifications` (`idnotifications`, `readyn`,
				`content`, `date`, `fromwho`, `towho`) VALUES (NULL,
				'0', 'The Router at $routerip did not allow SSH on Management VLAN IP. Reason Unknown. Some Stats were not collected. ID of Port AFFECTED = $portid', CURRENT_TIMESTAMP, 'system', 'all');")
				=== TRUE) {
				//Will notify admins of error 
				}
			}
				/* free result set */
		$result5->close();
		}
	}else{
	 if ($result5 = $mysqli->query("SELECT * FROM `notifications`
		WHERE `readyn` = '0' and `content` = 'The Router at $routerip did not allow SSH on Management VLAN IP. Reason Unknown. Some Stats were not collected. ID of Port AFFECTED = $portid'")) {
			if ($result5->num_rows == 1){
			// Error is open we need to close
			   while ($row = $result5->fetch_assoc()) {
			   $notiid= $row["idnotifications"];
			   }
			   if ($mysqli->query("UPDATE `$db`.`notifications`
											 SET `readyn` = '1' WHERE `notifications`.`idnotifications` = '$notiid';")
								   === TRUE) {
								   // Nothing
								   }
			} elseif ($result5->num_rows == 0){
			// No open error do nothing
				
			}
				/* free result set */
		$result5->close();
		}
	}
	$data = $ssh->exec("/opt/vyatta/bin/vyatta-op-cmd-wrapper show interfaces ethernet $name");
			
	$string = 'RX:  bytes    packets     errors    dropped    overrun      mcast';  
	    
	  $datastart = strpos($data,$string);
	  $datastart = $datastart +65; 
	  
	  $data = substr($data,$datastart);
	preg_match("/\d{1,}/", $data, $matches);
	if(isset($matches[0])){
	$rxbytes = $matches[0];
	}else{
            if ($result5 = $mysqli->query("SELECT * FROM `notifications`
		WHERE `readyn` = '0' and `content` = 'The Router at $routerip did not have stat data for Port with id = $portid'")) {
			if ($result5->num_rows == 1){
			// Error is open no need to make again
										
			} elseif ($result5->num_rows == 0){
			// No open error have to make one
				if ($mysqli->query("INSERT INTO `$db`.
				`notifications` (`idnotifications`, `readyn`,
				`content`, `date`, `fromwho`, `towho`) VALUES (NULL,
				'0', 'The Router at $routerip did not have stat data for Port with id = $portid'', CURRENT_TIMESTAMP, 'system', 'all');")
				=== TRUE) {
				//Will notify admins of error 
				}
			}
				/* free result set */
		$result5->close();
		}
	}

	$string = 'TX:  bytes    packets     errors    dropped    carrier collisions';  
    
	 $datastart = strpos($data,$string);
	 $datastart = $datastart +65; 
  
	$data = substr($data,$datastart);
	preg_match("/\d{1,}/", $data, $matches);
	if(isset($matches[0])){
	$txbytes = $matches[0];
	}else{
		if ($result5 = $mysqli->query("SELECT * FROM `notifications`
		WHERE `readyn` = '0' and `content` = 'The Router at $routerip did not have stat data for Port with id = $portid'")) {
			if ($result5->num_rows == 1){
			// Error is open no need to make again
										
			} elseif ($result5->num_rows == 0){
			// No open error have to make one
				if ($mysqli->query("INSERT INTO `$db`.
				`notifications` (`idnotifications`, `readyn`,
				`content`, `date`, `fromwho`, `towho`) VALUES (NULL,
				'0', 'The Router at $routerip did not have stat data for Port with id = $portid'', CURRENT_TIMESTAMP, 'system', 'all');")
				=== TRUE) {
				//Will notify admins of error 
				}
			}
				/* free result set */
		$result5->close();
		}
	}
	if ($result4 = $mysqli->query("SELECT * FROM  `port_data` WHERE  `device_ports_iddevice_ports` =  '$portid'
ORDER BY  `port_data`.`idport_data` DESC LIMIT 0 , 1")) {
										/* fetch associative array */
										while ($row4 = $result4->fetch_assoc()) {
										$orxbytes= $row4["rxbytes"];
										$otxbytes= $row4["txbytes"];
										$otime = $row4["time"];
										}
										
								   if($rxbytes < $orxbytes){
										$orxbytes = '0';
								   } else{
										//Nothing
								   }
								   
								   if($txbytes < $otxbytes){
										$otxbytes = '0';
								   }else{
										//nothing
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
                                   
	if ($mysqli->query("INSERT INTO `$db`.`port_data` (`idport_data`, `txbytes`, `rxbytes`, `txrate`, `rxrate`, `time`, `device_ports_iddevice_ports`)
					   VALUES (NULL, '$txbytes', '$rxbytes', '$txrate', '$rxrate', '$time', '$portid');") === TRUE) {
	} else{
	   echo'DB';
	      exit;
	}
    }
						 }// end if
					 } // End of If	 
    }// End of Loop
 } // End of IF 
?>
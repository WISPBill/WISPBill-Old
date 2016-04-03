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

$email = $_POST["email"];
$pin = $_POST["pin"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2']  = 'email';
    header('Location: ipcustomer.php');
    exit;
} elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2']  = 'pin';
    header('Location: ipcustomer.php');
    exit;
}else{
    $_SESSION['exitcodev2'] = '';
} // end if

$emailc = inputcleaner($email,$mysqli);
$pinc = inputcleaner($pin,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['errorcode'] = 'email';
    header('Location: ipcustomer.php');
    exit;
  }else{
  //do nothing 
  }
// end of data sanitize and existence check

if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `idcustomer_users` = $uid")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $infoid= $row["customer_info_idcustomer_info"];
     $uid= $row["idcustomer_users"];
}
       /* free result set */
    $result2->close();
}// end if

if ($result = $mysqli->query("SELECT * FROM  `customer_info` WHERE  `idcustomer_info` =  '$infoid' ")) {
    /* fetch associative array */
     
		while ($row = $result->fetch_assoc()) {      
        $cdid= $row["devices_iddevices"];
     }								

       /* free result set */
    $result->close();
}// end if

$cpeid = $cdid;

$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
  if ($result3 = $mysqli->query("SELECT * FROM  `customer_external` 
WHERE  `customer_info_idcustomer_info` =  '$infoid'")) {
    /* fetch associative array */
     while ($row = $result3->fetch_assoc()) {
     $mode= $row["billing_mode"];
}
       /* free result set */
    $result3->close();
}// end if
    if($mode == 'radius'){
     //Radius
     echo "Static IP not supported in Radius Mode";
     exit;
    }elseif($mode == 'wispbill'){
    
     if ($result14 = $mysqli->query("SELECT * FROM `devices` WHERE `iddevices` = '$cdid'")) {
     /* fetch associative array */
          while ($row14 = $result14->fetch_assoc()) {
          $mac= $row14["mac"];
          $site= $row14["location_idlocation"];
     }
       /* free result set */
     $result14->close();
}// end if
         // Get router ip
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
                         }
                    }               
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
		if ($mysqli->query("INSERT INTO `$db`.`notifications` (`idnotifications`, `readyn`,
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
	$ip = $radioip["ip"];
          
       if ($resultk = $mysqli->query("SELECT * FROM  `device_ports`
WHERE  `devices_iddevices` =  '$did'
AND  `use` =  'ap'")) {
    /* fetch associative array */
    foreach ($resultk as $rowk){
           $apportid= $rowk["iddevice_ports"];
    
     if ($result3 = $mysqli->query("SELECT * 
FROM  `dhcp_servers` 
WHERE  `device_ports_iddevice_ports` =  '$apportid'")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
					 $name= $row3["name"];
                     $subnet= $row3["subnet"];
                     $serverid= $row3["idDHCP_Servers"];
                     $start= $row3["Range_Start"];
                     $stop= $row3["Range_Stop"];
					 }
           $longip = ip2long($ip);
           $longstart = ip2long($start);
           $longstop = ip2long($stop);
           
           if($longip > $longstart and $longip < $longstop){
               // IP is in server range
               break 3;
           }else{
               // IP not in range loop will run until it is
           }
     }
    }
}// End of if 
     $ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$rname", "$rpass")) {
    exit('Login Failed');
}
$mapname = "static.ip.set.by.wispbill.for.user.$uid";
$ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet static-mapping $mapname ip-address $ip/n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet static-mapping $mapname mac-address '$mac'/n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");

// DB entry 

if ($mysqli->query("INSERT INTO `$db`.`static_leases`
                   (`idstatic_leases`, `ip`, `mac`, `DHCP_Servers_idDHCP_Servers`, `customer_info_idcustomer_info`)
                   VALUES (NULL, '$ip', '$mac', '$serverid', '$infoid');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

    header('Location: index.php');                   
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
    }// End of mode wispbill mode

}elseif($isuser === false){
       $_SESSION['exitcodev2']  = 'pin';
      header('Location: ipcustomer.php');
    exit;
}else{
  echo 'Error with userverify';
  exit;
}

 header('Location: index.php');
?>
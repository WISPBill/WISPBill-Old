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
function ACLWhitelist($cusid,$mysqli,$masterkey,$db){
// This assumes Customer already has linked cpe
$success = false;
if ($result = $mysqli->query("SELECT  `devices_iddevices` FROM  `customer_info` 
WHERE  `idcustomer_info` =  '$cusid'")) {
				/* fetch associative array */
				 while ($row = $result->fetch_assoc()) {
					 $cdid= $row["devices_iddevices"];
					 }
                     }
                     
                     if(empty($cdid)){
                        //no Device
                        return $success;
                     }
if ($result5 = $mysqli->query("SELECT * FROM  `devices` 
WHERE  `iddevices` =  '$cdid'")) {
				/* fetch associative array */
				 while ($row5 = $result5->fetch_assoc()) {
					 $site= $row5["location_idlocation"];
                      $mac= $row5["mac"];
					 }
                     }
                     
                     if(empty($site)){
                        //no site
                        return $success;
                     }

if ($result2 = $mysqli->query("SELECT * FROM  `devices` WHERE  `type` =  'router'
AND  `location_idlocation` =  '$site'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $did= $row2["iddevices"];
					 }
                     }
                     
                     if(empty($did)){
                        //no router
                        return $success;
                     }
                     
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
                         }else{
                        return $success;
                     }
                     }else{
                        return $success;
                     }
if ($result5 = $mysqli->query("SELECT * FROM  `device_ports` 
WHERE  `devices_iddevices` =  '$did' AND  `use` =  'ap'")) {
				foreach($result5 as $row){
				 $portid = $row["iddevice_ports"];
                
                if ($result4 = $mysqli->query("SELECT * FROM  `firewall` 
WHERE  `device_ports_iddevice_ports` =  '$portid' and `default_action` =  'reject'")) {
						 /* fetch associative array */
						 while ($row4 = $result4->fetch_assoc()) {
						 $rejectrule= $row4["reject_rule"];
						 $name= $row4["name"];
                         $firewallid= $row4["idfirewall"];
						 }
                         $newrejectrule = $rejectrule +1;
         
         $ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$rname", "$rpass")) {
    $success = false;
}

   $mac = strtolower($mac);                           
                         $ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper delete firewall name $name rule $rejectrule\n 
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule action accept\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule description 'Approved Mac'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule log disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule protocol all\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule source mac-address '$mac'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $newrejectrule action reject\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $newrejectrule description 'Non Approved Macs'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $newrejectrule log disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $newrejectrule protocol all\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");
        
                    if ($result = $mysqli->query("UPDATE  `$db`.`firewall` SET  `reject_rule` =
'$newrejectrule' WHERE `device_ports_iddevice_ports` =  '$portid'")) {
 
}else{
                        return $success;
                     }// end if

if ($result = $mysqli->query("INSERT INTO `$db`.`Firewall_Rules` (`idACL`, `rule_number`, `mac`,
`firewall_idfirewall`, `customer_info_idcustomer_info`) VALUES (NULL, '$rejectrule', '$mac', '$firewallid', '$cusid');")) {
 
}else{
                        return $success;
                     }// end if
				}
				}// End of Loop               
    }else{
                        return $success;
                     }//end if
    $success = true;
    return $success;
} // end of ACLWhitelist


function nopayset($cusid,$mysqli,$masterkey,$db) {
$success = false;
if ($result = $mysqli->query("SELECT  `devices_iddevices` FROM  `customer_info` 
WHERE  `idcustomer_info` =  '$cusid'")) {
				/* fetch associative array */
				 while ($row = $result->fetch_assoc()) {
					 $cdid= $row["devices_iddevices"];
					 }
                     }
                     
                     if(empty($cdid)){
                        //no Device
                        return $success;
                     }
if ($result5 = $mysqli->query("SELECT * FROM  `devices` 
WHERE  `iddevices` =  '$cdid'")) {
				/* fetch associative array */
				 while ($row5 = $result5->fetch_assoc()) {
					 $site= $row5["location_idlocation"];
                      $mac= $row5["mac"];
					 }
                     }
                     
                     if(empty($site)){
                        //no site
                        return $success;
                     }

if ($result2 = $mysqli->query("SELECT * FROM  `devices` WHERE  `type` =  'router'
AND  `location_idlocation` =  '$site'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $did= $row2["iddevices"];
					 }
                     }
                     
                     if(empty($did)){
                        //no router
                        return $success;
                     }
                     
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
                         }else{
                        return $success;
                     }
                     }else{
                        return $success;
                     }

if ($result4 = $mysqli->query("SELECT * FROM `Firewall_Rules` WHERE `customer_info_idcustomer_info` = '$cusid'")) {
						 /* fetch associative array */
						 while ($row4 = $result4->fetch_assoc()) {
						 $rulenumber= $row4["rule_number"];
						 $firewall= $row4["firewall_idfirewall"];
						 }
}

if ($result4 = $mysqli->query("SELECT * FROM `firewall` WHERE `idfirewall` = '$firewall'")) {
						 /* fetch associative array */
						 while ($row4 = $result4->fetch_assoc()) {
						 $name= $row4["name"];
						 }
}

         $ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$rname", "$rpass")) {
    $success = false;
}

               $ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper delete firewall name $name rule $rulenumber\n 
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");

    $success = true;
    return $success;
} // end of Nopay Set

function nopayunset($cusid,$mysqli,$masterkey,$db) {
$success = false;
if ($result = $mysqli->query("SELECT  `devices_iddevices` FROM  `customer_info` 
WHERE  `idcustomer_info` =  '$cusid'")) {
				/* fetch associative array */
				 while ($row = $result->fetch_assoc()) {
					 $cdid= $row["devices_iddevices"];
					 }
                     }
                     
                     if(empty($cdid)){
                        //no Device
                        return $success;
                     }
if ($result5 = $mysqli->query("SELECT * FROM  `devices` 
WHERE  `iddevices` =  '$cdid'")) {
				/* fetch associative array */
				 while ($row5 = $result5->fetch_assoc()) {
					 $site= $row5["location_idlocation"];
                      $mac= $row5["mac"];
					 }
                     }
                     
                     if(empty($site)){
                        //no site
                        return $success;
                     }

if ($result2 = $mysqli->query("SELECT * FROM  `devices` WHERE  `type` =  'router'
AND  `location_idlocation` =  '$site'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $did= $row2["iddevices"];
					 }
                     }
                     
                     if(empty($did)){
                        //no router
                        return $success;
                     }
                     
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
                         }else{
                        return $success;
                     }
                     }else{
                        return $success;
                     }
if ($result5 = $mysqli->query("SELECT * FROM  `device_ports` 
WHERE  `devices_iddevices` =  '$did' AND  `use` =  'ap'")) {
				foreach($result5 as $row){
				 $portid = $row["iddevice_ports"];
                
                if ($result4 = $mysqli->query("SELECT * FROM  `firewall` 
WHERE  `device_ports_iddevice_ports` =  '$portid' and `default_action` =  'modify'")) {
						 /* fetch associative array */
						 while ($row4 = $result4->fetch_assoc()) {
						 $name= $row4["name"];
                         $firewallid= $row4["idfirewall"];
						 }
         
         $ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$rname", "$rpass")) {
    $success = false;
}
if ($result4 = $mysqli->query("SELECT * FROM  `Firewall_Rules` 
WHERE  `firewall_idfirewall` =  '$firewallid' and `customer_info_idcustomer_info` = '$cusid'")) {
						 /* fetch associative array */
						 while ($row4 = $result4->fetch_assoc()) {
						 $rejectrule = $row4["rule_number"];
						 $mac= $row4["mac"];
						 }
}
                             
                           $mac = strtolower($mac);                           
                         $ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule action accept\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule description 'Approved Mac'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule log disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule protocol all\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule source mac-address '$mac'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");
						 

				}
				}// End of Loop               
    }else{
                        return $success;
                     }//end if
    $success = true;
    return $success;
} // end of Nopay unSet

function mailuser($email,$event,$sendgridapi,$fromemail,$filldata) {
//SendGrid API Loader
require './sendgrid-php/vendor/autoload.php';
require './emailtemplates.php';

if($event == 'receipt'){
	// Send Receipt
	$sendgrid = new SendGrid("$sendgridapi");
	$semail = new SendGrid\Email();
	$semail
    ->addTo("$email")
    ->setFrom("$fromemail")
    ->setSubject("$receiptsubject")
    ->setHtml("$receipthtml");

	$sendgrid->send($semail);
	
}elseif($event == 'fail'){
	$sendgrid = new SendGrid("$sendgridapi");
	$semail = new SendGrid\Email();
	$semail
    ->addTo("$email")
    ->setFrom("$fromemail")
    ->setSubject("$failsubject")
    ->setHtml("$failhtml");

	$sendgrid->send($semail);
}elseif($event == 'task'){
	// Left Blank on purpose
}elseif($event == 'endtask'){
	// Left Blank on purpose
}
}// End of Mail User
?>
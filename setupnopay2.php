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
	
	This script is depreciated as far as the software gose I do not recommend using it for WISPBill
 */
require_once('./session.php');
require_once('./fileloader.php');
$mysqli = new mysqli("$ip", "$username", "$password", "$db");
$mysqlil = new mysqli("$ipl", "$usernamel", "$passwordl", "$dbl");
// start of post
$backport = $_POST["backport"];
$site = $_POST["site"];
$port = $_POST["port"];
// end of post

// start of data sanitize and existence check
 if (empty($backport)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'backport';
    header('Location: setupnopay.php');
    exit;
}elseif(empty($site)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'site';
      header('Location: setupnopay.php');
    exit;
}elseif(empty($port)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'port';
      header('Location: setupnopay.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}


$backport = $mysqli->real_escape_string($backport);
$site = $mysqli->real_escape_string($site);
$portid = $mysqli->real_escape_string($port);

// end of data sanitize and existence check
// start of data entry
if ($result2 = $mysqli->query("SELECT * FROM  `devices` WHERE  `type` =  'router'
AND  `location_idlocation` =  '$site'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $did= $row2["iddevices"];
					 }
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
						 

if ($resultl = $mysqlil->query("SELECT * FROM `device_ports` WHERE `iddevice_ports` = '$backport")) {
/* fetch associative array */
while ($rowl = $resultl->fetch_assoc()) {
$backip= $rowl["ip_address"];
					}
                    }
if ($resultl = $mysqli->query("SELECT * FROM `device_ports` WHERE `iddevice_ports` = '$portid'")) {
				/* fetch associative array */
				 while ($rowl = $resultl->fetch_assoc()) {
					 $portdesc= $rowl["name"];
					 }
                     
                     $ifvirtual = strpos($portdesc, '.');
                     
                     if($ifvirtual == FALSE){
                      $portname = $portdesc;
                     }else{
                      $ifvirtual = $ifvirtual + 1;
                      $vif = substr("$portdesc","$ifvirtual");
                      $vlength = strlen($vif);
                      $vlength = $vlength + 1;
                      $lenght = strlen($portdesc);
                      $lenght = $lenght - $vlength;
                      $eth = substr("$portdesc","0","$lenght");
                      $portname = "$eth vif $vif";
                     }
} else {
     echo'Something went wrong with the database please contact your webmaster';
        exit;
}

$ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$rname", "$rpass")) {
    exit('Login Failed');
}

$desc = "No Pay Ruleset SET by WISPBill";

if ($result = $mysqli->query("SELECT * FROM `firewall` WHERE `description` = '$desc'")) {
    $nopayfirewalls = mysqli_num_rows($result);

$ipnumber = 220 - $nopayfirewalls;    
$long = ip2long($nopayportalip);
$end = $long + $ipnumber;
$addip = long2ip($end);
}else{
    echo "DB";
    exit;
}
$name = "NOPAY"."$did";
$ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set protocols static table 35 route 0.0.0.0/0 next-hop $nopayportalip\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces tunnel tun35 address $addip/24\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces tunnel tun35 encapsulation gre\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces tunnel tun35 local-ip $backip\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces tunnel tun35 multicast disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces tunnel tun35 remote-ip $nopaytunip\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces tunnel tun35 ttl 255\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall modify $name description '$desc'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall modify $name rule 1 action accept\n 
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces ethernet $portname firewall in modify $name\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");

if ($mysqli->query("INSERT INTO `$db`.`firewall` (`idfirewall`, `name`,
`default_action`, `description`, `reject_rule`,`device_ports_iddevice_ports`) VALUES
(NULL, '$name', 'modify', '$desc', '1', '$portid');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
header('Location: index.php');
?>
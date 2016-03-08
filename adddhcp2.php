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
$name = $_POST["name"];
$pool = $_POST["pool"];
$dns = $_POST["dns"];
$site = $_POST["site"];
$port = $_POST["port"];
// end of post

// start of data sanitize and existence check
 if (empty($name)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: adddhcp.php');
    exit;
} elseif(empty($pool)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pool';
     header('Location: adddhcp.php');
    exit;
}elseif(empty($dns)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'dns';
     header('Location: adddhcp.php');
    exit;
}elseif(empty($site)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'site';
     header('Location: adddhcp.php');
    exit;
}elseif(empty($port)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'port';
     header('Location: adddhcp.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}


$name = $mysqli->real_escape_string($name);
$pool = $mysqli->real_escape_string($pool);
$dns = $mysqli->real_escape_string($dns);
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
					 }}
						 if ($result4 = $mysqli->query("SELECT * FROM `device_credentials` WHERE `devices_iddevices` = '$did'")) {
						 /* fetch associative array */
						 while ($row4 = $result4->fetch_assoc()) {
						 $eusername= $row4["username"];
						 $epassword= $row4["password"];
						 $iv= $row4["IV"];
						 }}
						 
						 $rpass= mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$epassword","ofb","$iv");
						 $rname = mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$eusername","ofb","$iv");
						 
	if ($result4 = $mysqli->query("SELECT * FROM `device_ports` WHERE `iddevice_ports` = '$portid'")) {
				/* fetch associative array */
				 while ($row4 = $result4->fetch_assoc()) {
					 $defaultrouter = $row4["ip_address"];
					 $mask = $row4["network"];
					 }}
					 
					 $cidr = substr($mask,1);

$network = long2ip((ip2long($defaultrouter)) & ((-1 << (32 - (int)$cidr))));

$subnet = $network.$mask;

$long = ip2long($defaultrouter);
$start = $long +1;
$start =long2ip($start);
$end = $long + $pool;
$end = long2ip($end);

$ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$rname", "$rpass")) {
    exit('Login Failed');
}

$ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name authoritative disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet default-router $defaultrouter\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet dns-server $dns\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet lease 86400\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet start $start stop $end\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");

if ($mysqli->query("INSERT INTO `$db`.`dhcp_servers` (`idDHCP_Servers`,
                   `DNS`, `Range_Start`, `Range_Stop`, `subnet`, `name`,
                   `device_ports_iddevice_ports`) VALUES
                   (NULL, '$dns', '$start', '$end', '$subnet', '$name', '$portid');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
header('Location: index.php');
?>
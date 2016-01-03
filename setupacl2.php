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
$site = $_POST["site"];
$port = $_POST["port"];
// end of post

// start of data sanitize and existence check
 if (empty($name)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: setupacl.php');
    exit;
}elseif(empty($site)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'site';
     header('Location: setupacl.php');
    exit;
}elseif(empty($port)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'port';
     header('Location: setupacl.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}


$name = $mysqli->real_escape_string($name);
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
					 $portidm= $row3["port id"];
					 }
                     }
if ($result4 = $mysqli->query("SELECT * FROM  `device_ports` WHERE  `port id` =  '$portid'")) {
/* fetch associative array */
while ($row4 = $result4->fetch_assoc()) {
$portdid= $row4["iddevice_ports"];
					 }
                     }
if ($resultl = $mysqlil->query("SELECT * FROM `ipv4_addresses` WHERE `port_id` = '$portidm'")) {
				/* fetch associative array */
				 while ($rowl = $resultl->fetch_assoc()) {
					 $routerip= $rowl["ipv4_address"];
					 }
                     }
if ($resultl = $mysqlil->query("SELECT * FROM `ports` WHERE `port_id` = '$portid'")) {
				/* fetch associative array */
				 while ($rowl = $resultl->fetch_assoc()) {
					 $portdesc= $rowl["ifDescr"];
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

$desc = "ACL Ruleset SET by WISPBill";
$rejectrule = "2";
$ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name default-action reject\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name description '$desc'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 action accept\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 description 'Allow Existing'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 log disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 protocol all\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 state established enable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 state invalid disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 state new disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule 1 state related enable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule action reject\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule description 'Non Approved Macs'\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule log disable\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set firewall name $name rule $rejectrule protocol all\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set interfaces ethernet $portname firewall in name $name\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");

if ($mysqli->query("INSERT INTO `$db`.`firewall` (`idfirewall`, `name`,
`default_action`, `description`, `reject_rule`,`device_ports_iddevice_ports`) VALUES
(NULL, '$name', 'reject', '$desc', '$rejectrule', '$portdid');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
header('Location: index.php');
?>
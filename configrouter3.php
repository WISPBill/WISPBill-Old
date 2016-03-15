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
$router = $_POST["router"];
$cleandata = $_SESSION['cleandata'];
// end of post

$router = inputcleaner($router,$mysqli);

if ($result = $mysqli->query("SELECT * FROM `device_credentials` WHERE `devices_iddevices` = '$router'")) {
      /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
    $eusername= $row["username"];
	$epassword= $row["password"];
	$iv= $row["IV"];
    }
	 $routerip = $_SESSION['ip'];
}
$password = mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$epassword","ofb","$iv");
$username = mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$eusername","ofb","$iv");
foreach($cleandata as $value){
    $rawip = $value["ip"];
    $interface = $value["name"];
	$key = $value["post"];
    $use = $_POST["$key"];
		
	preg_match("/\/(\d{1,2})/", $rawip, $matches);
	if(isset($matches[0])){
	$mask = $matches[0];
	}else{
	    $mask = '';
	}
	
	preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $rawip, $matches);
	if(isset($matches[0])){
	$ip = $matches[0];
	}else{
	    $ip = '';
	}
		
		
    $time = time();
	$ssh = new Net_SSH2("$routerip");
	if (!$ssh->login("$username", "$password")) {
    $_SESSION['exitcodev2'] = 'all';
	echo "SSH Fail";
     exit;
	}

	$data = $ssh->exec("/opt/vyatta/bin/vyatta-op-cmd-wrapper show interfaces ethernet $interface");
	
	preg_match("/((?:[a-zA-Z0-9]{2}[:-]){5}[a-zA-Z0-9]{2})/", $data, $matches);
	if(isset($matches[0])){
	$mac = $matches[0];
	}else{
	    $mac = '';
	}
	
	preg_match("/Description: (\w{1,})/", $data, $matches);
	if(isset($matches[0])){
	$desc = $matches[0];
	}else{
	    $desc = '';
	}
	
	$string = 'RX:  bytes    packets     errors    dropped    overrun      mcast';  
	    
	  $datastart = strpos($data,$string);
	  $datastart = $datastart +65; 
	  
	  $data = substr($data,$datastart);
	preg_match("/\d{1,}/", $data, $matches);
	if(isset($matches[0])){
	$rxbytes = $matches[0];
	}else{
	    echo 'RX';
	}

	$string = 'TX:  bytes    packets     errors    dropped    carrier collisions';  
    
	 $datastart = strpos($data,$string);
	 $datastart = $datastart +65; 
  
	$data = substr($data,$datastart);
	preg_match("/\d{1,}/", $data, $matches);
	if(isset($matches[0])){
	$txbytes = $matches[0];
	}else{
		echo 'TX';
	}
	if ($mysqli->query("INSERT INTO `$db`.`device_ports` (`iddevice_ports`, `port id`,
					   `use`, `devices_iddevices`, `name`, `ip_address`, `network`,
					   `mac`, `desc`) VALUES
					   (NULL, NULL, '$use', '$router', '$interface', '$ip', '$mask', '$mac', '$desc');") === TRUE) {
	} else{
	   echo'Something went wrong with the database please contact your webmaster';
	      exit;
	}
	if ($result = $mysqli->query("SELECT * FROM `device_ports` WHERE `mac` = '$mac' and `ip_address` = '$ip' and `devices_iddevices` = '$router'")) {
      /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
    $portid = $row["iddevice_ports"];
    }
	}
	if ($mysqli->query("INSERT INTO `$db`.`port_data` (`idport_data`, `txbytes`, `rxbytes`, `txrate`, `rxrate`, `time`, `device_ports_iddevice_ports`)
					   VALUES (NULL, '$txbytes', '$rxbytes', NULL, NULL, '$time', '$portid');") === TRUE) {
	} else{
	   echo'Something went wrong with the database please contact your webmaster';
	      exit;
	}
} // end of loop
$_SESSION['cleandata'] = '';
$_SESSION['ip'] = '';
header('Location: index.php');
?>
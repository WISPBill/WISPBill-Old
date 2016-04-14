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
$location = $_POST["location"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2']  = 'email';
    header('Location: ipdelete.php');
    exit;
} elseif(empty($location)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'location';
   header('Location: ipdelete.php');
    exit;
}elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2']  = 'pin';
    header('Location: ipdelete.php');
    exit;
}else{
    $_SESSION['exitcodev2'] = '';
} // end if

$emailc = inputcleaner($email,$mysqli);
$pinc = inputcleaner($pin,$mysqli);
$location = inputcleaner($location,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['errorcode'] = 'email';
    header('Location: ipdelete.php');
    exit;
  }else{
  //do nothing 
  }
// end of data sanitize and existence check

$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
  if ($result3 = $mysqli->query("SELECT * FROM `customer_locations` WHERE `idcustomer_locations` = '$location'")) {
    /* fetch associative array */
     while ($row = $result3->fetch_assoc()) {
     $mode= $row["billing_mode"];
     $cdid= $row["devices_iddevices"];
}
       /* free result set */
    $result3->close();
}// end if
    if($mode == 'radius'){
     //Radius
     echo "Static IP not supported in Radius Mode";
     exit;
    }elseif($mode == 'wispbill'){
     
     if ($result = $mysqli->query("SELECT * FROM `static_leases` WHERE `customer_locations_idcustomer_locations	`= '$location'")) {
            while ($row = $result->fetch_assoc()) {
    $mac = $row["mac"];
     $ip = $row["ip"];
     $serverid= $row["DHCP_Servers_idDHCP_Servers"];
     $lid = $row["idstatic_leases"];
}}
 if ($result = $mysqli->query("SELECT * FROM `dhcp_servers` WHERE `idDHCP_Servers` = '$serverid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
    $name = $row["name"];
    $subnet = $row["subnet"];
    $sportid = $row["device_ports_iddevice_ports"];
}
 }
 // Get router ip
          if ($result2 = $mysqli->query("SELECT * FROM `device_ports` WHERE `iddevice_ports` = '$sportid'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $did= $row2["devices_iddevices"];
					 }}
					
                    if ($result3 = $mysqli->query("SELECT * FROM `routermgmt` WHERE `iddevices` = '$did'")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
					 $routerip= $row3["ip_address"];
                     $eusername= $row3["username"];
					 $epassword= $row3["password"];
					 $iv= $row3["IV"];
					 }

						 $rpass= mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$epassword","ofb","$iv");
						 $rname = mcrypt_decrypt (MCRYPT_BLOWFISH,"$masterkey", "$eusername","ofb","$iv");
                      
                     }else{
                        echo 'MYSQL DB Error';
                        exit;
                     }               
          
      $mac = strtolower($mac);
      
          $ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$rname", "$rpass")) {
    exit('Login Failed');
}
$mapname = "static.ip.set.by.wispbill.for.user.$location";
$ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper delete service dhcp-server shared-network-name $name subnet $subnet static-mapping $mapname ip-address $ip/n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper delete service dhcp-server shared-network-name $name subnet $subnet static-mapping $mapname mac-address '$mac'/n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");

 if ($result = $mysqli->query("DELETE FROM `static_leases` WHERE `idstatic_leases` = '$lid'")) {
       header('Location: index.php');
       exit;
 }else{
     echo"DB";
     exit;
 }
    }// end of wispbill mode

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
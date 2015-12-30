<?php


function staticip($cusid,$rname,$rpass,$db,$mysqli){
// This will make a statc ip on router and store in mysql

if ($result2 = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = $cusid")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $did= $row["devices_iddevices"];
}
       /* free result set */
    $result2->close();
}// end if

if ($result2 = $mysqli->query("SELECT * FROM `devices` WHERE `iddevices` = $did")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $mac= $row["mac"];
     $locid= $row["location_idlocation"];
}
       /* free result set */
    $result2->close();
}// end if



$ssh = new Net_SSH2("$sship");
if (!$ssh->login("$rname", "$rpass")) {
    exit('Login Failed');
}

$ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper begin\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet static-mapping Test ip-address $ip/n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper set service dhcp-server shared-network-name $name subnet $subnet static-mapping Test mac-address '$mac'/n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper commit\n
/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper save\n");

// DB entry 

if ($mysqli->query("INSERT INTO `$db`.`static_leases`
                   (`idstatic_leases`, `ip`, `mac`, `DHCP_Servers_idDHCP_Servers`)
                   VALUES (NULL, '$ip', '$mac', '$serverid');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

if ($result2 = $mysqli->query("SELECT * FROM `static_leases` WHERE `ip` ='$ip' and `mac` = '$mac'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
    $sid = $row["idstatic_leases"];
}
       /* free result set */
    $result2->close();
}// end if

if ($result = $mysqli->query("UPDATE `$db`.`customer_info` SET
                             `static_leases_idstatic_leases` = '$sid' WHERE
                             `customer_info`.`idcustomer_info` = $cusid;")) {
}else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}
// End of DB Entry
} // End of function staticip
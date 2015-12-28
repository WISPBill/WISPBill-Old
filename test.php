<?php
require_once('./fileloader.php');

$routerip = '192.168.1.1';
$username = 'danny';
$password = 'joekity';
  
function getdhcpip2($routerip, $username, $password,$mac) {

$ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$username", "$password")) {
    return $radioip ='router error';
}

$data = $ssh->exec("/opt/vyatta/sbin/vyatta-cfg-cmd-wrapper show dhcp leases");
	echo "$data";
}

getdhcpip2($routerip,$username,$password);


?>

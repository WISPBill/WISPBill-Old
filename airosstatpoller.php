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
	
	Thanks to @jormerod on the ubiquiti community
	 this function has been modified from its original
	 to better work with WISPBill The orginal is posted here
	 http://community.ubnt.com/t5/airOS-SDK-Custom-Development/PHP-code-to-grab-AirOS-Stats/m-p/745544#M1985
 */
function getAirOSstat($radioIP, $username, $password) {
    $airOS["error"] = 'none'; 
    if (!($con = ssh2_connect($radioIP, 22))) {
        $airOS["error"] = 'conerror';
			return $airOS;
    } else {
        // try to authenticate with username root, password secretpassword
        if (!ssh2_auth_password($con, $username, $password)) {
            $airOS["error"] = 'autherror';
			return $airOS;
        } else {

            // execute a command
            if (!($stream = ssh2_exec($con, "iwconfig"))) {
                $airOS["error"] = 'exeerror';
				return $airOS;
            } else {
                // collect returning data from command
                stream_set_blocking($stream, true);
                $data = "";
                while ($buf = fread($stream, 4096)) {
                    $data .= $buf;
                }
                fclose($stream);
            }
        }
    }
	
    $data = str_replace(array("\r", "\n", "\t"), "", $data);
    $data = str_replace(" ", "|", $data);
	
	if(empty($data)){
		$airOS["error"] = 'exeerror';
				return $airOS;
	} else {
	//print_r($data);
    /* Capture Frequency */
    preg_match("/\Frequency:(.*?)\|/", $data, $matches);
    $airOS["frequency"] = str_replace("\"", "", $matches[1]);

    /* Capture TX Power */
    preg_match("/\Tx-Power=(.*?)\|/", $data, $matches);
    $airOS["txPower"] = str_replace("\"", "", $matches[1]);

    /* Capture Signals */
    preg_match_all("/level=(.*?)\|/", $data, $matches);
    $airOS["signal"] = str_replace("\"", "", $matches[1][0]);
    $airOS["noise"] = str_replace("\"", "", $matches[1][1]);
	
	}
	
	if (!($con = ssh2_connect($radioIP, 22))) {
         $airOS["error"] = 'conerror';
			return $airOS;
    } else {
        // try to authenticate with username root, password secretpassword
        if (!ssh2_auth_password($con, $username, $password)) {
             $airOS["error"] = 'autherror';
			return $airOS;
        } else {

            // execute a command
            if (!($stream = ssh2_exec($con, "mca-status"))) {
                 $airOS["error"] = 'exeerror';
				return $airOS;
            } else {
                // collect returning data from command
				$airOS["time"] = time();
                stream_set_blocking($stream, true);
                $data = "";
                while ($buf = fread($stream, 4096)) {
                    $data .= $buf;
                }
                fclose($stream);
            }
        }
    }

    $data = str_replace(array("\r", "\n", "\t"), "", $data);
    $data = str_replace(" ", "|", $data);

	if(empty($data)){
		$airOS["error"] = 'exeerror';
				return $airOS;
	} else {
	
	$start = strpos($data, 'ccq=');
	$start = $start +4;
	$end = strpos($data, 'uptime=');
	$length = $end - $start;
	$airOS["ccq"] = substr("$data", "$start","$length" );
	
	$start = strpos($data, 'wlanTxLatency=');
	$end = strpos($data, 'wlanPolling=');
	$start = $start +14;
	$length = $end - $start;
	$airOS["latency"] = substr("$data", "$start","$length" );

	$start = strpos($data, 'wlanRxBytes=');
	$end = strpos($data, 'wlanRxPackets=');
	$start = $start +12;
	$length = $end - $start;
	$airOS["rxbtyes"] = substr("$data", "$start","$length" );
	
	$start = strpos($data, 'wlanTxBytes=');
	$end = strpos($data, 'wlanTxPackets=');
	$start = $start +12;
	$length = $end - $start;
	$airOS["txbtyes"] = substr("$data", "$start","$length" );
	 
    return $airOS;
	}
}

function getdhcpip($routerip, $username, $password,$mac) {
$radioip = array();
$ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$username", "$password")) {
    return $radioip["error"] ='router error';
}

$data = $ssh->exec("/opt/vyatta/bin/vyatta-op-cmd-wrapper show dhcp leases");
	
	$end = strpos($data, "$mac");
	$maclenght = strlen($mac);
	$start = $end - 18; // Get is the longest an IP can be with spaces
	$length = $end + $maclenght - $start;
	$data = substr("$data", "$start","$length" );
		
		if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $data, $match)) {
    if (filter_var($match[0], FILTER_VALIDATE_IP)) {
        $radioip["ip"] = $match[0];
		$radioip["mac"] ="$mac";
		$radioip["error"] ='';
		return $radioip;
	    
    }else {
	return $radioip["error"] ='';
	}
}
		
} // End of Get DHCP IP
?>
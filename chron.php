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
require_once('./fileloader.php');
 $mysqli = new mysqli("$ip", "$username", "$password", "$db");
 if ($result = $mysqli->query("SELECT COUNT( * ) FROM  `device_ports` WHERE  `use` !=  'no'")){
     while ($row = $result->fetch_assoc()) {
						 $ports = $row["COUNT( * )"];
						
						 }
 }
    $groupsof20 = $ports / 20;
    
    $loops = ceil($groupsof20);
    
    $limitstart = 0;
    $limitend = 20;
    
    while($loops > 0){
         $cmd = "wget --no-check-certificate \"https://127.0.0.1/routerpoller.php?start=$limitstart&end=$limitend\" -O /dev/null";
        exec($cmd . " > /dev/null &");
        
        $limitstart = $limitstart + 20;
        $limitend = $limitend + 20;
        
        --$loops;
    }
    
     if ($result = $mysqli->query("SELECT COUNT( * ) FROM  `devices` WHERE  `type` =  'cpe'
AND  `field_status` =  'customer' AND  `manufacturer` =  'Ubiquiti Networks'")){
     while ($row = $result->fetch_assoc()) {
						 $radios = $row["COUNT( * )"];
						
						 }
 }

    $groupsof20 = $radios / 20;
    
    $loops = ceil($groupsof20);
    
    $limitstart = 0;
    $limitend = 20;
    
    while($loops > 0){
         $cmd = "wget --no-check-certificate \"https://127.0.0.1/poller.php?start=$limitstart&end=$limitend\" -O /dev/null";
        exec($cmd . " > /dev/null &");
        
        $limitstart = $limitstart + 20;
        $limitend = $limitend + 20;
        
        --$loops;
    }
    
     $cmd = "wget --no-check-certificate https://127.0.0.1/networkmonitor.php -O /dev/null";
        exec($cmd . " > /dev/null &");
?>
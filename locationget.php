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
	
    $user = $_GET['choice'];
	if(isset($_GET['ip'])){
		$isip = $_GET['ip'];
		
	}
	
	$user = inputcleaner($user,$mysqli);
	$isip = inputcleaner($isip,$mysqli);
	
	echo "$isip";
	
    if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$user'")) {
				/* fetch associative array */
				 while ($row2 = $result2->fetch_assoc()) {
					 $infoid= $row2["customer_info_idcustomer_info"];
					 }
					 if ($result3 = $mysqli->query("SELECT * FROM `customer_locations` WHERE `customer_info_idcustomer_info` = '$infoid'")) {
				/* fetch associative array */
                 echo'<option value="" selected disabled>Please Select a Location</option>';
				 foreach($result3 as $row3){
					 $street_address = $row3["street_address"];
                     $city = $row3["city"];
                     $zip = $row3["zip"];
                     $state = $row3["state"];
                     $locid = $row3["idcustomer_locations"];
                     $plan = $row3["customer_plans_idcustomer_plans"];
                     $mode = $row3["billing_mode"];
					 $disabled = '';
					
					 
                     if(empty($plan)){
                        $plan = 'No Active Service at this Location';
                     }else{
                        $plan = "Active Service at this Location Billing Mode: $mode";
                     }
					 
					 $display = "$street_address $city $state $zip Note $plan";
					 
					  if($isip == 'true'){
						if($mode == 'radius'){
							$disabled = 'disabled';
							$display = 'This Locations is using Radius Which Does Not Support Static IP Address';
						}
					 }elseif($isip == 'false'){
						    if ($result = $mysqli->query("SELECT COUNT( * ) FROM  `static_leases` WHERE `customer_locations_idcustomer_locations` = '$locid'")){
						   while ($row = $result->fetch_assoc()) {
						 $ips= $row["COUNT( * )"];
						
						 }
						 }
						 if($ips == '0'){
							$disabled = 'disabled';
							$display = 'This Location Has No Static IP Address\'s';
						 }
					 }
					 
                    echo"<option value=$locid $disabled>$display</option>";
					 
                 } // end of loop
                 }
                     }
?>
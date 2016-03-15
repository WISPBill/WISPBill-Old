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
require_once('./billingcon.php');

$newplanid = $_POST['plan'];
$newplanid = inputcleaner($newplanid,$mysqli);

$mysqli = new mysqli("$ip", "$username", "$password", "$db");
if ($result = $mysqli->query("SELECT * FROM `customer_plans` WHERE `idcustomer_plans` = $newplanid")){
          /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $newpname= $row["name"];
}}
       /* free result set */
    $result->close();


foreach ($_POST['id'] as $id) {
	$id = inputcleaner($id,$mysqli);
    if ($result = $mysqli->query("SELECT * FROM `customer_plans` WHERE `idcustomer_plans` = $id")){
          /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $pname= $row["name"];
}
       /* free result set */
    $result->close();
    // Remove Plan from Stripe and Stripe Billing 
      if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_plans` ='$id'")){
          /* fetch associative array */
     foreach ($result as $row) {
     $uid= $row["idcustomer_users"];
     
       if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `idcustomer_users` = '$uid'")){
          /* fetch associative array */
     foreach ($result as $row) {
     $cusid= $row["stripeid"];
     $cus= Stripe_Customer::retrieve("$cusid");
      $subid = $cus->subscriptions->data[0]->id;
     $subscription = $cus->subscriptions->retrieve("$subid");
     $subscription->plan = "$newpname";
     $subscription->save();
   
}
}
     }
      }
    }
    
    /* free result set */
    $result->close();
    // Remove Plan form Databases
      if ($result = $mysqli->query("UPDATE `customer_info` SET `idcustomer_plans` = '$newplanid' WHERE `idcustomer_plans` = '$id'")){
        }
     if ($result = $mysqli->query("DELETE FROM `customer_plans` WHERE `idcustomer_plans` = '$id'")){
        // Delete Stripe Plan
        $plan = Stripe_Plan::retrieve("$pname");
      $plan->delete();
     }
      

} // end of foreach 
header('Location: index.php');
?>
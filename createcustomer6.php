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
$plan = $_SESSION['plan'];
$email = $_SESSION['email'];
$mysqli = new mysqli("$ip", "$username", "$password", "$db");
// start of flow
$flow = $_SESSION['flow'];
if($flow == 3){
    // do nothing
}else{
    // user tried to skip a step
    header('Location: createcustomer.php');
}
  if ($result = $mysqli->query("SELECT * FROM `customer_plans` WHERE `idcustomer_plans` = '$plan'")) {
    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $pname = $row["name"];
        }

    /* free result set */
    $result->close();
}
// Start of Strip API
require_once(dirname(__FILE__) . '/billingcon.php');

  $token  = $_POST['stripeToken'];

  $customer = Stripe_Customer::create(array(
      'email' => $email,
      'card'  => $token,
      'plan' => $pname
  ));
   $cusid= $customer["id"];

  
    if ($result = $mysqli->query("UPDATE `$db`.`customer_users` SET `stripeid` = '$cusid' WHERE `customer_users`.`email` = '$email'")) {
    
}
// clean up
unset($_SESSION['flow']);
unset($_SESSION['exitcode']);
unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION['exitcodev2']);
unset($_SESSION['plan']);
unset($_SESSION['email']);
header('Location: index.php');
exit;
?>
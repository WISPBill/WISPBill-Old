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

// Start of Strip API
require_once(dirname(__FILE__) . '/billingcon.php');

  $token  = $_POST['stripeToken'];
$email = $_POST['stripeEmail'];

if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$email'")) {
    if ($result->num_rows == 1){
   //none
    } elseif ($result->num_rows == 0){
       echo 'Your Email is not in Datebase hit back';
    exit;
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    
    /* free result set */
    $result->close();
}
  $customer = Stripe_Customer::create(array(
      'email' => $email,
      'card'  => $token,
      
  ));
   $cusid= $customer["id"];

  
    if ($result = $mysqli->query("UPDATE `$db`.`customer_users` SET `stripeid` = '$cusid' WHERE `customer_users`.`email` = '$email'")) {
    
}

header('Location: index.php');
exit;
?>
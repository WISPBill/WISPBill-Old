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
$mysqli = new mysqli("$ip", "$username", "$password", "$db");
$mysqlil = new mysqli("$ip", "$username", "$password", "$dbl");
// start of post
$id= $_POST["id"];
$phone = $_POST["tel"];
$email = $_POST["email"];
$l4= $_POST["l4"];
$lid= $_POST["site"];
if (empty($id)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Device was Selected';
    header('Location: linkcusdevice.php');
    exit;
}elseif (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Email Enenterd';
    header('Location: linkcusdevice.php');
    exit;
} elseif(empty($phone)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Phone Number Enenterd';
    header('Location: linkcusdevice.php');
    exit;
} elseif(empty($l4)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Last 4 Digits Enenterd';
    header('Location: linkcusdevice.php');
    exit;
} elseif(empty($lid)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Site was Selected';
    header('Location: linkcusdevice.php');
    exit;
}  else {
    // Nothing
}
$emailc = $mysqli->real_escape_string($email);
$phonec = $mysqli->real_escape_string($phone);
$l4c = $mysqli->real_escape_string($l4);
$lid = $mysqli->real_escape_string($lid);
if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
         $_SESSION['exitcodev2'] = 'Email is Not Valid';
    header('Location: linkcusdevice.php');
    exit;
  }
else{
  //do nothing 
  }
if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `email` = '$emailc' and `phone` = '$phonec'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $uid= $row["idcustomer_users"];
     $iid= $row["idcustomer_info"];
}
       /* free result set */
    $result->close();
}// end if

if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `idcustomer_users` = $uid")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["stripeid"];
}
       /* free result set */
    $result2->close();
}// end if

 $cus= Stripe_Customer::retrieve("$cid");
 $last4 = $cus->sources->data[0]->last4;

 if($last4 == $l4c){
    // if last 4 match update DB
         if ($result = $mysqli->query("UPDATE `$db`.`customer_info`
                                      SET `devices_iddevices` = '$id'
                                      WHERE `customer_info`.`idcustomer_info` = $iid;")) {
 
         }// end if
         if ($result = $mysqli->query("UPDATE `$db`.`devices` SET
                                      `location_idlocation` = '$lid',
                                      `field_status` = 'customer' WHERE
                                      `devices`.`iddevices` = $id;")) {
 
         }// end if
 }else{
     $_SESSION['exitcodev2'] = 'The Last 4 Digits are Wrong';
    header('linkcusdevice.php');
    exit;
 }
header('Location: index.php');


?>
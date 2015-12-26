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

// start of post
$phone = $_POST["tel"];
$email = $_POST["stripeEmail"];
$l4= $_POST["l4"];
$token = $_POST['stripeToken'];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Email Enenterd';
    header('Location: billcustomer.php');
    exit;
} elseif(empty($phone)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Phone Number Enenterd';
    header('Location: billcustomer.php');
    exit;
} elseif(empty($l4)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Last 4 Digits Enenterd';
    header('Location: billcustomer.php');
    exit;
} else{
    // do nothing 
} // end if

$emailc = $mysqli->real_escape_string($email);
$phonec = $mysqli->real_escape_string($phone);
$l4c = $mysqli->real_escape_string($l4);
if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['errorcode'] = 'Email is Not Valid';
    header('Location: billcustomer.php');
    exit;
  }else{
  //do nothing 
  }
// end of data sanitize and existence check
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
    // if last 4 match update Stripe
    $cu= Stripe_Customer::retrieve("$cid");
        $cu->source = "$token";
        $cu->save();

 }else{
    $_SESSION['errorcode'] = 'The Last 4 Digits are Wrong';
    header('billcustomer.php');
    exit;
 }
header('Location: index.php');
?>
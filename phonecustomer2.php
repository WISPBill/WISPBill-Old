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
$nphone = $_POST["ntel"];
$email = $_POST["email"];
$l4= $_POST["4"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: phonecustomer.php');
    exit;
} elseif(empty($phone)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'tel';
    header('Location: phonecustomer.php');
    exit;
} elseif(empty($l4)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = '4';
    header('Location: phonecustomer.php');
    exit;
} elseif(empty($nphone)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'ntel';
    header('Location:phonecustomer.php');
    exit;
}else{
    // do nothing 
} // end if

$emailc = inputcleaner($email,$mysqli);
$nphonec= inputcleaner($nphone,$mysqli);
$phonec = inputcleaner($phone,$mysqli);
$l4c = inputcleaner($l4,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'email';
    header('Location: phonecustomer.php');
    exit;
  }
else{
  //do nothing 
  }
  if ($result = $mysqli->query("SELECT * FROM  `customer_info` WHERE  `email` =  '$emailc'
AND  `phone` =  '$phonec'")) {
    /* fetch associative array */
     $numsrows = $result->num_rows;
    if ($numsrows == 0){
			 $_SESSION['exitcodev2']  = 'emailphone';
    header('Location: phonecustomer.php');
    exit;							
	 } elseif($numsrows == 1){
		// Nothing								
}
       /* free result set */
    $result->close();
}// end if
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
    // if last 4 match update DB
  if ($result = $mysqli->query("UPDATE `customer_info` SET `phone` ='$nphonec' WHERE `idcustomer_info` ='$iid'")) {
 
}// end if
 }else{
    $_SESSION['exitcodev2'] = '4';
    header('phonecustomer.php');
    exit;
 }
header('Location: index.php');
?>
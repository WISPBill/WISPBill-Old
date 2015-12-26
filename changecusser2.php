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
$email = $_POST["email"];
$l4= $_POST["l4"];
$plan = $_POST["plan"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Email Enenterd';
    header('Location: changecusser.php');
    exit;
} elseif(empty($phone)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Phone Number Enenterd';
    header('Location: changecusser.php');
    exit;
} elseif(empty($l4)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Last 4 Digits Enenterd';
    header('Location: changecusser.php');
    exit;
}else{
    // do nothing 
} // end if

$emailc = $mysqli->real_escape_string($email);
$phonec = $mysqli->real_escape_string($phone);
$l4c = $mysqli->real_escape_string($l4);
if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['errorcode'] = 'Email is Not Valid';
    header('Location: changecusser.php');
    exit;
  }
else{
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
     $uname= $row["username"];
}
       /* free result set */
    $result2->close();
}// end if

if ($result3 = $mysqli->query("SELECT * FROM `customer_plans` WHERE `idcustomer_plans` = '$plan'")) {
    /* fetch associative array */
     while ($row = $result3->fetch_assoc()) {
     $pname= $row["name"];

}
       /* free result set */
    $result3->close();
}// end if

 $cus= Stripe_Customer::retrieve("$cid");
 $last4 = $cus->sources->data[0]->last4;

 if($last4 == $l4c){
    if ($result = $mysqli->query("UPDATE `customer_info` SET `idcustomer_plans` = '$plan' WHERE `idcustomer_info` = '$iid'")) {
        $mysqlr = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
     if ($result = $mysqlr->query("UPDATE `radusergroup` SET `groupname` = '$pname' WHERE `username` = '$uname'")) {
     $subid = $cus->subscriptions->data[0]->id;
     $subscription = $cus->subscriptions->retrieve("$subid");
     $subscription->plan = "$pname";
     $subscription->save();
       header('Location: index.php');
}
}
 }else{
    $_SESSION['errorcode'] = 'The Last 4 Digits are Wrong';
    header('Location: changecusser.php');
    exit;
 }

?>
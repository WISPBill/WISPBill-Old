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
$email = $_POST["email"];
$pin = $_POST["pin"];
$plan = $_POST["plan"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: changecusser.php');
    exit;
}elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pin';
    header('Location: changecusser.php');
    exit;
}else{
    // do nothing 
} // end if

$emailc = inputcleaner($email,$mysqli);
$pinc = inputcleaner($pin,$mysqli);
$plan = inputcleaner($plan,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'email';
    header('Location: changecusser.php');
    exit;
  }
else{
  //do nothing 
  }
     if ($result = $mysqli->query("SELECT * FROM  `customer_users` WHERE  `email` =  '$emailc'
AND  `phone` =  '$phonec'")) {
    /* fetch associative array */
     $numsrows = $result->num_rows;
    if ($numsrows == 0){
			 $_SESSION['exitcodev2']  = 'emailphone';
     header('Location: changecusser.php');
    exit;							
	 } elseif($numsrows == 1){
		// Nothing								
}
       /* free result set */
    $result->close();
}// end if
// end of data sanitize and existence check

if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$emailc'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["stripeid"];
     $uname= $row["username"];
     $uid= $row["idcustomer_users"];
     $iid= $row["customer_info_idcustomer_info"];
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

$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
  if ($result = $mysqli->query("UPDATE `customer_info` SET `idcustomer_plans` = '$plan' WHERE `idcustomer_info` = '$iid'")) {
     
     $subid = $cus->subscriptions->data[0]->id;
     $subscription = $cus->subscriptions->retrieve("$subid");
     $subscription->plan = "$pname";
     $subscription->save();
       header('Location: index.php');

}
}elseif($isuser === false){
       $_SESSION['exitcodev2']  = 'pin';
       header('Location: changecusser.php');
    exit;
}else{
  echo 'Error with userverify';
  exit;
}

header('Location: index.php');
?>
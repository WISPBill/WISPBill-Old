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
$l4= $_POST["4"];
$mode = $_POST["mode"];
$plan= $_POST["plan"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: activatecustomer.php');
    exit;
} elseif(empty($phone)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'tel';
    header('Location: activatecustomer.php');
    exit;
} elseif(empty($l4)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = '4';
    header('Location: activatecustomer.php');
    exit;
} elseif(empty($mode)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'mode';
   header('Location: activatecustomer.php');
    exit;
} elseif(empty($plan)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'plan';
   header('Location: activatecustomer.php');
    exit;
}else{
    // do nothing 
} // end if

$emailc = $mysqli->real_escape_string($email);
$phonec = $mysqli->real_escape_string($phone);
$l4c = $mysqli->real_escape_string($l4);
$plan = $mysqli->real_escape_string($plan);
$mode = $mysqli->real_escape_string($mode);
if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'email';
    header('Location: activatecustomer.php');
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
    header('Location: activatecustomer.php');
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
	 $user= $row["username"];
	 $hash= $row["password"];
}
       /* free result set */
    $result2->close();
}// end if

if ($result3 = $mysqli->query("SELECT * FROM `customer_plans` WHERE `idcustomer_plans` = '$plan'")) {
    /* fetch associative array */
     while ($row = $result3->fetch_assoc()) {
     $planname= $row["name"];
     $up= $row["max_bandwith_up_kilo"];
     $down= $row["max_bandwith_down_kilo"];
}
       /* free result set */
    $result3->close();
}// end if

 $cus= Stripe_Customer::retrieve("$cid");
 $last4 = $cus->sources->data[0]->last4;

 if($last4 == $l4c){
    // if last 4 match do 
   if($mode == "radius"){
     // DO if mode radius
	 $upr = $up*1000;
	 $downr = $down*1000;
	 $radiuspass = substr("$hash", 30, 8);
     $mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
	 if ($mysqlir->query("INSERT INTO `radius`.`radcheck` (`id`, `username`, `attribute`, `op`, `value`)
                    VALUES (NULL, '$user', 'Cleartext-Password', ':= ', '$radiuspass');") === TRUE) {
	 } else{
	 echo'Something went wrong with the database please contact your webmaster';
      exit;
	 }
	 if ($mysqlir->query("INSERT INTO `$dbr`.`radreply` (`id`, `username`, `attribute`, `op`, `value`)
                                (NULL, '$user', 'WISPr-Bandwidth-Max-Down', ':=', '$downr');") === TRUE) {
            } else{
             echo'Something went wrong with the database please contact your webmaster';
                 exit;
                }
	 if ($mysqlir->query("INSERT INTO `$dbr`.`radreply` (`id`, `username`, `attribute`, `op`, `value`)
                              VALUES (NULL, '$user', 'WISPr-Bandwidth-Max-Up', ':=', '$upr');") === TRUE) {
            } else{
             echo'Something went wrong with the database please contact your webmaster';
                 exit;
                }
   }elseif($mode == "wispbill"){
     // Do WISPBill SSH Billing
          
   }else{
     echo "Error";
     exit;
   }
   // DB Update
  if ($result = $mysqli->query("UPDATE `customer_info` SET `idcustomer_plans` ='$plan' WHERE `idcustomer_info` ='$iid'")) {
 
  if ($result = $mysqli->query("UPDATE  `$db`.`customer_external` SET  `billing` =  '1',
`billing_mode` =  '$mode' WHERE  `customer_external`.`customer_info_idcustomer_info` =$iid;")) {
 
  //Stripe Enroll
     $cus->subscriptions->create(array("plan" => "$planname"));
     $cus->save();
  }// end if
}// end if
 }else{
    $_SESSION['exitcodev2'] = '4';
    header('Location: activatecustomer.php');
    exit;
 }
//header('Location: index.php');
?>
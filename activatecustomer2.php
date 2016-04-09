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
$pin= $_POST["pin"];
$mode = $_POST["mode"];
$plan= $_POST["plan"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: activatecustomer.php');
    exit;
} elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pin';
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

$emailc = inputcleaner($email,$mysqli);
   $pinc = inputcleaner($pin,$mysqli);
  $plan = inputcleaner($plan,$mysqli);
   $mode = inputcleaner($mode,$mysqli);
if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'email';
    header('Location: activatecustomer.php');
    exit;
  }
else{
  //do nothing 
  }

// end of data sanitize and existence check


if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$emailc' ")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["stripeid"];
	 $user= $row["username"];
	 $hash= $row["password"];
	 $uid= $row["idcustomer_users"];
     $iid= $row["customer_info_idcustomer_info"];
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

$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
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
       $works = ACLWhitelist($iid,$mysqli,$masterkey,$db);
	   if($works == false){
		  echo "SSH Error AClWhitelist";
		  exit;
	   }elseif($works == true){
		  //nothing
	   }else{
		  //acl did not run
		  echo "ACLWhitelist did not work";
		  exit;
	   }
   }elseif($mode == "test"){
	 // No action taken
   }else{
     echo "Error";
     exit;
   }
   // DB Update
  if ($result = $mysqli->query("UPDATE `customer_info` SET `idcustomer_plans` ='$plan' WHERE `idcustomer_info` ='$iid'")) {
 
  if ($result = $mysqli->query("UPDATE  `$db`.`customer_external` SET  `billing` =  '1',
`billing_mode` =  '$mode' WHERE  `customer_external`.`customer_info_idcustomer_info` =$iid;")) {
 
  //Stripe Enroll
  $cus= Stripe_Customer::retrieve("$cid");
     $cus->subscriptions->create(array("plan" => "$planname"));
     $cus->save();
  }// end if
}// end if

}elseif($isuser === false){
       $_SESSION['exitcodev2']  = 'pin';
       header('Location: activatecustomer.php');
    exit;
}else{
  echo 'Error with userverify';
  exit;
}

header('Location: index.php');
?>
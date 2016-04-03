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
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: deletecustomer.php');
    exit;
} elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pin';
    header('Location: deletecustomer.php');
    exit;
}else{
    // do nothing 
} // end if

$emailc = inputcleaner($email,$mysqli);
$pinc = inputcleaner($pin,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'email';
    header('Location: deletecustomer.php');
    exit;
  }
else{
  //do nothing 
  }

// end of data sanitize and existence check
if ($result2 = $mysqli->query("SELECT * FROM `customer_users`  WHERE `email` = '$emailc'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["stripeid"];
     $uname= $row["username"];
     $uid= $row["idcustomer_users"];
     $infoid= $row["customer_info_idcustomer_info"];
}
       /* free result set */
    $result2->close();
}// end if

if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = '$infoid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $cdid= $row["devices_iddevices"];
}
       /* free result set */
    $result->close();
}// end if


$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
   // Delete all info from Stripe and Our database
    if ($result = $mysqli->query("DELETE FROM `customer_info` WHERE `idcustomer_info` ='$iid'")) {
      if ($result = $mysqli->query("DELETE FROM `customer_users` WHERE `idcustomer_users` ='$uid'")) {
       $mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
      
                    $cus->delete();
    
    }
    } 
}elseif($isuser === false){
       $_SESSION['exitcodev2']  = 'pin';
          header('Location: deletecustomer.php');
    exit;
}else{
  echo 'Error with userverify';
  exit;
}
header('Location: index.php');
?>
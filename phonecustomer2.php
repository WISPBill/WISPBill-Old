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

// start of post
$nphone = $_POST["ntel"];
$email = $_POST["email"];
$pin= $_POST["pin"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: phonecustomer.php');
    exit;
}elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pin';
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
$pinc = inputcleaner($pin,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'email';
    header('Location: phonecustomer.php');
    exit;
  }
else{
  //do nothing 
  }
  if ($result = $mysqli->query("SELECT * FROM  `customer_users` WHERE  `email` =  '$emailc'")) {
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


if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$emailc'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $iid= $row["customer_info_idcustomer_info"];
}
       /* free result set */
    $result2->close();
}// end if


$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
  if ($result = $mysqli->query("UPDATE `customer_info` SET `phone` ='$nphonec' WHERE `idcustomer_info` ='$iid'")) {
 
}// end if
}elseif($isuser === false){
       $_SESSION['exitcodev2']  = 'pin';
       header('phonecustomer.php');
    exit;
}else{
  echo 'Error with userverify';
  exit;
}

header('Location: index.php');
?>
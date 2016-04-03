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
$nemail = $_POST["nemail"];
$email = $_POST["email"];
$pin= $_POST["pin"];
// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'email';
    header('Location: emailcustomer.php');
    exit;
} elseif(empty($pin)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pin';
    header('Location: emailcustomer.php');
    exit;
} elseif(empty($nemail)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'nemail';
    header('Location: emailcustomer.php');
    exit;
}else{
    // do nothing 
} // end if

$emailc = inputcleaner($email,$mysqli);
$nemailc = inputcleaner($nemail,$mysqli);
$pinc = inputcleaner($pin,$mysqli);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'email';
    header('Location: emailcustomer.php');
    exit;
  }elseif(!filter_var($nemailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['exitcodev2'] = 'nemail';
    header('Location: emailcustomer.php');
    exit;
  }else{
  //do nothing 
  }
      if ($result = $mysqli->query("SELECT * FROM  `customer_users` WHERE  `email` =  '$emailc'")) {
    /* fetch associative array */
     $numsrows = $result->num_rows;
    if ($numsrows == 0){
			 $_SESSION['exitcodev2']  = 'emailphone';
     header('Location: billcustomer.php');
    exit;							
	 } elseif($numsrows == 1){
		// Nothing								
}
       /* free result set */
    $result->close();
}// end if
// end of data sanitize and existence check

if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$nemailc'")) {
    if ($result->num_rows == 1){
    $_SESSION['exitcodev2'] = 'nemail';
    header('Location: emailcustomer.php');
    exit;
    } elseif ($result->num_rows == 0){
        // do nothing 
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    
    /* free result set */
    $result->close();
}


$isuser = userverify($emailc,$pinc,$mysqli);

if($isuser === true){
  
    if ($result = $mysqli->query("UPDATE `customer_users` SET `email` = '$nemailc' WHERE  `email` =  '$emailc'")) {
        

}// end if
  
}elseif($isuser === false){
       $_SESSION['exitcodev2']  = 'pin';
       header('emailcustomer.php');
    exit;
}else{
  echo 'Error with userverify';
  exit;
}

header('Location: index.php');
?>
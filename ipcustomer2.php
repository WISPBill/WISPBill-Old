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
$phone = $_POST["tel"];
$email = $_POST["email"];
$ip= $_POST["ip"];

// end of post
// start of data sanitize and existence check
 if (empty($email)) {
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Email Enenterd';
    header('Location: ipcustomer.php');
    exit;
} elseif(empty($phone)){
    // If phone is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No Phone Number Enenterd';
    header('Location: ipcustomer.php');
    exit;
} elseif(empty($ip)){
    // If Last 4 is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'No IP Address Enenterd';
    header('Location: ipcustomer.php');
    exit;
}else{
    // do nothing 
} // end if

$emailc = $mysqli->real_escape_string($email);
$phonec = $mysqli->real_escape_string($phone);
$ipc = $mysqli->real_escape_string($ip);

if(!filter_var($emailc, FILTER_VALIDATE_EMAIL)){
     $_SESSION['errorcode'] = 'Email is Not Valid';
    header('Location: ipcustomer.php');
    exit;
  }elseif(!filter_var($ipc, FILTER_VALIDATE_IP)){
     $_SESSION['errorcode'] = 'IP Address is Not Valid';
    header('Location: ipcustomer.php');
    exit;
  }else{
  //do nothing 
  }
// end of data sanitize and existence check
if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `email` = '$emailc' and `phone` = '$phonec'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $uid= $row["idcustomer_users"];
}
       /* free result set */
    $result->close();
}// end if

if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `idcustomer_users` = $uid")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $uname= $row["username"];
}
       /* free result set */
    $result2->close();
}// end if
$mysqlr = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");

if ($result = $mysqlr->query("SELECT * FROM `radreply` WHERE `value` = '$ipc'")) {
    if ($result->num_rows == 1){
    $_SESSION['errorcode'] = 'IP Address is Already in Use';
    header('Location: ipcustomer.php');
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
if ($result = $mysqlr->query("INSERT INTO `$dbr`.`radreply` (`id`, `username`, `attribute`, `op`, `value`)
                             VALUES (NULL, '$uname', 'Framed-IP-Address', ':=', '$ipc');")) {
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    header('Location: index.php');
?>
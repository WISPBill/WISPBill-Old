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
$user = $_POST["username"];
$pass1 = $_POST["password"];
$pass2 = $_POST["password2"];
$infoid = $_POST["id"];

// end of post

// start of data sanitize and existence check
 if (empty($user)) {
    // If username is empty it goes back to the fourm and informs the user
     $_SESSION['exitcodev2'] = 'name';
    header('Location: convertlead.php');
    exit;
} elseif(empty($pass1)){
    // If password is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pass';
    header('Location: convertlead.php');
    exit;
}
elseif(empty($pass2)){
    // If password is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pass';
    header('Location: convertlead.php');
    exit;
}
elseif(empty($infoid)){
    // If email is empty it goes back to the fourm and informs the user
   $_SESSION['exitcodev2'] = 'lead';
    header('Location: convertlead.php');
    exit;
}
else{
    // do nothing 
} // end if

$user = $mysqli->real_escape_string($user);
$pass1 = $mysqli->real_escape_string($pass1);
$pass2 = $mysqli->real_escape_string($pass2);

// end of data sanitize and existence check
if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = $infoid")) {
      /* fetch associative array */
      
    while ($row = $result->fetch_assoc()) {
     $email= $row["email"];
    }
}
//start of password match
if($pass1 == $pass2){
    // do nothing 
} else {
    // If password match fails it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pass';
    header('Location: convertlead.php');
    exit;
}
// end if and password match

// start of cheack for exsting username and or email 

if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `username` = '$user'")) {
    if ($result->num_rows == 1){
   $_SESSION['exitcodev2'] = 'name';
    header('Location: convertlead.php');
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
if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$email'")) {
    if ($result->num_rows == 1){
   $_SESSION['exitcodev2'] = 'name';
    header('Location: convertlead.php');
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
// end of cheack for exsting username and or email
//start of hashing
$hash= password_hash("$pass1", PASSWORD_DEFAULT);
// end of hashing

//start of data entry
if ($mysqli->query("INSERT INTO `$db`.`customer_users` (`idcustomer_users`, `username`, `password`, `email`, `stripeid`)
                   VALUES (NULL, '$user', '$hash', '$email', NULL);") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}
if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$email'")) {
      /* fetch associative array */
      
    while ($row = $result->fetch_assoc()) {
     $cusid= $row["idcustomer_users"];
    }
}
if ($mysqli->query("UPDATE `$db`.`customer_info`
                   SET `idcustomer_users` = '$cusid' WHERE
                   `customer_info`.`idcustomer_info` = $infoid;") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
header('Location: index.php');
?>
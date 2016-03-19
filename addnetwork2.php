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
$ip = $_POST["ip"];
$mask = $_POST["mask"];

// end of post

// start of data sanitize and existence check
 if (empty($ip)) {
    // If name is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'ip';
    header('Location: addnetwork.php');
    exit;
} elseif(empty($mask)){
    // If price is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2']= 'mask';
    header('Location: addnetwork.php');
    exit;
}else{
    // do nothing 
} // end if

 $ip = inputcleaner($ip,$mysqli);
  $mask = inputcleaner($mask,$mysqli);

if (filter_var($ip, FILTER_VALIDATE_IP)) {
 // ip is valid
}else{
  // ip is not valid 
   $_SESSION['exitcodev2'] = 'ip';
    header('Location: addnetwork.php');
}

if(is_numeric($mask)){
  // mask is int
}else{
  // mask in not int 
   $_SESSION['exitcodev2']= 'mask';
    header('Location: addnetwork.php');
}
// end of data sanitize and existence check
//start of data entry for system DB
if ($mysqli->query("INSERT INTO `$db`.`ip_networks` (`idip_networks`, `network`, `subnet`) VALUES (NULL, '$ip', '$mask');") === TRUE) {
} else{
   echo'Something went wrong with the database please contact your webmaster';
      exit;
}

header('Location: index.php');
// end of file
?>
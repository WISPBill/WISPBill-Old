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
$up = $_POST["up"];
$down = $_POST["down"];
$id = $_POST["id"];
// end of post

// start of data sanitize and existence check
if(empty($up)){
    // If up is empty it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'no up';
    header('Location: speedplan.php');
    exit;
}
elseif(empty($down)){
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'no down';
    header('Location: speedplan.php');
    exit;
} elseif(empty($id)){
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'no id';
    header('Location: speedplan.php');
    exit;
}else{
    // do nothing 
} // end if

$up = $mysqli->real_escape_string($up);
$down = $mysqli->real_escape_string($down);
$id = $mysqli->real_escape_string($id);
// end of data sanitize and existence check
if ($result = $mysqli->query("SELECT * FROM `customer_plans` WHERE `idcustomer_plans` ='$id'")) {
      /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
    $name= $row["name"];
    }
}
//start of data entry for system DB
if ($mysqli->query("UPDATE `wispbill`.`customer_plans` SET `max_bandwith_up_kilo` = '$up', `max_bandwith_down_kilo`
                   = '$down' WHERE `customer_plans`.`idcustomer_plans` = $id;") === TRUE) {
} else{
   echo'Something went wrong with the database please contact your webmaster';
      exit;
}
// end of data entry for system DB
// start of bandwith convert for radius
$upr = $up*1000;
$downr = $down*1000;
// end of bandwith convert for radius
//start of data entry for radius 
$mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
//bandwith up
if ($mysqlir->query("UPDATE `radgroupreply` SET `value` = '$upr' WHERE
                    `groupname` ='$name' and `attribute` = 'WISPr-Bandwidth-Max-Up'") === TRUE) {
} else{
   echo'Something went wrong with the database please contact your webmaster';
      exit;
}
//bandwith down
if ($mysqlir->query("UPDATE `radgroupreply` SET `value` = '$downr' WHERE
                    `groupname` ='$name' and `attribute` = 'WISPr-Bandwidth-Max-Down'") === TRUE) {
} else{
   echo'Something went wrong with the database please contact your webmaster';
      exit;
}
// end of data entry for radius

header('Location: index.php');
// end of file
?>
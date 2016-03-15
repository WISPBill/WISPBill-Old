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
$adminid = $_SESSION['adminid'];
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

// start of post
$note = $_POST["text1"];
$id = $_POST["id"];
// end of post
// start of data sanitize and existence check
 if (empty($note)) {
    // If note is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'note';
    header('Location: createticketnote.php');
    exit;
} elseif(empty($id)){
    // If id is empty it goes back to the fourm and informs the user
    $_SESSION['errorcode'] = 'con';
    header('Location: createticketnote.php');
    exit;
} else{
    // do nothing 
} // end if

$id = inputcleaner($id,$mysqli);
$note = inputcleaner($note,$mysqli);
// end of data sanitize and existence check

$time = time();

if ($result = $mysqli->query("INSERT INTO `$db`.`ticket_note` (`idticket_notes`, `ticket_idticket`,
`note`, `date`, `admin_users_idadmin`) VALUES (NULL, '$id', '$note', '$time', '$adminid');")) {
    /* fetch associative array */
   

} else {
   echo 'DB ERROR';
  exit;
}// end if

if ($result3 = $mysqli->query("SELECT * FROM  `ticket` WHERE `idticket` = '$id'")) {
     while ($row3 = $result3->fetch_assoc()) {
     $cusid= $row3["customer_info_idcustomer_info"];
     }
     }

 if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Note added to Ticket', '$time', '$adminid', '$cusid', '$id');")) {

} else {
   echo 'DB ERROR';
  exit;
}// end if
header('Location: index.php');
?>
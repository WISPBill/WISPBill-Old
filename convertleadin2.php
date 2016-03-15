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

$id = $_POST["id"];

 if (empty($id)) {
    $_SESSION['exitcodev2'] = 'id';
    header('Location: convertleadin.php');
    exit;
 }else {
    // Nothing 
 }
 
 $leadidid = inputcleaner($leadidid,$mysqli);

 /*0 unassigned
  *1 assigned but not solved
  *2 assigned and solved
  *3 assigned and escalation needed
  *4 solved with escalation 
  */
 
 if ($result = $mysqli->query("INSERT INTO `$db`.`ticket` (`idticket`, `issue`,
`status`, `admin_users_idadmin`, `customer_info_idcustomer_info`) VALUES
(NULL, 'Install', '0', '$adminid', '$leadid');")) {

} else {
   echo 'DB ERROR';
  exit;
}// end if

if ($result3 = $mysqli->query("SELECT * FROM  `ticket` WHERE  `issue` =  'Install'
AND  `admin_users_idadmin` =  '$adminid' AND  `customer_info_idcustomer_info` =  '$leadid'")) {
     while ($row3 = $result3->fetch_assoc()) {
     $ticketid= $row3["idticket"];
     }
     }
$time = time();
 if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Install Ticket Created', '$time', '$adminid', '$leadid', '$ticketid');")) {

} else {
   echo 'DB ERROR';
  exit;
}// end if
header('Location: index.php');
?>
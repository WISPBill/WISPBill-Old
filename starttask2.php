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
    header('Location: starttask.php');
    exit;
 }else {
    // Nothing 
 }

 $id = $mysqli->real_escape_string($id);

 /*0 unassigned
  *1 assigned but not solved
  *2 assigned and solved
  *3 assigned and escalation needed
  *4 solved with escalation 
  */
 
  if ($result2 = $mysqli->query("SELECT * FROM `ticket` WHERE `idticket` = '$id'")) {
     while ($row2 = $result2->fetch_assoc()) {
     $cusinfoid= $row2["customer_info_idcustomer_info"];
     }
     }
 
     $time = time();
     
     if ($result = $mysqli->query("UPDATE  `$db`.`tasks` SET  `real_start_date_time` =  '$time'
WHERE  `ticket_idticket` = '$id'") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if 
     
 if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Task Started', '$time', '$adminid', '$cusinfoid', '$id');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

header('Location: index.php');
?>
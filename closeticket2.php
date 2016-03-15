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
$status = $_POST["status"];

 if (empty($id)) {
    $_SESSION['exitcodev2'] = 'id';
    header('Location: closeticket.php');
    exit;
 }elseif(empty($status)) {
      $_SESSION['exitcodev2'] = 'status';
    header('Location: closeticket.php');
    exit;
 }else{
    // Nothing
 }


  $id = inputcleaner($id,$mysqli);
$status = inputcleaner($status,$mysqli);

  if(is_numeric($status)){
    //nothing
  }else{
    // Input is not what the system expects
    $_SESSION['exitcodev2'] = 'status';
    header('Location: closeticket.php');
    exit;
  }
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
     
 if ($result = $mysqli->query("UPDATE  `$db`.`ticket` SET  `status` =  '$status' WHERE  `ticket`.`idticket` ='$id';") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

if($status % 2 == 0){
    // Status is even so ticket is solved
     if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Ticket Solved', '$time', '$adminid', '$cusinfoid', '$id');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if
}else{
    //  Input is not what the system expects no action
}

header('Location: index.php');
?>
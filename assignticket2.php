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

$adminid = $_SESSION['adminid'];

$assignto= $_POST['adminid'];

if (empty($_POST['id'])) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'id';
    header('Location: assignticket.php');
    exit;
} elseif(empty($assignto)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'admin';
     header('Location: assignticket.php');
    exit;
}else{
    
}

 $assignto = inputcleaner($assignto,$mysqli);

foreach ($_POST['id'] as $id) {
    /*0 unassigned
  *1 assigned but not solved
  *2 assigned and solved
  *3 assigned and escalation needed
  *4 solved with escalation 
	Odd is unsloved and even is solved
  */
    $id = inputcleaner($id,$mysqli);

    if ($result2 = $mysqli->query("SELECT * FROM `ticket` WHERE `idticket` = '$id'")) {
     while ($row2 = $result2->fetch_assoc()) {
     $cusinfoid= $row2["customer_info_idcustomer_info"];
     }
     }
    
    $time = time();
 if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Ticket Assigned', '$time', '$adminid', '$cusinfoid', '$id');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

 if ($result = $mysqli->query("UPDATE  `$db`.`ticket` SET  `status` =  '1' WHERE  `ticket`.`idticket` ='$id';") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

	$notimesgpart2 = $mysqli->real_escape_string("<a href='viewticket.php?id=$id'>View it Now</a>");
	
 if ($result = $mysqli->query("INSERT INTO `$db`.`notifications` (`idnotifications`, `readyn`, `content`,
`date`, `fromwho`, `towho`) VALUES (NULL, '0', 'You have been assigned a ticket $notimesgpart2', CURRENT_TIMESTAMP, '$adminid', '$assignto');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

 if ($result = $mysqli->query("INSERT INTO `$db`.`tasks` (`idtasks`, `task`, `start_date_time`,
`end_date_time`, `real_start_date_time`, `real_end_date_time`, `task_length`, `admin_users_idadmin`,
`ticket_idticket`) VALUES (NULL, 'Install', NULL, NULL, NULL, NULL, NULL, '$assignto', '$id');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

} // end of foreach 
header('Location: index.php');
?>
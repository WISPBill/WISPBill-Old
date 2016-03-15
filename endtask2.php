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
    header('Location: starttask.php');
    exit;
 }elseif(empty($status)) {
      $_SESSION['exitcodev2'] = 'status';
    header('Location: starttask.php');
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
    header('Location: starttask.php');
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
     
      if ($result2 = $mysqli->query("SELECT * FROM `tasks` WHERE `ticket_idticket` = '$id'")) {
     while ($row2 = $result2->fetch_assoc()) {
     $realstart= $row2["real_start_date_time"];
     }
     }
 
     $time = time();

     $tasklength = $time - $realstart;
     
 if ($result = $mysqli->query("UPDATE  `$db`.`tasks` SET  `real_end_date_time` =  '$time',
`task_length` =  '$tasklength' WHERE  `ticket_idticket` = '$id'") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

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
}elseif ($status == '3'){
    
     $notimesgpart2 = $mysqli->real_escape_string("<a href='viewticket.php?id=$id'>View it Now</a>");
	
 if ($result = $mysqli->query("INSERT INTO `$db`.`notifications` (`idnotifications`, `readyn`, `content`,
`date`, `fromwho`, `towho`) VALUES (NULL, '0', 'A ticket needs escalation $notimesgpart2', CURRENT_TIMESTAMP, '$adminid', 'all');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Ticket Marked For Escalation', '$time', '$adminid', '$cusinfoid', '$id');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

}else{
    //  Input is not what the system expects no action
}

 if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = '$cusinfoid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $email= $row["email"];
 }
       /* free result set */
    $result->close();
 }// end if

$emailfilldata = array(
    "status" => "$status",
);

mailuser($email,'endtask',$sendgridapi,$fromemail,$emailfilldata);

header('Location: index.php');
?>
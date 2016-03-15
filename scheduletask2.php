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
$time = $_POST["time"];

 if (empty($id)) {
    $_SESSION['exitcodev2'] = 'id';
    header('Location: scheduletask.php');
    exit;
 }elseif(empty($time)){
    $_SESSION['exitcodev2'] = 'time';
    header('Location: scheduletask.php');
    exit;
 }else {
    // Nothing 
 }
 $timerange = $time;
//Split time into start and end
 $time = explode("-",$time);
 
 if($time == false){
     $_SESSION['exitcodev2'] = 'time';
    header('Location: scheduletask.php');
    exit;
 }else{

 $starttime = $time["0"];
 $endtime = $time["1"];
 

 $starttime = strtotime($starttime);
 $endtime = strtotime($endtime);
 }
 
 $id = inputcleaner($id,$mysqli);
 $endtime = inputcleaner($endtime,$mysqli);
 $starttime = inputcleaner($starttime,$mysqli);
 
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
 if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Ticket Task Scheduled', '$time', '$adminid', '$cusinfoid', '$id');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

 if ($result = $mysqli->query("UPDATE  `$db`.`tasks` SET  `start_date_time` =  '$starttime',
`end_date_time` =  '$endtime' WHERE  `ticket_idticket` = '$id'") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

 if ($result = $mysqli->query("INSERT INTO `$db`.`notifications` (`idnotifications`, `readyn`, `content`,
`date`, `fromwho`, `towho`) VALUES (NULL, '0', 'You have a task to do on $timerange', CURRENT_TIMESTAMP, 'system', '$adminid');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

 if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = '$cusinfoid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $email= $row["email"];
 }
       /* free result set */
    $result->close();
 }// end if

$emailfilldata = array(
    "start" => "$starttime",
    "end" => "$endtime",
);
mailuser($email,'task',$sendgridapi,$fromemail,$emailfilldata);

header('Location: index.php');
?>
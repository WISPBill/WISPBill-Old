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

$passdata = $_SESSION['troubleshooting'];

if(empty($passdata)){
    header('Location: troubleshooting.php');
}else{
    $cusinfoid = $passdata["id"];
    $issue = $passdata["issue"];
     $thisticketid = $passdata["ticket"];
}

$adminid = $_SESSION['adminid'];
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

$status = $_POST["status"];
$note = $_POST["text1"];

 if (empty($status)) {
    $_SESSION['exitcodev2'] = 'status';
    header('Location: troubleshooting3.php');
    exit;
 }elseif(empty($note)) {
      $_SESSION['exitcodev2'] = 'note';
    header('Location: troubleshooting3.php');
    exit;
 }else{
    // Nothing
 }

 $note = $mysqli->real_escape_string($note);
  $status= $mysqli->real_escape_string($status);

  if(is_numeric($status)){
    //nothing
  }else{
    // Input is not what the system expects
    $_SESSION['exitcodev2'] = 'status';
        header('Location: troubleshooting3.php');
    exit;
  }
 /*0 unassigned
  *1 assigned but not solved
  *2 assigned and solved
  *3 assigned and escalation needed
  *4 solved with escalation 
  */
 
$time = time();

if ($result = $mysqli->query("INSERT INTO `$db`.`ticket_note` (`idticket_notes`, `ticket_idticket`,
`note`, `date`, `admin_users_idadmin`) VALUES (NULL, '$thisticketid', '$note', '$time', '$adminid');")) {
    /* fetch associative array */
   
} else {
   echo 'DB ERROR';
  exit;
}// end if

 if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Note added to Ticket', '$time', '$adminid', '$cusinfoid', '$thisticketid');")) {

} else {
   echo 'DB ERROR';
  exit;
}// end if

if ($result = $mysqli->query("UPDATE  `$db`.`ticket` SET  `status` =  '$status' WHERE  `ticket`.`idticket` ='$thisticketid';") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

if($thisticketid == '0'){

  $notimesgpart2 = $mysqli->real_escape_string("<a href='viewticket.php?id=$thisticketid'>View it Now</a>");
	
 if ($result = $mysqli->query("INSERT INTO `$db`.`notifications` (`idnotifications`, `readyn`, `content`,
`date`, `fromwho`, `towho`) VALUES (NULL, '0', 'A Troubleshooting Ticket Created $notimesgpart2', CURRENT_TIMESTAMP, '$adminid', 'all');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

header('Location: index.php');
exit;
}elseif($status % 2 == 0){
    // Status is even so ticket is solved
     if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Ticket Solved', '$time', '$adminid', '$cusinfoid', '$thisticketid');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if
}elseif ($status == '3'){
    
     $notimesgpart2 = $mysqli->real_escape_string("<a href='viewticket.php?id=$thisticketid'>View it Now</a>");
	
 if ($result = $mysqli->query("INSERT INTO `$db`.`notifications` (`idnotifications`, `readyn`, `content`,
`date`, `fromwho`, `towho`) VALUES (NULL, '0', 'A ticket needs escalation $notimesgpart2', CURRENT_TIMESTAMP, '$adminid', 'all');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

if ($result = $mysqli->query("INSERT INTO `$db`.`history`
(`idhistory`, `event`, `date`, `admin_users_idadmin`, `customer_info_idcustomer_info`, `ticket_idticket`)
VALUES (NULL, 'Ticket Marked For Escalation', '$time', '$adminid', '$cusinfoid', '$thisticketidid');") === TRUE){

} else {
   echo 'DB ERROR';
  exit;
}// end if

}else{
    //  Input is not what the system expects no action
}

header('Location: index.php');
?>
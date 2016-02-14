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
require_once('./fileloader.php');
require_once('./billingcon.php');

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input, true);

// Asks stripe for data to ensure that charge has failed and it is not a false messeage

$id = $event_json["id"];
$event= Stripe_Event::retrieve("$id");

    //making sure id is a fail charge
      $data= $event["data"];
      $data2= $data["object"];
      $cusid= $data2["customer"];
      
if($event["type"] == 'charge.failed' ){
 
      if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `stripeid` = '$cusid'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["idcustomer_users"];
	 $user= $row["username"];
 }
       /* free result set */
    $result2->close();
 }// end if
 
      if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_users` = '$cid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $did= $row["devices_iddevices"];
     $iid= $row["idcustomer_info"];
 }
       /* free result set */
    $result->close();
 }// end if
 
  if ($result = $mysqli->query("SELECT * FROM  `customer_external` 
WHERE  `customer_info_idcustomer_info` =  '$iid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $mode= $row["billing_mode"];
     $status= $row["billing"];
 }
       /* free result set */
    $result->close();
 }// end if
 
 if($status == '1'){
  // Customer has had falied charge for first time
  if($mode == 'radius'){
   //Radius Billing User
   $mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
   
   if ($mysqlir->query("INSERT INTO `$dbr`.`radreply` (`id`, `username`, `attribute`, `op`, `value`)
                                VALUES (NULL, '$user', 'WISPr-Redirection-URL', ':=', '$nopayurl');") === TRUE) {
            } else{
            http_response_code(500);
                 exit;
            }
    if ($mysqli->query("UPDATE  `$db`.`customer_external` SET  `billing` =  '0' WHERE
   `customer_external`.`customer_info_idcustomer_info` =$iid;") === TRUE) {

   }else{
   http_response_code(500);
   exit;
   }
  }elseif($mode == 'wispbill'){
   //SSH billing user
   $works = nopayset($iid,$mysqli,$masterkey,$db);
   if($works == false){
    $works = nopayset($iid,$mysqli,$masterkey,$db);
       if($works == false){
       http_response_code(500);
       }elseif($works == true){
       //Nothing
       }else{
       http_response_code(500);
       exit;
       }

   }elseif($works == true){
    //Nothing
   }else{
    http_response_code(500);
    exit;
   }
  }
 }elseif($status == '0'){
  // Customer is already behind we don't need to do anything
  
 }else{
  http_response_code(500); 
 }
        
    //making sure id is a charge 
}elseif($event["type"] == 'charge.succeeded' ){
        // Get cus id 
 if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `stripeid` = '$cusid'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["idcustomer_users"];
	 $user= $row["username"];
 }
       /* free result set */
    $result2->close();
 }// end if
 
      if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_users` = '$cid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $did= $row["devices_iddevices"];
     $iid= $row["idcustomer_info"];
 }
       /* free result set */
    $result->close();
 }// end if
 
  if ($result = $mysqli->query("SELECT * FROM  `customer_external` 
WHERE  `customer_info_idcustomer_info` =  '$iid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $mode= $row["billing_mode"];
     $status= $row["billing"];
 }
       /* free result set */
    $result->close();
 }// end if
 
 if($status == '1'){
  // Customer has been paying there bill there is nothing to do
  
 }elseif($status == '0'){
  // Customer has payed is bill we need undo
  if($mode == 'radius'){
   //Radius Billing User
   $mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
   
   
   $mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
   
   if ($mysqlir->query("DELETE FROM `radreply` WHERE `username`='$user' and `attribute` = 'WISPr-Redirection-URL'") === TRUE) {
            } else{
            http_response_code(500);
                 exit;
            }
    if ($mysqli->query("UPDATE  `$db`.`customer_external` SET  `billing` =  '1' WHERE
   `customer_external`.`customer_info_idcustomer_info` =$iid;") === TRUE) {

   }else{
   http_response_code(500);
   exit;
   }
   
  }elseif($mode == 'wispbill'){
   //SSH billing user
   $works = nopayunset($iid,$mysqli,$masterkey,$db);
   if($works == false){
    $works = nopayunset($iid,$mysqli,$masterkey,$db);
       if($works == false){
       http_response_code(500);
       }elseif($works == true){
       if ($mysqli->query("UPDATE  `$db`.`customer_external` SET  `billing` =  '1' WHERE
   `customer_external`.`customer_info_idcustomer_info` =$iid;") === TRUE) {

   }else{
   http_response_code(500);
   exit;
   }
       }else{
       http_response_code(500);
       exit;
       }

   }elseif($works == true){
    if ($mysqli->query("UPDATE  `$db`.`customer_external` SET  `billing` =  '1' WHERE
   `customer_external`.`customer_info_idcustomer_info` =$iid;") === TRUE) {

   }else{
   http_response_code(500);
   exit;
   }
   }else{
    http_response_code(500);
    exit;
   }
  }
 }else{
  http_response_code(500); 
 }
 
 }elseif($event["type"] == 'invoice.payment_succeeded'){
  // Send Email
  
   if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `stripeid` = '$cusid'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["idcustomer_users"];
 }
       /* free result set */
    $result2->close();
 }// end if
 
    if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_users` = '$cid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $email= $row["email"];
 }
       /* free result set */
    $result->close();
 }// end if
 mailuser($email,'receipt',$sendgridapi,$fromemail);
 }elseif($event["type"] == 'invoice.payment_failed'){
  // Send Email
  
   if ($result2 = $mysqli->query("SELECT * FROM `customer_users` WHERE `stripeid` = '$cusid'")) {
    /* fetch associative array */
     while ($row = $result2->fetch_assoc()) {
     $cid= $row["idcustomer_users"];
 }
       /* free result set */
    $result2->close();
 }// end if
 
    if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_users` = '$cid'")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
     $email= $row["email"];
 }
       /* free result set */
    $result->close();
 }// end if
 mailuser($email,'fail',$sendgridapi,$fromemail);
}else{
    // nothing happens if it is not a charge event
    
} // end if
http_response_code(200); 
?>
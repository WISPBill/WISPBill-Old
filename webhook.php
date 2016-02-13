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

// Looking for failed charge

if($event_json["type"] == 'charge.failed' ){
    // stuff happens here if charge failed
    
    // Asks stripe for data to ensure that charge has failed and it is not a false messeage
    $id = $event_json["id"];
    $event= Stripe_Event::retrieve("$id");
    
    //making sure id is a fail charge 
    if($event["type"] == 'charge.failed' ){
        // Get cus id 
        
        $data= $event["data"];
        $data2= $data["object"];
        $cusid= $data2["customer"];
        
        // Get cus email
        
        $cus= Stripe_Customer::retrieve("$cusid");
        $email = $cus["email"];
        $mysqli = new mysqli("$ip", "$username", "$password", "$db");
        if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$email'")) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
            $user= $row["username"];
							$userid $row["idcustomer_users"];
              }
				}
					if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_users` = '$userid'")) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
            $infoid= $row["idcustomer_info"];
              }
					}
					
					if ($result = $mysqli->query("SELECT * FROM `customer_external` WHERE `customer_info_idcustomer_info` = '$infoid'")) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
            $mode = $row["billing_mode"];
						$status = $row["billing"];
              }
					}
					if($mode == 'radius'){
            // Start of Suspend
						if($status = '1'){
            $mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
            if ($mysqlir->query("INSERT INTO `$dbr`.`radreply` (`id`, `username`, `attribute`, `op`, `value`)
                                VALUES (NULL, '$user', 'WISPr-Redirection-URL', ':=', '$nopayurl');") === TRUE) {
            } else{
             echo'Something went wrong with the database please contact your webmaster';
                 exit;
                }
						}else{
							// Cus is alread
						}
        }elseif($mode == 'wispbill'){
						// WISPBIll
					}
				}
        
        http_response_code(200); 
    }else{
    // nothing happens if it is not a failed charge event
    http_response_code(200); 
    } // end  nested if 

} elseif($event_json["type"] == 'charge.succeeded' ){
    // stuff happens here if charge 
    
    // Asks stripe for data to ensure that charge is not a false messeage
    $id = $event_json["id"];
    $event= Stripe_Event::retrieve("$id");
    
    //making sure id is a charge 
    if($event["type"] == 'charge.succeeded' ){
        // Get cus id 
        
        $data= $event["data"];
        $data2= $data["object"];
        $cusid= $data2["customer"];
        
        // Get cus email
        
        $cus= Stripe_Customer::retrieve("$cusid");
        $email = $cus["email"];
        $mysqli = new mysqli("$ip", "$username", "$password", "$db");
        if ($result = $mysqli->query("SELECT * FROM `customer_users` WHERE `email` = '$email'")) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
            $user= $row["username"];
              }
            // Start of Unsuspend
            $mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");
            
            if ($result2 = $mysqlir->query("SELECT * FROM `radreply` WHERE `username` = '$user'")) {
            if ($result2->num_rows >= 1){
            // Delete it 
             if ($mysqlir->query("DELETE FROM `radreply` WHERE `username`='$user' and `attribute` = 'WISPr-Redirection-URL'") === TRUE) {
            } else{
             echo'Something went wrong with the database please contact your webmaster';
                 exit;
                }
             exit;
            } elseif ($result2->num_rows == 0){
            // do nothing 
          } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
         }
    
    /* free result set */
    $result2->close();
    }// end of  Unsuspend
            
         /* free result set */
         $result->close();
         
        } else {
          // There is an error with SQL or Inputs 
         http_response_code(300);
        exit;
        } //end if

        http_response_code(200); 
    }else{
    // nothing happens if it is not a  charge event
    http_response_code(200); 
    } // end  nested if 

}

else{
    // nothing happens if it is not a charge event
    http_response_code(200); 
} // end if

?>
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

	
    $id = $mysqli->real_escape_string($_GET['choice']);

if(empty($id)){
    //No id
    echo "No Data Available";
    exit;
}else{
    echo '
    <h3>General Ticket Data</h3>
    <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
				  <th>Issue</th> 
				  <th>Customer</th>
                  <th>Created By</th> 
                 <th>Creation Date</td>
                </tr>
                </thead>
                <tbody>';
                
                if ($result = $mysqli->query("SELECT * FROM `ticket` WHERE `idticket` = '$id'")) {
      /* fetch associative array */
         
    while ($row = $result->fetch_assoc()) {
      $id= $row["idticket"];
      $issue= $row["issue"];
     $cusinfoid= $row["customer_info_idcustomer_info"];
     $adminid= $row["admin_users_idadmin"];
     if ($result2 = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = '$cusinfoid'")) {
     while ($row2 = $result2->fetch_assoc()) {
     $fname= $row2["fname"];
     $lname= $row2["lname"];
     }
     }
     if ($result3 = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = '$adminid'")) {
     while ($row3 = $result3->fetch_assoc()) {
     $afname= $row3["fname"];
     $alname= $row3["lname"];
     }
     }
     if ($result3 = $mysqli->query("SELECT * FROM  `history` 
WHERE  `ticket_idticket` =  '$id' ORDER BY  `history`.`date` ASC 
LIMIT 0 , 1")) {
     while ($row3 = $result3->fetch_assoc()) {
     $cdate= $row3["date"];
     }
     }

     $cdate = date('n/j/y  g:i A',"$cdate");
     echo" <tr>
    <td>$issue</td> 
    <td>$fname $lname</td>
    <td>$afname $alname</td>
    <td>$cdate</td>
  </tr>";
    }
}
echo '</tbody>
                <tfoot>
                <tr>
                   
				  <th>Issue</th> 
				  <th>Customer</th>
                  <th>Created By</th> 
                <th>Creation Date</th>
                </tr>
                </tfoot>
              </table>
              
              <br></br>
               <h3>Ticket Notes</h3>
              <table id="example3" class="table table-bordered table-striped">
                <thead>
                <tr> 
				  <th>Note</th>
				  <th>Date</th> 
				 <th>By Whom</th>
                </tr>
                </thead>
                <tbody>';
				
 if ($result = $mysqli->query("SELECT * FROM `ticket_note` WHERE `ticket_idticket` = '$id'")) {
    /* fetch associative array */
    foreach ($result as $row){
		 $note = $row["note"];
        $date = $row["date"];
		 $taid = $row["admin_users_idadmin"];
			 $date = date('n/j/y  g:i A',"$date");
					if ($result3 = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = $taid")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
				
                     $afname= $row3["fname"];
     $alname= $row3["lname"];
					
					 }
  
					$result3->close();
						}else{
						 echo'Something went wrong with the database please contact your webmaster';
							exit;
					}
		// Echo Data
		echo "<tr>
		<td>$note</td>
		<td>$date</td>
		<td>$afname $alname</td>
		</tr>";
	}
  
    $result->close();
}else{
       echo'Something went wrong with the database please contact your webmaster';
       exit;
    }
  

echo '
                </tbody>
                <tfoot>
                <tr>
				  <th>Note</th>
				  <th>Date</th> 
				 <th>By Whom</th>
                </tr>
                </tfoot>
              </table>
              
                <br></br>
                <h3>Ticket History</h3>
              <table id="example4" class="table table-bordered table-striped">
                <thead>
                <tr> 
				  <th>Note</th>
				  <th>Date</th> 
				 <th>By Whom</th>
                </tr>
                </thead>
                <tbody>';
				
 if ($result = $mysqli->query("SELECT * FROM  `history` WHERE  `ticket_idticket` =  '$id'
                              ORDER BY  `history`.`date` ASC ")) {
    /* fetch associative array */
    foreach ($result as $row){
		 $note = $row["event"];
        $date = $row["date"];
		 $taid = $row["admin_users_idadmin"];
			 $date = date('n/j/y  g:i A',"$date");
					if ($result3 = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = $taid")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
				 $afname= $row3["fname"];
                    $alname= $row3["lname"];
					
					 }
  
					$result3->close();
						}else{
						 echo'Something went wrong with the database please contact your webmaster';
							exit;
					}
		// Echo Data
		echo "<tr>
		<td>$note</td>
		<td>$date</td>
		<td>$afname $alname</td>
		</tr>";
	}
  
    $result->close();
}else{
       echo'Something went wrong with the database please contact your webmaster';
       exit;
    }
  

echo '
                </tbody>
                <tfoot>
                <tr>
				  <th>Note</th>
				  <th>Date</th> 
				 <th>By Whom</th>
                </tr>
                </tfoot>
              </table>
              ';
              
}
?>
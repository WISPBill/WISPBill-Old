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

if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE
                             `idcustomer_users` is not NULL
                             and `idcustomer_plans` is not NULL")) {
      /* fetch associative array */
      $numrow = $result->num_rows;
     echo "<h1>We have $numrow Customers</h1>";
      echo'
      <table border="1" style="width:100%">
      <tr>
    <td>First Name</td> 
    <td>Last Name</td>
    <td>Phone</td>
    <td>Address</td> 
    <td>City</td>
    <td>Zip Code</td>
     <td>Email</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $fname= $row["fname"];
     $lname= $row["lname"];
     $phone= $row["phone"];
     $add= $row["address"];
     $city= $row["city"];
     $zip= $row["zip_code"];
     $email= $row["email"];
     echo" <tr>
    <td>$fname</td> 
    <td>$lname</td>
    <td>$phone</td>
    <td>$add</td> 
    <td>$city</td>
    <td>$zip</td>
     <td>$email</td>
  </tr>";
    }
}
    
    echo" </table> <br><a href='index.php'>Back</a></br>";

?>
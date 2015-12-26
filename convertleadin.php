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

$error = $_SESSION['exitcodev2'];
echo "<h1>$error</h1>";
$_SESSION['exitcodev2'] ='';

if ($result = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_users` is NULL")) {
      /* fetch associative array */
      echo'<h1>Select a A lead to Convert to An Install</h1>
      <form action="convertleadin2.php"method="post">
      <table border="1" style="width:100%">
      <tr>
    <td>Select</td>
    <td>Name</td> 
    <td>Phone</td>
    <td>Email</td>
    <td>Address</td>
    <td>City</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $id= $row["idcustomer_info"];
     $fname= $row["fname"];
     $lname= $row["lname"];
     $tel= $row["phone"];
     $email= $row["email"];
     $add= $row["address"];
     $city= $row["city"];
     echo" <tr>
    <td><input type='radio' name='id' value=$id unchecked></td>
    <td>$fname $lname</td> 
    <td>$tel</td>
    <td>$email</td>
    <td>$add</td> 
    <td>$city</td>
  </tr>";
    }
}
    
    echo' </table>
  	            <br><button type="submit">
  	                Submit Info
  	            </button></br>   
  	    </fieldset>
  	    </form>';
?>
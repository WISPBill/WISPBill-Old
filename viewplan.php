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

if ($result = $mysqli->query("SELECT * FROM `customer_plans`")) {
      /* fetch associative array */
      echo'
      <table border="1" style="width:100%">
      <tr>
    <td>Name</td> 
    <td>Price</td>
    <td>Kilo Up</td> 
    <td>Kilo Down</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $name= $row["name"];
     $price= $row["price"];
     $up= $row["max_bandwith_up_kilo"];
     $down= $row["max_bandwith_down_kilo"];
     echo" <tr>
    <td>$name</td> 
    <td>$$price</td>
    <td>$up</td> 
    <td>$down</td>
  </tr>";
    }
}
    
    echo" </table> <br><a href='index.php'>Back</a></br>";

?>
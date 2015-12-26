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

$mysqlir = new mysqli("$ipr", "$usernamer", "$passwordr", "$dbr");

if ($result = $mysqlir->query("SELECT * FROM `radreply` WHERE `attribute` = 'Framed-IP-Address'")) {
      /* fetch associative array */
      echo'
      <table border="1" style="width:100%">
      <tr>
    <td>Username</td> 
    <td>IP Address</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $name= $row["username"];
     $ipv= $row["value"];
     echo" <tr>
    <td>$name</td> 
    <td>$ipv</td>
  </tr>";
    }
}
    
    echo" </table> <br><a href='index.php'>Back</a></br>";

?>
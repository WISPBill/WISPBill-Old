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
      echo'<h1>Select the Static IP Assignments you Want to Delete<h1/>
      <form action="ipdelete2.php"method="post">
      <table border="1" style="width:100%">
      <tr>
    <td>Select</td>
     <td>Username</td> 
    <td>IP Address</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $id= $row["id"];
     $name= $row["username"];
     $value= $row["value"];
     echo" <tr>
    <td><input type='checkbox' name='id[]' value=$id unchecked></td>
    <td>$name</td> 
    <td>$value</td>
  </tr>";
    }
    echo'</table>
    <br><button type="submit">
  	                Delete Selected Static IP Assignments 
  	            </button></br> 
    </form>';
    /* free result set */
    $result->close();
}

?>
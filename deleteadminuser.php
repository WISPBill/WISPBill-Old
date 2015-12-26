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
$priv = $_SESSION['priv'];

if($priv == 0){
    // Do nothing 
} else{
    header('Location: index.php');
        exit;
}
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

if ($result = $mysqli->query("SELECT * FROM `admin_users`")) {
      /* fetch associative array */
      echo'<h1>Do not Delete all users you will not be able to log back in<h1/>
      <form action="deleteadminuser2.php"method="post">
      <table border="1" style="width:100%">
      <tr>
    <td>Select</td>
    <td>Username</td> 
    <td>Email</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $id= $row["idadmin"];
     $name= $row["username"];
     $email= $row["email"];
     echo" <tr>
    <td><input type='checkbox' name='id[]' value=$id unchecked></td>
    <td>$name</td> 
    <td>$email</td>
  </tr>";
    }
    echo'</table>
    <br><button type="submit">
  	                Delete Selected Users
  	            </button></br> 
    </form>';
    /* free result set */
    $result->close();
}

?>
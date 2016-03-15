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
$mysqlil = new mysqli("$ipl", "$usernamel", "$passwordl", "$dbl");
// start of post
$id= $_POST["id"];
$id2= $_POST["id2"];
$id3 = $_POST['id3'];

if (empty($id)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No Device was Selected';
    header('Location: configsite.php');
    exit;
} elseif (empty($id2)) {
     $_SESSION['exitcodev2'] = 'No Site was Selected';
    header('Location: configsite.php');
    exit;
}elseif (empty($id3)) {
     $_SESSION['exitcodev2'] = 'No  Access Point was Selected';
    header('Location: configsite.php');
    exit;
} else{
    // We are good
}

$id = inputcleaner($id,$mysqli);
$id2 = inputcleaner($id2,$mysqli);
$id3 = inputcleaner($id3,$mysqli);

if ($result = $mysqli->query("SELECT * FROM `devices` WHERE `iddevices` = $id")) {
      /* fetch associative array */
     
    while ($row = $result->fetch_assoc()) {
     $nms= $row["librenms_id"];
    }
}
if ($result = $mysqlil->query("SELECT * FROM `ports` WHERE `device_id` = $nms")) {
      /* fetch associative array */
      echo'
      <form action="configsite3.php"method="post">';
     
    while ($row = $result->fetch_assoc()) {
     $name= $row["ifName"];
      $port = $row["port_id"];
     echo "<h3>$name</h3><br></br>
     <select name ='$port' required>
     <option value='' selected disabled>Please Select an Option</option>
  <option value='no'>Port not in Use</option>
  <option value='backhaul'>Backhaul Port</option>
  <option value='ap'>Access Point Port</option>
  <option value='mgmt'>Management VLAN</option>
  <option value='other'>Loopback</option>
  <option value='loop'>Other Use</option>
</select><br></br>";
    }
}
$_SESSION['id'] = $id;
$_SESSION['id2'] = $id2;
$_SESSION['id3'] = $id3;
 echo'       
  	            <br><button type="submit">
  	                Next Step
  	            </button></br>   
  	    </form>';
?>
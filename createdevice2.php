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
// start of post
$name = $_POST["name"];
$serial = $_POST["serial"];
$modle = $_POST["modle"];
$mac = $_POST["add"];
$type = $_POST["type"];
$man = $_POST["manu"];

// end of post

// start of data sanitize and existence check
 if (empty($name)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: createdevice.php');
    exit;
} elseif(empty($serial)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'ser';
    header('Location: createdevice.php');
    exit;
}elseif(empty($modle)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'mod';
    header('Location: createdevice.php');
    exit;
}elseif(empty($mac)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'mac';
    header('Location: createdevice.php');
    exit;
}elseif(empty($type)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'type';
    header('Location: createdevice.php');
    exit;
}elseif(empty($man)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'devm';
    header('Location: createdevice.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}
if(!filter_var($mac, FILTER_VALIDATE_MAC)){
    $_SESSION['exitcodev2'] = 'mac';
    header('Location: createdevice.php');
    exit;
  }
else
  {
  //do nothing 
  }
$name = $mysqli->real_escape_string($name);
$serial = $mysqli->real_escape_string($serial);
$modle = $mysqli->real_escape_string($modle);
$mac = $mysqli->real_escape_string($mac);
$type = $mysqli->real_escape_string($type);
$man = $mysqli->real_escape_string($man);

// end of data sanitize and existence check
// start of data entry
if ($mysqli->query("INSERT INTO `$db`.`devices` (`iddevices`, `location_idlocation`, `name`, `serial_number`, `manufacturer`, `model`, `type`, `librenms_id`, `field_status`, `mac`)
                   VALUES (NULL, NULL, '$name', '$serial', '$man', '$modle', '$type', NULL, 'inventory', '$mac');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
header('Location: index.php');
?>
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
$lat = $_POST["lat"];
$lon = $_POST["lon"];
$type = $_POST["type"];
$con = $_POST["contact"];
// end of post

// start of data sanitize and existence check
 if (empty($name)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: createsite.php');
    exit;
} elseif(empty($lat)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'lat';
     header('Location: createsite.php');
    exit;
}elseif(empty($lon)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'lon';
     header('Location: createsite.php');
    exit;
}elseif(empty($type)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'type';
     header('Location: createsite.php');
    exit;
}elseif(empty($con)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'con';
     header('Location: createsite.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}


$name = $mysqli->real_escape_string($name);
$lat = $mysqli->real_escape_string($lat);
$lon = $mysqli->real_escape_string($lon);
$type = $mysqli->real_escape_string($type);
$con = $mysqli->real_escape_string($con);

// end of data sanitize and existence check
// start of data entry
if ($mysqli->query("INSERT INTO `$db`.`location` (`idlocation`, `name`,
                   `latitude`, `longitude`, `type`, `contacts_idcontacts`,
                   `ifconfig`, `coverage`) VALUES
                   (NULL, '$name', '$lat', '$lon', '$type', '$con', 'no', NULL);") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
    header('Location: index.php');
?>
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
$device = $_POST["id"];
$site = $_POST["site"];

// end of post

// start of data sanitize and existence check
 if (empty($device)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: linkrouter.php');
    exit;
}elseif(empty($site)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'site';
     header('Location: linkrouter.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}


$device = $mysqli->real_escape_string($device);
$site = $mysqli->real_escape_string($site);

// end of data sanitize and existence check
// start of data entry
					
if ($mysqli->query("UPDATE `$db`.`devices` SET `location_idlocation` = '$site', `field_status` = 'tower'
WHERE `devices`.`iddevices` = '$device';") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

if ($mysqli->query("UPDATE `$db`.`location` SET `ifconfig` = 'yes' WHERE
`location`.`idlocation` = '$site';") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

// end of data entry
header('Location: index.php');
?>
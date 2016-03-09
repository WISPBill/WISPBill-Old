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
$adminid = $_SESSION['adminid'];
// start of post
$skin = $_POST["skin"];

// end of post

// start of data sanitize and existence check
 if (empty($skin)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'skin';
    header('Location: setskin.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}


$skin = $mysqli->real_escape_string($skin);


// end of data sanitize and existence check
// start of data entry

if ($result = $mysqli->query("SELECT * FROM `admin_settings` WHERE `setting_name` = 'guiskin' and `admin_users_idadmin` = '$adminid'")) {
      /* fetch associative array */
      $skinset = $result->num_rows;
}

if($skinset == '0'){
    if ($mysqli->query("INSERT INTO `$db`.`admin_settings` (`idadmin_settings`, `setting_name`,
`value`, `admin_users_idadmin`) VALUES (NULL, 'guiskin', '$skin', '$adminid');") === TRUE) {
    //nothing 
    } else{
        echo'Something went wrong with the database please contact your webmaster';
            exit;
    }
}elseif($skinset == '1'){
    if ($mysqli->query("UPDATE  `$db`.`admin_settings` SET  `value` =  '$skin' WHERE
`setting_name` = 'guiskin' and `admin_users_idadmin` = '$adminid'") === TRUE) {
    //nothing 
    } else{
        echo'Something went wrong with the database please contact your webmaster';
            exit;
    }
}else{
    echo "unexpected database entry";
    exit;
}
// end of data entry
header('Location: index.php');
?>
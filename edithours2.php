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

$adminid = $_SESSION['adminid'];
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

$intday = $_POST["day"];
$daydata = $_POST["work"];

$intday = $intday + 1; // for empty

 if (empty($intday)) {
    $_SESSION['exitcodev2'] = 'change';
    header('Location: edithours.php');
    exit;
 }elseif(empty($daydata)){
    $_SESSION['exitcodev2'] = 'work';
    header('Location: edithours.php');
    exit;
 }else {
    // Nothing 
 }
 
 $intday = $intday - 1; // for empty
  
  $intday = inputcleaner($intday,$mysqli);
  $daydata = inputcleaner($daydata,$mysqli);
  
     if($daydata =='false'){
    
     if ($result = $mysqli->query("DELETE FROM `admin_hours`
WHERE `day_of_week` = '$intday' and `admin_users_idadmin` = '$adminid'") === TRUE){

    } else {
       echo 'DB ERROR';
     exit;
    }// end if
    
    if ($result = $mysqli->query("INSERT INTO `$db`.`admin_hours`
    (`idadmin_hours`, `day_of_week`, `start`, `end`, `admin_users_idadmin`) VALUES
    (NULL, '$intday', '0', '0', '$adminid');") === TRUE){

    } else {
       echo 'DB ERROR';
     exit;
    }// end if
    
 }elseif($daydata == 'true'){
    
    
    $start = $_POST["newstart"];
    $end = $_POST["newend"];
    
	$start = inputcleaner($start,$mysqli);
	$end = inputcleaner($end,$mysqli);
    if ($result = $mysqli->query("DELETE FROM `admin_hours`
WHERE `day_of_week` = '$intday' and `admin_users_idadmin` = '$adminid'") === TRUE){

    } else {
       echo 'DB ERROR';
     exit;
    }// end if
    
    if ($result = $mysqli->query("INSERT INTO `$db`.`admin_hours`
    (`idadmin_hours`, `day_of_week`, `start`, `end`, `admin_users_idadmin`) VALUES
    (NULL, '$intday', '$start', '$end', '$adminid');") === TRUE){

    } else {
       echo 'DB ERROR';
     exit;
    }// end if
    
 }else{
    echo "Input Error";
    exit;
 }
    
header('Location: index.php');
?>
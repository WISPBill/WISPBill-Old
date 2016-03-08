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

$sun = $_POST["sunday"];
$mon = $_POST["monday"];
$tue = $_POST["tuesday"];
$wed = $_POST["wednesday"];
$thr = $_POST["thursday"];
$fri = $_POST["friday"];
$sat = $_POST["saturday"];


 if (empty($sun)) {
    $_SESSION['exitcodev2'] = 'sunday';
    header('Location: sethours.php');
    exit;
 }elseif(empty($mon)){
    $_SESSION['exitcodev2'] = 'monday';
    header('Location: sethours.php');
    exit;
 }elseif(empty($tue)){
    $_SESSION['exitcodev2'] = 'tuesday';
    header('Location: sethours.php');
    exit;
 }elseif(empty($wed)){
    $_SESSION['exitcodev2'] = 'wednesday';
    header('Location: sethours.php');
    exit;
 }elseif(empty($thr)){
    $_SESSION['exitcodev2'] = 'thursday';
    header('Location: sethours.php');
    exit;
 }elseif(empty($fri)){
    $_SESSION['exitcodev2'] = 'friday';
    header('Location: sethours.php');
    exit;
 }elseif(empty($sat)){
    $_SESSION['exitcodev2'] = 'saturday';
    header('Location: sethours.php');
    exit;
 }else {
    // Nothing 
 }

 $sun = $mysqli->real_escape_string($sun);
 $mon = $mysqli->real_escape_string($mon);
 $tue = $mysqli->real_escape_string($tue);
 $wed = $mysqli->real_escape_string($wed);
 $thr = $mysqli->real_escape_string($thr);
 $fri = $mysqli->real_escape_string($fri);
 $sat = $mysqli->real_escape_string($sat);
 
 $weekdata = array(
    "sunday" => "$sun",
    "monday" => "$mon",
    "tuesday" => "$tue",
    "wednesday" => "$wed",
    "thursday" => "$thr",
    "friday" => "$fri",
    "saturday" => "$sat",
    );
 
 $daysoftheweek = array("sunday", "monday", "tuesday", "wednesday","thursday", "friday", "saturday");
 
 foreach($daysoftheweek as $day){
    
    $daydata = $weekdata["$day"];
    
    // Gets the int of day sunday = 0
    
    $intday = date("w",strtotime("$day"));
    
     if($daydata =='false'){
    
    if ($result = $mysqli->query("INSERT INTO `$db`.`admin_hours`
    (`idadmin_hours`, `day_of_week`, `start`, `end`, `admin_users_idadmin`) VALUES
    (NULL, '$intday', '0', '0', '$adminid');") === TRUE){

    } else {
       echo 'DB ERROR';
     exit;
    }// end if
    
 }elseif($daydata == 'true'){
    
    $poststart= "$day"."start";
    $postend= "$day"."end";
    
    $start = $_POST["$poststart"];
    $end = $_POST["$postend"];
    
    $start = $mysqli->real_escape_string($start);
    $end = $mysqli->real_escape_string($end);
    
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
    
 }// End of loop
 
header('Location: index.php');
?>
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
$name = $_POST["name"];
$pass = $_POST["pass"];
$ip = $_POST["ip"];
$router = $_POST["router"];
// end of post

// start of data sanitize and existence check
 if (empty($name)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'name';
    header('Location: configrouter.php');
    exit;
} elseif(empty($pass)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'pass';
     header('Location: configrouter.php');
    exit;
}elseif(empty($ip)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'ip';
     header('Location: configrouter.php');
    exit;
}elseif(empty($router)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'router';
     header('Location: configrouter.php');
    exit;
}else{
    //resets the code
     $_SESSION['exitcodev2'] = '';
}


$name = inputcleaner($name,$mysqli);
$pass = inputcleaner($pass,$mysqli);
$ip = inputcleaner($ip,$mysqli);
$router = inputcleaner($router,$mysqli);

// end of data sanitize and existence check
$routerip = $ip;
$username = $name;
$password = $pass;

$ssh = new Net_SSH2("$routerip");
if (!$ssh->login("$username", "$password")) {
    $_SESSION['exitcodev2'] = 'all';
     header('Location: configrouter.php');
}

$size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, 'ofb');
$iv = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
$epassword = mcrypt_encrypt (MCRYPT_BLOWFISH,"$masterkey", "$pass","ofb","$iv");
$ename = mcrypt_encrypt (MCRYPT_BLOWFISH,"$masterkey", "$username","ofb","$iv");
	          
if ($mysqli->query("INSERT INTO `$db`.`device_credentials` (`devices_iddevices`,
				   `username`, `password`, `IV`) VALUES ('$router', '$ename', '$epassword', '$iv');") === TRUE) {
//nothing 
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}

$data = $ssh->exec("/opt/vyatta/bin/vyatta-op-cmd-wrapper show interfaces");
	
  $string = 'eth';  
    
  $datastart = strpos($data,$string);
  $datastart = $datastart -1; 
  
  $data = substr($data,$datastart);
 
 $string2 = 'lo           127.0.0.1/8                       u/u';
 
 $data = str_replace($string2, "", $data);
 
 $string2 = '             ::1/128                          ';
 
 $data = str_replace($string2, "", $data);
 
 $data = preg_replace('/(\s)+/', ' ', $data);

$ports = array();
$cleandata = array();
while(true){
  $string = 'u/';  
    
  $dataend = strpos($data,$string);
  
  if ($dataend == false){
    break; // leave loop
  }else{
   
   $portdata = substr($data,0,$dataend);
   array_push($ports, $portdata);
   // Removes collected string
   $nextdatastart = $dataend + 2;
   $data = substr($data,$nextdatastart);  
  }
  
} // end of loop
echo' <h1>Don\'t it Back or Change Page fill out feilds and hit submit</h1>
      <form action="configrouter3.php"method="post">';
foreach($ports as $key => $row){

preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/\d{1,2}/", $row, $matches);
if(isset($matches[0])){
$ipwithmask = $matches[0];
}

preg_match("/[a-z]{3}\d{1,2}\.\d{1,4}|[a-z]{3}\d{1,2}/", $row, $matches);
if(isset($matches[0])){
$interface = $matches[0];
$ipandname = [
    "ip" => "$ipwithmask",
    "name" => "$interface",
	"post" => "$key",
];
array_push($cleandata, $ipandname);
 echo "<h3>$ipwithmask on $interface </h3><br></br>
     <select name ='$key' required>
     <option value='' selected disabled>Please Select an Option</option>
  <option value='no'>Port not in Use</option>
  <option value='backhaul'>Backhaul Port</option>
  <option value='ap'>Access Point Port</option>
  <option value='mgmt'>Management Port</option>
  <option value='other'>Loopback</option>
  <option value='loop'>Other Use</option>
</select><br></br>";
}
$ipwithmask = '';
} //end of loop for interfaces
  $_SESSION['cleandata'] = $cleandata;
  $_SESSION['ip'] = $routerip;
 echo"       <input type='hidden' name='router' value='$router'>
  	            <br><input type='submit' value='Submit'></br>   
  	    </form>";

?>
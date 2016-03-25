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

	
    $id = $_GET['choice'];
	
	$id = inputcleaner($id,$mysqli);

     if ($result = $mysqli->query("SELECT * FROM `ip_address` WHERE `idip_address` = '$id'")) {
      /* fetch associative array */
         
    while ($row = $result->fetch_assoc()) {
     $address= $row["address"];
     $mac= $row["dhcp_mac"];
     $status= $row["status"];
    
     echo '
     <div class="form-group">';
				$error = $_SESSION['exitcodev2'];
$_SESSION['exitcodev2'] ='';
$errorlabel ='<label class="control-label" for="inputError" style="color: red;"><i class="fa fa-times-circle-o"></i> Input with
    error</label>';
					if($error == 'name'){
						echo "$errorlabel";
					}else{
						echo '<label>Device Name</label>';
					}
					
                    echo '
                  <input type="text" class="form-control" name="name" placeholder="Enter Device Name" required>
                </div>
               
			   <div class="form-group">
                  ';
               
					if($error == 'ser'){
						echo "$errorlabel";
					}else{
						echo '<label>Serial Number</label>';
					}
					echo '
                  <input type="text" class="form-control" name="serial" placeholder="Enter Serial Number" required>
                </div>
			   
			   <div class="form-group">
               ';
              
					if($error == 'mod'){
						echo "$errorlabel";
					}else{
						echo '<label>Model</label>';
					}
					echo'
                  
                  <input type="text" class="form-control" name="modle" placeholder="Enter Device Model" required>
                </div>
			   
			   <div class="form-group">
            ';
					if($error == 'mac'){
						echo "$errorlabel";
					}else{
						echo '<label>Mac Address</label>';
					}
					
                  echo '
                  <input type="text" class="form-control" name="add" '; echo" value='$mac' required>
                </div>";
                
                echo '
			                 
                <!-- select -->
                <div class="form-group">
                ';
             
					if($error == 'type'){
						echo "$errorlabel";
					}else{
						echo '<label>Device Type</label>';
					}
					echo '
                  <select class="form-control" name="type" required>
					  <option value="" selected disabled>Please Select Device Type</option>
					 <option value="ap">Access Point</option>
  <option value="cpe">Customer Radio</option>
  <option value="router">Router</option>
  <option value="switch">Switch</option>
  <option value="other">Other</option>
                  </select>
                </div>
				
				 <div class="form-group">
                  
				';
				 
                if($error == 'devm'){
						echo "$errorlabel";
					}else{
						echo '<label>Device Manufacturer</label>';
					}
					       
  $macurl = "http://api.macvendors.com/";
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $macurl);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "mac=$mac");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
     if($response) {
       array_push($manufacturer,$response);
     } else {
      
    }
                echo '<select class="form-control" name="manu" required>
				  <option value="" selected disabled>Please Select Device Manufacturer</option>;';
				  foreach ($manufacturer as $man){
   echo "<option value='$man'>$man</option>";
}
echo '
    </select>
                </div>
				<div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>';

				
     
    }}

?>
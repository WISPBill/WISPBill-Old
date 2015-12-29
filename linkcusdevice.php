<?php
require_once('./session.php');
require_once('./fileloader.php');
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

$error = $_SESSION['exitcodev2'];
echo "<h1>$error</h1>";
$_SESSION['exitcodev2'] ='';

if ($result = $mysqli->query("SELECT * FROM `devices`
                             WHERE `location_idlocation` is NULL and
                             `type` = 'cpe' and `field_status` ='inventory'")) {
      /* fetch associative array */
      echo'<h1>Select a Device to Link it to an Account</h1>
      <form action="linkcusdevice2.php"method="post">
      <table border="1" style="width:100%">
      <tr>
    <td>Select</td>
    <td>Name</td> 
    <td>Type</td>
    <td>Mac Address</td>
    <td>Serial Number</td> 
    <td>Model</td>
  </tr>';
    while ($row = $result->fetch_assoc()) {
     $id= $row["iddevices"];
     $name= $row["name"];
     $type= $row["type"];
     $mac= $row["mac"];
     $sn= $row["serial_number"];
     $model= $row["model"];
     echo" <tr>
    <td><input type='radio' name='id' value=$id unchecked></td>
    <td>$name</td> 
    <td>$type</td>
    <td>$mac</td>
    <td>$sn</td> 
    <td>$model</td>
  </tr>";
    }
}
    
    echo' </table>
            <br></br>';
			 echo" <h1>Choose a site</h1><br>
			  
                  <select name='site'  required>
                    <option value='' selected disabled>Choose a A Site</option>";
                    // gets plan name and id
                    if ($result = $mysqli->query("SELECT * FROM `location` WHERE `ifconfig` = 'yes'")) {
    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $id = $row["idlocation"];
        $name = $row["name"];           
        echo"<option value=$id>$name</option>";
        }
					}
    echo'</br>
    <br></br>
    
    </select> 
              <br></br>
              <h1> Enter the Account Info</h1>
                   <br><label>Email</label>
  	            <input type="text" 
  	             name="email"/></br>

                 <br><label>Telephone Number</label>
  	            <input type="tel" 
  	             name="tel"/></br>
                 
                 <br><label>Last 4 Digits of Credit Card</label>
  	            <input type="number" 
  	             name="l4"/></br>
  	            <br><button type="submit">
  	                Submit Info
  	            </button></br>   
  	    </fieldset>
  	    </form>';
?>
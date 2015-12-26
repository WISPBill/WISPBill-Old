<?php
require_once('./session.php');
require_once('./fileloader.php');


$error = $_SESSION['exitcodev2'];
echo "<h1>$error</h1>";
$_SESSION['exitcodev2'] ='';

echo'<form action="createdevice2.php"method="post">
  	    <fieldset>
  	            <br><label>Name</label>
  	            <input type="text" 
  	             name="name"/></br>
                     
                     <br><label>Serial Number</label>
  	            <input type="text" 
  	             name="serial"/></br>
                 
                 <br><label>Model</label>
  	            <input type="text" 
  	             name="modle"/></br>
                     
                     <br><label>Mac Address</label>
  	            <input type="text" 
  	             name="add"/></br>
                 
             <br><label>Device Use  </label><select name="type">
  <option value="ap">Access Point</option>
  <option value="cpe">Customer Radio</option>
  <option value="router">Router</option>
  <option value="switch">Switch</option>
  <option value="other">Other</option>
</select></br>

<br><label>Device Manufacturer  </label><select name="manu">';
foreach ($manufacturer as $man){
   echo "<option value='$man'>$man</option>";
}

echo'</select></br>
  	            <br><button type="submit">
  	                Submit Info
  	            </button></br>   
  	    </fieldset>
  	    </form>';
// end of user input feilds 
?>
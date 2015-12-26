<?php
require_once('./session.php');
require_once('./fileloader.php');
$mysqli = new mysqli("$ip", "$username", "$password", "$db");
$errorcode = $_SESSION['errorcode'];

echo "<h1>$errorcode</h1>";
// start of user input feilds
echo'<form action="createcontactnote2.php"method="post">
  	    <fieldset>
  	           <h2>Fill out the needed info</h2>';
               
               echo" <br><label>Contact</label>   
                  <select name='contact'  required>
                    <option value='' selected disabled>Please Select an Option</option>";
                    // gets plan name and id
                    if ($result = $mysqli->query("SELECT * FROM `contacts`")) {
    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $id = $row["idcontacts"];
        $fname = $row["fname"];
        $lname = $row["lname"];
        $org = $row["org"];               
        echo"<option value=$id>$fname $lname with $org</option>";
        }
    echo'</br>
    <br></br>
    
    </select> 
               
  	   <br></br>
  	   <textarea rows="8" name="text1" cols="68"></textarea>

        <br>  </br><br><button type="submit">
  	                Submit Info 
  	            </button></br> </fieldset> </form>';
    
    /* free result set */
    $result->close();
}else{
       echo'Something went wrong with the database please contact your webmaster';
       exit;
    }
                
      
// end of user input feilds 
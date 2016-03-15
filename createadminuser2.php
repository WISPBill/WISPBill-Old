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
$user = $_POST["username"];
$pass1 = $_POST["password"];
$pass2 = $_POST["password2"];
$email1 = $_POST["email"];
$email2 = $_POST["email2"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$tel = $_POST["tel"];
$ctel = $_POST["ctel"];
// end of post

// start of data sanitize and existence check
 if (empty($fname)) {
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No First Name was Submitted';
    header('Location: createadminuser.php');
    exit;
} elseif(empty($lname)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No last Name was Submitted';
    header('Location: createadminuser.php');
    exit;
}elseif(empty($tel)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No telphone number was Submitted';
    header('Location: createadminuser.php');
    exit;
}elseif(empty($ctel)){
    // If input feild is empty it goes back to the fourm and informs the user
    $_SESSION['exitcodev2'] = 'No cell number was Submitted';
    header('Location: createadminuser.php');
    exit;
}elseif (empty($user)) {
    // If username is empty it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'user empty';
    header('Location: createadminuser.php');
    exit;
} elseif(empty($pass1)){
    // If password is empty it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'password empty';
    header('Location: createadminuser.php');
    exit;
}
elseif(empty($pass2)){
    // If password is empty it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'password empty';
    header('Location: createadminuser.php');
    exit;
}
elseif(empty($email1)){
    // If email is empty it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'email empty';
    header('Location: createadminuser.php');
    exit;
}
elseif(empty($email2)){
    // If email is empty it goes back to the fourm and informs the user 
    $_SESSION['exitcode'] = 'email empty';
    header('Location: createadminuser.php');
    exit;
} else{
    // do nothing 
} // end if

$fname = inputcleaner($fname,$mysqli);
$lname = inputcleaner($lname,$mysqli);
$tel = inputcleaner($tel,$mysqli);
$ctel = inputcleaner($ctel,$mysqli);
$user = inputcleaner($user,$mysqli);
$pass1 = inputcleaner($pass1,$mysqli);
$pass2 = inputcleaner($pass2,$mysqli);
$email1 = inputcleaner($email1,$mysqli);
$email2 = inputcleaner($email2,$mysqli);

if(!filter_var($email1, FILTER_VALIDATE_EMAIL)){
    $_SESSION['exitcode'] = 'email not valid';
    header('Location: createadminuser.php');
    exit;
  }
else
  {
  //do nothing 
  }
  
// end of data sanitize and existence check

//start of password match
if($pass1 == $pass2){
    // do nothing 
} else {
    // If password match fails it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'pass match fail';
    header('Location: createadminuser.php');
    exit;
}
// end if and password match

//start of email match
if($email1 == $email2){
    // do nothing 
} else {
    // If email match fails it goes back to the fourm and informs the user
    $_SESSION['exitcode'] = 'email match fail';
    header('Location: createadminuser.php');
    exit;
}
// end if and email match

// start of cheack for exsting username and or email 

if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `username` = '$user'")) {
    if ($result->num_rows == 1){
    $_SESSION['exitcode'] = 'username already';
    header('Location: createadminuser.php');
    exit;
    } elseif ($result->num_rows == 0){
        // do nothing 
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    
    /* free result set */
    $result->close();
}
if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `email` = '$email1'")) {
    if ($result->num_rows == 1){
    $_SESSION['exitcode'] = 'email already';
    header('Location: createadminuser.php');
    exit;
    } elseif ($result->num_rows == 0){
        // do nothing 
    } else{
        echo'Something went wrong with the database please contact your webmaster';
        exit;
    }
    
    /* free result set */
    $result->close();
}
// end of cheack for exsting username and or email

//start of hashing
$hash= password_hash("$pass1", PASSWORD_DEFAULT);
// end of hashing
$target_dir = "$uploadimgdir";
$target_file = $target_dir .$user. basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
         $_SESSION['exitcodev2'] = 'File is not an image';
    header('Location: createadminuser.php');
    }
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $_SESSION['exitcodev2'] = 'File is not a JPG,PNG,JPEG or GIF';
    header('Location: createadminuser.php');
}

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      
    } else {
        $_SESSION['exitcodev2'] = 'Sorry, there was an error uploading your file Try again';
    header('Location: createadminuser.php');
    }

$file_path=str_replace('\\','/',$target_file);
$file_path=str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_path);
$file_path='https://'.$_SERVER['HTTP_HOST'].$file_path;

//start of data entry
if ($mysqli->query("INSERT INTO `$db`.`admin_users`
                   (`idadmin`, `username`, `password`, `email`,
                   `fname`, `lname`, `hometel`, `celltel`, `img`,
                   `privilege`) VALUES
                   (NULL, '$user', '$hash', '$email1', '$fname',
                   '$lname', '$tel', '$ctel', '$file_path', '0');") === TRUE) {
          header('Location: index.php');
} else{
    echo'Something went wrong with the database please contact your webmaster';
        exit;
}
 //end of data entry and file
?>
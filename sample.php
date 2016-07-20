<?php
//Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>



<form method="post" action="createnew.php">
            
            <label>Privet e-mail</label>
            <input type="text" name="emaiSub"></input>
           
            <input type="submit" name="submit"></input>
        </form>



<?php

if (isset($_POST['submit'])) {

    print_r($_POST);


$con = mysqli_connect ('localhost', 'root', 'root', 'AtYourService')
        or die('Error connecting to MySQL server.');
        

//$sql = "INSERT INTO Emails (emailSub) VALUES ('$_post[emailSub]')";
$sql = "INSERT INTO Emails (emailSub) VALUES ('".$_POST['emailSub']."')";

$result = mysqli_query($con, $sql);    

}
?>


</body>
</html>
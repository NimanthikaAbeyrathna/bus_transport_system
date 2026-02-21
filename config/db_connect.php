<?php

//connecting to the database
$conn = mysqli_connect("localhost","root","","buslynk",3306);

//checking the connection status
if(!$conn){
    echo"mysql error - ". mysqli_connect_error();
}
?>
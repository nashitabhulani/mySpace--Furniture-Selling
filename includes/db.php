<?php
// Database configuration
$host = 'localhost'; // or your host
$username = 'root';
$password = '';
$dbname = 'myspacedb';

// Create connection
$con=mysqli_connect($host,$username,$password,$dbname);

if(!$con){
    die("Connection failed: ". mysqli_connect_error());

}
else{
    
}
?>

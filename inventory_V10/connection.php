<?php

$servername="localhost";
$database="gestion-stock";
$user="root";
$pass="";

try{
  $conn=new PDO("mysql:host=$servername; dbname=$database", $user, $pass);
}
catch(PDOException $e){
  echo $e->getMessage();
  exit();
}
?>
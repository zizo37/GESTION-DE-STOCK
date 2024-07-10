<?php
include "connection.php"; 
session_start();

if (!isset($_SESSION["loggedIn_admin"]) || $_SESSION["loggedIn_admin"] !== true) {
  header("Location: login.php?erreur=1");
  exit();
}

if(isset($_GET['id'])){
    $id=$_GET['id'];

    $sql = 'delete from articles where id= :id limit 1';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        echo 'supprimer avec succes';
        header( "location: article.php" );
        exit();
    } else{
        die('Error!');
    }
}


?>
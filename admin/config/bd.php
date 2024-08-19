<?php

$dbhost = 'localhost';
$dbuser = '';
$dbpass = '';
$dbname = '';


try{
     $conexion= new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser, $dbpass);
} catch( Exception $ex){
    echo $ex->getMessage();
}

?>
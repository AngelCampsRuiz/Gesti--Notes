<?php
    $dbservidor = '127.0.0.1';
    $dbusername = 'root';
    $dbpsw = '';
    $bdDatabase = 'bd_escuela';
    try{
        $conn = @mysqli_connect($dbservidor, $dbusername, $dbpsw, $bdDatabase);
    } catch(Exception $e){
        echo "Error de conexion: ". $e->getMessage() . "<br>";
        die();
    }
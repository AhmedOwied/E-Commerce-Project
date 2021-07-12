<?php

$dsn='mysql:host=localhost;dbname=shop'; //data source name
$user='root';
$pass='';
$option=array(

    PDO::MYSQL_ATTR_INIT_COMMAND => 'setnames utf-8',
);

    try{
        $connect = new PDO($dsn, $user, $pass); //sart a new connection with PDO class  
        $connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo 'You Are Connect Welcome to Database';
    }  

    catch(PDOException $e){ // ($e) variable 
            echo 'failed To connect' . $e->getmessage();
    }

?>
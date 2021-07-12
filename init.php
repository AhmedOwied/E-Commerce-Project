<?php
        include 'connect.php';

        $tmp = 'includes/template/';  // template directed
        $css = 'layout/css/'; //css directed
        $lang ='includes/languages/';//language directed
        $func ='includes/functions/'; //function direct

        /*Include important file*/
        include $func .'functions.php';
        include $lang .'english.php';
        include $tmp .'Header.php'; 
         
        //includes navbar on All Pages except variable $noNavbar 
        if(!isset($noNavbar)){  include $tmp .'navbar.php'; }
   
    
?>
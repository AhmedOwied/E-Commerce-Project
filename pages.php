<?php

   /* $do='';
    if(isset($_GET['do'])){
        $do = $_GET['do']; 
    }else{
        $do = 'manage';
    } */
    
    $do = isset($_GET['do'] )? $_GET['do'] : 'manage';

    if($do == 'manage'){
        echo 'Welcome You Are in Manage Category page ';
        echo '<a href="pages.php?do=Add">Add Item+</a>';

    }elseif($do == 'Add'){
        echo 'You Are in Add Category page'; 

    }elseif($do == 'Delete'){
        echo 'You Are in Delete Category page';

    }else{
        echo'there is no page with this name';
    }





?>
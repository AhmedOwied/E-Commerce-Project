<?php

/* Template page */

ob_start(); //output buffering start
session_start();
$pageTitle='';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do'] )? $_GET['do'] : 'Manage';

    if($do == 'Manage'){

    }elseif($do =='Add'){

    }elseif($do =='Insert'){

    }elseif($do =='Edit'){

    }elseif($do =='Update'){

    }elseif($do =='Delete'){

    }elseif($do =='Activate'){

    }

    include   $tmp.'Footer.php';

}else{
    header(location:'index.php');
    exit();
}


ob_end_flush(); //Release th output 



?>
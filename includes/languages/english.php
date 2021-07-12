<?php

function lang($phrase){

    static $langs=array(
           /*Navbar links*/

        'HOME_Admin'=>'Home',
        'CATEGORIES'=>'categories',
        'ITEMS'     =>'items',
        'MEMBERS'   =>'members',
        'STATISTICS'=>'statistics',
        'LOGS'      =>'logs'

    );
    return $langs[$phrase];
  
}

?>
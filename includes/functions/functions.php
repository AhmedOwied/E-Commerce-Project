<?php


  function getTitle(){
     
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    }else
        echo 'Default';
   
  }


/**************************************************  (V.1 & V.2 ) **************************************** */ 

  /* (V.1)
  ** Home Redirect function [this function Accept parameter]
  ** $ErrorMsg = Echo the Error message
  ** $Seconds  = Seconds Before Redirected
  */

  /*function RedirectHome($ErrorMsg ,$Seconds=3){
    Echo "<div class='alert alert-danger'>$ErrorMsg</div>";
    Echo "<div class='alert alert-info'>You Will Be Redirected To HomePage After $Seconds seconds.</div>";
    header("refresh:$Seconds;url=index.php");
    exit();
  }
  */
  /* (V.2)
  ** Home Redirect function [this function Accept parameter]
  ** $theMsg = Echo the message [Error or success or warning]
     $url = the link you want to Redirect to it 
  ** $Seconds  = Seconds Before Redirected
  */
 

  function RedirectHome($theMsg ,$url=null,$Seconds=4){

    if($url == null){
       $url='index.php';
       $link='HomePage';

    }else{
      if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !==''){
        $url= $_SERVER['HTTP_REFERER'];
        $link='previous Page';
      }else{
        $url='Dashboard.php';  // Check Out
        $link='HomePage';
      }

    }
    Echo $theMsg;
    Echo "<div class='alert alert-info'>You Will Be Redirected To $link After $Seconds seconds </div>";
    header("refresh:$Seconds;url=$url");
    exit();
  }

 /***********************************************************************************************************/

  /*
   ** function check item in Database [Function Accept parameter]
   ** $select = item Example: 'username'  or  'category'
   ** $from   = table Example: users   <-  table_name
   ** $value  = value of select         where (...)=...
  */

  function CheckItem($select, $from ,$value){
    global $connect;

    $statement=$connect->prepare("SELECT $select FROM $from WHERE $select=? ");
    $statement->execute(array($value));
    $count=$statement->rowcount();
    return $count;    /* ...return->> meaning not echo in page all time Just a Query only */
  }

/*
** Function to count number of item rows 
** $item = the item to count
** $table= the table name to choose from
*/

function CountItem($item,$table){
  global $connect;
  
  $Totalitem =$connect->prepare("SELECT COUNT($item) FROM $table");
  $Totalitem->execute();

  return $Totalitem->fetchColumn();

}
/*
** Get latest Recored Function
** $select = field to select
** $table = table name
** $order = table arrangement(order)
** $limit = Number of Recorder to GET 
*/ 
function Getlatest($select,$table,$order,$limit=3){
  global $connect;

  $getstmt=$connect->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit ");
  $getstmt->execute();
  $rows=$getstmt->fetchALL();
  return  $rows;

}


?>
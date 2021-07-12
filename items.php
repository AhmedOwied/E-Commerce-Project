<?php
/*
===========================
==Items Page
===========================
*/


ob_start(); //output buffering start
session_start();
$pageTitle='Items';

if(isset($_SESSION['Username'])){

    include 'init.php';

    $do = isset($_GET['do'] )? $_GET['do'] : 'Manage';

    if($do == 'Manage'){
        Echo "Welcome to item Page";

    }elseif($do =='Add'){?>
        <h2 class="text-center">Add New Item</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                    
              <div class="form-group form-group-lg">
                <label class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" placeholder="Name of Item" required="required">
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Description</label>
                <div class="col-sm-10">
                    <input type="text" name="description" class="form-control" placeholder="Description of Item" required="required">
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Price</label>
                <div class="col-sm-10">
                    <input type="text" name="price" class="form-control" placeholder="Price of Item" required="required">
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Country</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" placeholder="Country of Made" required="required">
                </div>
              </div>
              <div class="form-group form-group-lg">
                <label class="col-sm-2">Status</label>
                <div class="col-sm-10"> 
                    <select class="form-control">
                       <option value ="0">...</option>
                       <option value ="1">New</option>
                       <option value ="2">Like New</option>
                       <option value ="3">Used</option>
                       <option value ="4">Old</option>

                    </select>
                </div>
              </div>

              <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Add Item" class="btn btn-primary btn-lg" >
                </div>
              </div>


              </form>
        </div>


     <?php
    

    }elseif($do =='Insert'){

    }elseif($do =='Edit'){

    }elseif($do =='Update'){

    }elseif($do =='Delete'){

    }elseif($do =='Approve'){

    }

    include   $tmp.'Footer.php';

}else{
    header('location:index.php');
    exit();
}
ob_end_flush(); //Release th output 



?>
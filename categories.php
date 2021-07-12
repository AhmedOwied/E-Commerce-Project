<?php

ob_start(); //output Buffering Start

session_start();
$pageTitle='Categories';

if(isset($_SESSION['Username'])){
 
    include 'init.php';

    $do =isset($_GET['do']) ? $_GET['do'] : 'Manage';  // Condition (if) 
    if($do =='Manage'){
       $sort='ASC';
       $sort_array=array('ASC','DESC');
       if(isset($_GET['sort']) && in_array($_GET['sort'] ,$sort_array)){
          $sort=$_GET['sort'];
        }

        $stmt2=$connect->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
        $stmt2->execute();
        $categs=$stmt2->fetchALL(); 
?>
        <h2 class="text-center">Manage Categories</h2>
        <div class="container categories">
           <div class="panel panel-default">
              <div class="panel-heading">Manage Categories
                <div class="Ordering pull-right">
                    Ordering:
                    <a class="<?php if($sort =='ASC') {echo 'active';} ?>" href="?sort=ASC">Asc</a> |
                   <a class="<?php if($sort =='DESC') {echo 'active';} ?>" href="?sort=DESC">Desc</a>
                </div>    
              </div>
              <div class="panel-body">
                  <?php
                       foreach($categs as $cat){
                         Echo "<div class='cate'>";
                         
                            echo "<div class='hidden-button'>";
                               echo "<a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-primary btn-xs'> <i class='fa fa-edit'></i> Edit</a>";
                               echo "<a href='#' class='btn btn-danger btn-xs'> <i class='fa fa-close'></i> Delete</a>";
                            echo "</div>";

                            echo '<h3>'.$cat['Name'] .'</h3>';
                            echo "<p>"; if(empty($cat['Description'])){echo 'this is category has no description';}else{echo $cat['Description'];} echo"</p>";
                            if($cat['Visibility'] == 1){ Echo '<span class="visibility">Hidden</span>';} 
                            if($cat['Allow_Comment'] == 1){ Echo '<span class="commenting">Comment Disabled</span>';} 
                            if($cat['Allow_Ads'] == 1){ Echo '<span class="advertises">Ads Disabled</span>';} 
                            echo '<hr>';

                         Echo "</div>";
                       }    
                ?>
              </div>
           </div>
        
        </div>
<?php
    }elseif($do =='Add'){ ?>
        <h2 class="text-center">Add New Category</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                    
              <div class="form-group form-group-lg">
                <label class="col-sm-2">Name</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control"  autocomplete="off" placeholder="Name of Category" required="required">
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Description</label>
                <div class="col-sm-10">
                    
                    <input type="text" name="description" class="form-control" placeholder="Description The Category" >
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Ordering</label>
                <div class="col-sm-10">
                    <input type="text" name="order" class="form-control" placeholder="Number to Arrange the Categories"  >
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">visible</label>
                <div class="col-sm-10">
                    <div>
                        <input id="vis-yes" type='radio' name="visibility" value="0" checked >
                        <label for="vis-yes">yes</label>
                    </div>
                    <div>
                        <input id="vis-no" type='radio' name="visibility" value="1">
                        <label for="vis-no">No</label>
                    </div>
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Allow Commenting</label>
                <div class="col-sm-10">
                    <div>
                        <input id="com-yes" type='radio' name="commting" value="0" checked >
                        <label for="com-yes">yes</label>
                    </div>
                    <div>
                        <input id="com-no" type='radio' name="commting" value="1">
                        <label for="com-no">No</label>
                    </div>
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Allow Ads</label>
                <div class="col-sm-10">
                    <div>
                        <input id="ads-yes" type='radio' name="Ads" value="0" checked >
                        <label for="ads-yes">yes</label>
                    </div>
                    <div>
                        <input id="vis-no" type='radio' name="Ads" value="1">
                        <label for="vis-no">No</label>
                    </div>
                </div>
              </div>

              <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Add Category" class="btn btn-primary btn-lg" >
                </div>
              </div>


              </form>
        </div>


     <?php
    }
    elseif($do =='Insert'){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            Echo "<h2 class='text-center'>Insert Category</h2>";
            Echo " <div class='container'>";
               
            //Get variable from the Form
            $name    =$_POST['name'];
            $visible =$_POST['visibility'];
            $desc    =$_POST['description'];
            $order   =$_POST['order'];
            $comment =$_POST['commting'];
            $ads     =$_POST['Ads'];
  
           // if(empty($FromErrors)){

            /*Check if Category Exit in Database*/                 ///////////////... Use Function ... //////////////////
                $check=CheckItem("Name", "categories", $name);
                if($check == 1){ 
                    $theMsg ='<div class="alert alert-danger">Sorry This Category is Exist</div>';
                    RedirectHome($theMsg,'back');
                }else{
                    /*Insert category info in Database*/
                    $stmt=$connect->prepare("INSERT INTO 
                                        categories(Name, Description, Ordering, Visibility, Allow_Comment,Allow_Ads)
                                        VALUE (:zname,:zdesc,:zorder,:zvisible,:zcomment,:zads)");  // (:zname) اي قيمه مش شرط حاجه معينه
                    $stmt->execute(array(
                    'zname'   =>   $name ,             //Bindparam               //input هنا بقي بخلي الاسم ده يساوي الحاجه اللي هكتبها في  
                    'zdesc'   =>   $desc ,     //~ ~ ~
                    'zorder'  =>   $order ,           //~ ~ ~~
                    'zvisible'=>   $visible ,            //~ ~ ~
                    'zcomment'=>   $comment ,
                    'zads'    =>   $ads 

                    ));

                    /*Echo Success Massage*/ 
                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() .'Recorded Inserted</div>'; 
                    RedirectHome($theMsg ,'back');  /*  سوف تتحدث قريبا باذن الله  manage اللي المفروض لما تضيف توديك لصفحه ال */
                }
              
          //  }
            
          }else{
            $theMsg='<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';    ///////////////... Use Function ... //////////////////
            RedirectHome($theMsg,3);
          }
          Echo "</div>";


    }
    elseif($do =='Edit'){

          //check if Get Request userid is Numeric $$ Get integer value(intval) of it
        $cat_id= isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']): 0 ;

          //Select All Data Depend on this ID
         $stmt= $connect->prepare("SELECT * FROM categories where ID = ? " ); 

          //Excute Query
         $stmt->execute(array($cat_id));

          //Fetch The Data
         $cat=$stmt->fetch(); // جلب المعلومات كلها 

          // ?? id بيعرفني هل فيه ريكورد في الداتا بيز بال  
         $count= $stmt->rowCount();
            
           // if  There's Such ID Show the Form
          if($count > 0){  ?>
            <h2 class="text-center">Edit Category</h2>
            <div class="container">
              <form class="form-horizontal" action="?do=Update" method="POST">
               <input type="hidden" name="catid" value="<?php echo $cat_id?>" >  <?php/*Update مخفي علشان يقدر يعتمد عليها في صفحه */ ?>
                <div class="form-group form-group-lg">
                  <label class="col-sm-2">Name</label>
                  <div class="col-sm-10">
                      <input type="text" name="name" class="form-control" value="<?php echo $cat['Name']?>" placeholder="Name of Category" required="required">
                  </div>
                </div>

                <div class="form-group form-group-lg">
                  <label class="col-sm-2">Description</label>
                  <div class="col-sm-10">
                      
                      <input type="text" name="description" class="form-control" value="<?php echo $cat['Description']?>" placeholder="Description The Category" >
                  </div>
                </div>

                <div class="form-group form-group-lg">
                  <label class="col-sm-2">Ordering</label>
                  <div class="col-sm-10">
                      <input type="text" name="order" class="form-control" value="<?php echo $cat['Ordering']?>" placeholder="Number to Arrange the Categories"  >
                  </div>
                </div>

                <div class="form-group form-group-lg">
                  <label class="col-sm-2">visible</label>
                  <div class="col-sm-10">
                      <div>
                          <input id="vis-yes" type='radio' name="visibility" value="0" <?php if($cat['Visibility'] == 0){ echo 'checked'; }?> >
                          <label for="vis-yes">yes</label>
                      </div>
                      <div>
                          <input id="vis-no" type='radio' name="visibility" value="1"  <?php if($cat['Visibility'] == 1){ echo 'checked'; }?>>
                          <label for="vis-no">No</label>
                      </div>
                  </div>
                </div>

                <div class="form-group form-group-lg">
                  <label class="col-sm-2">Allow Commenting</label>
                  <div class="col-sm-10">
                      <div>
                          <input id="com-yes" type='radio' name="commting" value="0" <?php if($cat['Allow_Comment'] == 0){ echo 'checked'; }?> >
                          <label for="com-yes">yes</label>
                      </div>
                      <div>
                          <input id="com-no" type='radio' name="commting" value="1" <?php if($cat['Allow_Comment'] == 1){ echo 'checked'; }?> >
                          <label for="com-no">No</label>
                      </div>
                  </div>
                </div>

                <div class="form-group form-group-lg">
                  <label class="col-sm-2">Allow Ads</label>
                  <div class="col-sm-10">
                      <div>
                          <input id="ads-yes" type='radio' name="Ads" value="0" <?php if($cat['Allow_Ads'] == 0){ echo 'checked'; }?> >
                          <label for="ads-yes">yes</label>
                      </div>
                      <div>
                          <input id="vis-no" type='radio' name="Ads" value="1" <?php if($cat['Allow_Ads'] == 1){ echo 'checked'; }?> >
                          <label for="vis-no">No</label>
                      </div>
                  </div>
                </div>

                <div class="form-group form-group-lg">
                  <div class="col-sm-offset-2 col-sm-10">
                      <input type="submit" value="Save Category" class="btn btn-primary btn-lg" >
                  </div>
                </div>


                </form>
        </div>     
            <?php
              //if there is no such id show Error Massage 
           } else{
               echo'<div class="container">';
                  $theMsg='<div class="alert alert-danger">theres is no such ID </div>';                ///////////////... Use Function ... //////////////////
                  RedirectHome($theMsg,3);
               echo'</div>';
           }

    }
    elseif($do =='Update'){

    }
    elseif($do =='Delete'){

    }

    include $tmp .'footer.php';

}else{
    header('location:index.php');
    exit();
}

ob_flush(); //Release the output


?>
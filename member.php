<?php
  /*
    manage member page
    You can Add || Edit || Delete memeber from here
  
  
  */
    session_start(); 
    $pageTitle='Member';
     
    if(isset($_SESSION['Username'])){
        
       include 'init.php';

       $do = isset($_GET['do'] )? $_GET['do'] : 'Manage';

         /*Start Manage Page*/
        if($do == 'Manage'){     ///////////////////////////////////////////////   (Manage page) ////////////////////////////////////

          $query='';    // to Show (pending people)
          if(isset($_GET['page']) && $_GET['page'] == 'pending'){
            $query='And Redstatus =0';
          }  

         $stmt=$connect->prepare("SELECT * FROM users WHERE Group_ID !=1  $query ");
         $stmt->execute();

         $records =$stmt->fetchAll();
        
        ?> 
         <h2 class="text-center">Manage Member</h2>
         <div class="container">
           <div class="table-responsive">
             <table class="main-table text-center table table-hover">
                <tr>
                  <td>#ID</td>
                  <td>Username</td>
                  <td>Email</td>
                  <td>Fullname</td>
                  <td>Registered Data</td>
                  <td>Control</td>
                </tr>
            <?php
                foreach($records as $record){

                  Echo"<tr>";
                    Echo"<td>". $record['User_ID']  . "</td>";
                    Echo"<td>". $record['Username'] . "</td>";
                    Echo"<td>". $record['Email']    . "</td>";
                    Echo"<td>". $record['FullName'] . "</td>";
                    Echo"<td>". $record['date']    . "</td>";
                    
                    Echo"<td>
                      <a href='member.php?do=Edit&userid=". $record['User_ID'] ."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                      <a href='member.php?do=Delete&userid=". $record['User_ID'] ." ' class='btn btn-danger'><i class='fa fa-close'></i>Delete</a>";

                      if($record['Redstatus'] == 0){
                        Echo "<a href='member.php?do=Activate&userid=". $record['User_ID'] ." ' class='btn btn-info Activate'>
                        <i class='fa fa-close'></i>Activate</a>";
                      }
                     Echo "</td  
                    107d>";

                  Echo"</tr>"; 
                }
              
            ?>
            
             </table>
       
           </div>
           <a href="member.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member </a>
         </div>

   <?php }elseif($do =='Add'){   ///////////////////////////////////////////////     (Add Page)    //////////////////////////////////
     ?>
        <h2 class="text-center">Add New Member</h2>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
              <div class="form-group form-group-lg">
                <label class="col-sm-2">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="Username" class="form-control"  autocomplete="off" placeholder="Username to Login Shop" required="required">
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Password</label>
                <div class="col-sm-10">
                    
                    <input type="password" name="Password" class="form-control" placeholder="Password Must be Hard & complex" required="required"   >
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="Email" class="form-control" placeholder="Email Must be Vaild"   required="required">
                </div>
              </div>

              <div class="form-group form-group-lg">
                <label class="col-sm-2">FullName</label>
                <div class="col-sm-10">
                    <input type="text" name="Full" class="form-control" placeholder="FullName Appear in Profile Page "  required="required">
                </div>
              </div>

              <div class="form-group form-group-lg">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Add Member" class="btn btn-primary btn-lg" >
                </div>
              </div>


              </form>
        </div>      
   <?php
        }elseif($do == 'Insert'){ 
         
          if($_SERVER['REQUEST_METHOD'] == 'POST'){

            Echo "<h2 class='text-center'>Insert Member</h2>";
            Echo " <div class='container'>";
               
            //Get variable from the [Form]
            $user  =$_POST['Username'];
            $pass  =$_POST['Password'];
            $email =$_POST['Email'];
            $full  =$_POST['Full'];

            $hashpassword=sha1($_POST['Password']);

            /*Validation The form*/
            $FromErrors=array();

            if(strlen($user < 3)){
               $FromErrors[] =' Username cant Be More than <strong> 15 characters</strong>';
            }

            if(empty($user)){
               $FromErrors[] ='Username can Not <strong>Empty</strong>';                  
            }

            if(empty($pass)){
              $FromErrors[]  ='password can Not <strong>Empty</strong>';                  
            }

            if(empty($email)){
              $FromErrors[]  ='Email can Not <strong>Empty</strong>';                  
            }

            if(empty($full)){
              $FromErrors[]  ='Fullname can Not <strong>Empty</strong>';               
            }

            //loop Error Array and Echo it
            foreach( $FromErrors as $error){
              Echo '<div class="alert alert-danger"> '. $error .'</div>';
            }
            
            /*Check if There NO Error -> Update the Data*/
            if(empty($FromErrors)){

                /*Check if user Exit in Database*/                 ///////////////... Use Function ... //////////////////
                $check=CheckItem("Username", "users", $user);
                if($check == 1){ 
                  $theMsg ='<div class="alert alert-danger">Sorry Username This user is Exit</div>';
                  RedirectHome($theMsg,'back');
                }else{
                  /*Insert Userinfo in Database*/
                  $stmt=$connect->prepare("INSERT INTO 
                                        users(UserName, Password, Email, FullName,Redstatus ,Date)
                                        VALUE (:zuser,:zpass,:zemail,:zname, 1 ,now() )");  // (:zname) اي قيمه مش شرط حاجه معينه
                  $stmt->execute(array(
                    'zuser'  =>   $user ,             //Bindparam               //input هنا بقي بخلي الاسم ده يساوي الحاجه اللي هكتبها في  
                    'zpass'  =>   $hashpassword ,     //~ ~ ~
                    'zemail' =>   $email ,           //~ ~ ~~
                    'zname'  =>   $full             //~ ~ ~
                  ));

                  /*Echo Success Massage*/ 
                  $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() .'Recorded Inserted</div>'; 
                  RedirectHome($theMsg ,'back');  /*  سوف تتحدث قريبا باذن الله  manage اللي المفروض لما تضيف توديك لصفحه ال */
                }
              
            }
            
          }else{
            $theMsg='<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';    ///////////////... Use Function ... //////////////////
            RedirectHome($theMsg,3);
          }
          Echo "</div>";

        }elseif($do == 'Edit'){     ////////////////////////////////////////////// (Page Edit)   ////////////////////////////////////
          // echo ' welcome to Edit Page your id = '.$_GET['userid'];
              /*important*/
          //check if Get Request userid is Numeric $$ Get integer value(intval) of it
         $user_id=(isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0 ;

          //Select All Data Depend on this ID
         $stmt= $connect->prepare(" SELECT * FROM users where User_ID =? " ); 

          //Excute Query
         $stmt->execute(array( $user_id));

         //Fetch The Data
         $row=$stmt->fetch(); // جلب المعلومات كلها 

         // ?? id بيعرفني هل فيه ريكورد في الداتا بيز بال  
         $count= $stmt->rowcount();

            //show the form
            if( $count > 0){  ?>
                <h2 class="text-center">Edit Member</h2>
                  <div class="container">
                      <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="userid" value="<?php echo $user_id?>" >   <?php/*Update مخفي علشان يقدر يعتمد عليها في صفحه */ ?>
                              
                        <div class="form-group form-group-lg">
                          <label class="col-sm-2">Username</label>
                          <div class="col-sm-10">
                              <input type="text" name="Username" class="form-control" value="<?php echo $row['Username']?>"  autocomplete="off" required="required">
                          </div>
                        </div>

                        <div class="form-group form-group-lg">
                          <label class="col-sm-2">Password</label>
                          <div class="col-sm-10">
                              <input type="hidden"   name="old-Password" value="<?php echo $row['Password']?>">
                              <input type="password" name="new-Password" class="form-control" placeholder="Leave Empty If You Dont want to Change"  >
                          </div>
                        </div>

                        <div class="form-group form-group-lg">
                          <label class="col-sm-2">Email</label>
                          <div class="col-sm-10">
                              <input type="email" name="Email" class="form-control"  value="<?php echo $row['Email']?>"  required="required">
                          </div>
                        </div>

                        <div class="form-group form-group-lg">
                          <label class="col-sm-2">FullName</label>
                          <div class="col-sm-10">
                              <input type="text" name="Full" class="form-control"  value="<?php echo $row['FullName']?>"  required="required">
                          </div>
                        </div>

                        <div class="form-group form-group-lg">
                          <div class="col-sm-offset-2 col-sm-10">
                              <input type="submit" value="Update" class="btn btn-primary btn-lg" >
                          </div>
                        </div>


                        </form>
                  </div>      

        <?php
              //if there is no such id show Error Massage 
           } else{
              $ErrorMsg='theres is no such ID';                ///////////////... Use Function ... //////////////////
              RedirectHome($ErrorMsg,3);
            }

          }elseif($do =='Update'){   /////////////////////////////////////////////////// (Update page) //////////////////////////////
              Echo "<h2 class='text-center'>Update Member</h2>";
              Echo " <div class='container'>";

                  if($_SERVER['REQUEST_METHOD'] == 'POST'){
                      
                        //Get variable from the Form
                        $id    = $_POST['userid'];
                        $user  = $_POST['Username'];
                        $email = $_POST['Email'];
                        $full  = $_POST['Full'];

                        /*Password Track*/
                        /* Condition ? true : False;
                          $pass= empty($_POST['new-password'])? $_POST['old-password'] : sha1($_POST['new-password']);
                        */
                        $pass='';
                        if(empty($_POST['new-Password'])){
                          $pass=$_POST['old-Password'];
                        }else {
                          $pass=sha1($_POST['new-Password']);
                          }

                        /*Validation The form*////////////////
                        $FromErrors=array();

                        if(strlen($user > 15)){
                          $FromErrors[] ='<div class="alert alert-danger"> Username cant Be More than <strong>15 characters</strong> </div>';
                          }

                        if(empty($user)){
                          $FromErrors[] ='<div class="alert alert-danger">Username can Not <strong>Empty</strong></div>';                  
                          }

                        if(empty($email)){
                          $FromErrors[] ='<div class="alert alert-danger">Email can Not <strong>Empty</strong></div>';                  
                          }

                        if(empty($full)){
                          $FromErrors[] ='<div class="alert alert-danger">Fullname can Not <strong>Empty</strong></div>';               
                          }

                        //loop Error Array and Echo it
                        foreach( $FromErrors as $error){
                          Echo $error .'<br/>';
                          }
                        
                        /*Check if There NO Error -> Update the Data*/
                        if(empty($FromErrors)){
                            /*Update the Database with the info*/
                        $stmt=$connect->prepare("UPDATE users SET Username =? ,Email =?,FullName =? ,Password=? WHERE User_ID =? " );
                        $stmt->execute(array($user ,$email ,$full ,$pass ,$id ));

                        /*Echo Success Massage*/ 
                        $theMsg='<div class="alert alert-success">' . $stmt->rowCount() .'Recorded Updated</div>'; 
                        RedirectHome($theMsg,'back');
                        }
                    
                  }else{
                    $theMsg= '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';        ///////////////... Use Function ... //////////////////
                    RedirectHome($theMsg);
                    }
              Echo "</div>";

          }elseif($do =='Delete'){    /////////////////////////////////////////////////// (Delete page) //////////////////////////////
 
            Echo "<h2 class='text-center'>Delete Member</h2>";
            Echo " <div class='container'>";

                  //check if Get Request user_id is Numeric $$ Get integer value(intval) of it
                $user_id=(isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']): 0 ;

                  //Select All Data Depend on this ID
      /*          $stmt= $connect->prepare(" SELECT * FROM users where User_ID =? " ); 
        
                     /*Excute Query***
                $stmt->execute(array( $user_id ));
                      /*Fecth The Data***
                $row=$stmt->fetch();  //Delete مش عاوزها في صفحه

                       /* ?? id بيعرفني هل فيه ريكورد في الداتا بيز بال **** 
                $count= $stmt->rowcount();
      */

                  //Select All Data Depend on this ID
                $check = CheckItem( 'User_ID', 'users' ,$user_id);

                if($check >0){
                  $stmt=$connect->prepare("DELETE FROM users WHERE User_ID = :zuser");
                  $stmt->execute(array(
                    ':zuser' =>$user_id         //Bindparam 
                  )); 
                  $stmt->execute();

                  $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() .'Recorded Deleted</div>'; 
                  RedirectHome($theMsg,'back');  /////

                }else{
                $theMsg= '<div class="alert alert-danger">This is ID NOT Exit </div>';
                RedirectHome($theMsg);
                }
            
           Echo "</div>";

          }elseif($do == 'Activate'){

            Echo "<h2 class='text-center'>Activate Member</h2>";
            Echo " <div class='container'>";

                  //check if Get Request user_id is Numeric $$ Get integer value(intval) of it
              $user_id=(isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']): 0 ;

              $check= CheckItem('User_ID','users',$user_id);
          
                if($check > 0){
                    //Select All Data Depend on this ID
                    $stmt= $connect->prepare("UPDATE users SET Redstatus =1 WHERE User_ID =? " ); 
                    //Excute Query
                    $stmt->execute(array($user_id));

                    $theMsg= '<div class="alert alert-success">' . $stmt->rowCount() .'Recorded Updated</div>'; 
                    RedirectHome($theMsg,'back');  /////

                }else{
                  $theMsg= '<div class="alert alert-danger">This is ID NOT Exit </div>';
                  RedirectHome($theMsg);
                }
                
           Echo "</div>";
  
          }
        
       include $tmp.'Footer.php';
    }

    else{
      
        header('location:index.php');
        exit();
    }
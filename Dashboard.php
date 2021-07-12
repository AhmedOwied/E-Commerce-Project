<?php 
    session_start(); 

    if(isset($_SESSION['Username'])){

        $pageTitle='Dashboard';

        include 'init.php';

        $latestUsers =4;   /*Number of latest User*/
        $Thelatest= Getlatest('*','users','User_ID',$latestUsers); /*Latest User Array*/
     ?>
     <div class="container home-stats text-center">
        <h2>Dashboard</h2>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat total-member">
                    Total Member
                    <span><a href="member.php"> <?php Echo CountItem('User_ID','users')?></a> </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat pend-member">
                    Pending Member
                    <span><a href="member.php?do=Manage&page=pending"> <?php Echo  CheckItem("Redstatus","users",0)?> </a></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat item">
                    Total Item
                    <span>150</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat comment">
                    Total Comments
                    <span>3500</span>
                    </div>
                </div>
            </div>

            <div class="continer latest">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                               <i class="fa fa-users"></i> Latest<?php Echo $latestUsers;?>Registerd Users
                            </div>
                            <div class="panel-body">
                               <ul class='list-unstyled latest-users'>
                                 <?php 
                                       foreach($Thelatest as $user){
                                            Echo '<li>'; 
                                            Echo $user['Username'];
                                                Echo '<a href="member.php?do=Edit&userid='. $user['User_ID']. '">';
                                                  Echo '<span class="btn btn-success pull-right">';
                                                    Echo '<i class="fa fa-edit"></i>Edit';   
                                                  Echo '</span>';
                                                Echo '</a>';
                                             Echo '</li>';
                                        }
                                 ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                            <i class="fa fa-tags"></i> Latest Items
                            </div>
                            <div class="panel-body">
                                Test
                            </div>
                        </div>
                     </div>

                </div>
           </div>
     </div>

     <?php
       include $tmp.'Footer.php';
    }

    else{
        echo 'You Are not view this page';
        header('location:index.php');
        exit();
    }
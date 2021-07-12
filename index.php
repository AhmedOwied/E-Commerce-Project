<?php 
    session_start();
    $pageTitle='Login';
    $noNavbar = '';
    if(isset($_SESSION['Username'])){
       //  header('location:Dashboard.php'); //direct to Dashboard page
    }

   include "init.php";

   // check if User coming from HTTP POST Request
   if( $_SERVER['REQUEST_METHOD'] == 'POST'){

        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashpass = sha1($password);               //////////////// ??????? ////////// ????????? /////////

        //check if  the User Exit in Database Or NOT
        $stmt= $connect->prepare(" SELECT 
                                    User_ID, Username , Password
                                FROM  
                                    users 
                                where
                                        Username = ?
                                AND  
                                        Password = ? 
                                AND 
                                        Group_ID =  1 "
                                ); // (statement)   database ه حاجات قبل ما يخش علي يعني ينفذ(Databaseاستعلام )

        $stmt->execute(array($username,$hashpass));
        $row=$stmt->fetch(); // جلب المعلومات كلها 
        $count= $stmt->rowcount();

        //if count > 0 this mean the Database contain Record About this Username [ Admin]
        if ($count >0){
            $_SESSION['Username']= $username;  //Register session name
            $_SESSION['ID']= $row['User_ID'];  //Register session ID
            header('location:Dashboard.php'); //Direct to Dashboard page
            exit;
        }
        else{
            echo 'Error!!';
        }
        
    }

?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>"  method="POST">
        <h3 class="text-center ">Admin Login</h3>

        <input class="form-control" type="username" name="user" placeholder="Username" autocomplete="off">
        <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
        <input class="btn btn-primary btn-block" type="submit" value="Login">
    
    </form>

<?php include $tmp.'Footer.php'; ?>
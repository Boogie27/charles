<?php

 $host = "localhost";
 $name = "root";
 $passsword = "";
 $db_name = "shop_boutique";
    $connect = mysqli_connect($host,$name,$passsword,$db_name);
    $connect_error = mysqli_connect_error();
    if($connect_error == true){
        echo "connection to database failed".$connect_error;
    }else{
 
    }
    ob_start();//object boffering start
    session_start();
    define("BASEURL","/boutique/");
    define("TAXRATE",0.087);
    define("CART_COOKIE","EmiRpsUmitPo");
    define("EXPIRE_DATE",time() + (86400 * 30));
    $cookie_id = '';
    if(isset($_COOKIE[CART_COOKIE])){
            $cookie_id = $_COOKIE[CART_COOKIE];
    }

         if(isset($_SESSION["id"])){
              $id = $_SESSION["id"];
              $sql = "SELECT * FROM users WHERE id='$id'";
              $result = mysqli_query($connect,$sql);
              $row_user = mysqli_fetch_assoc($result);
                 $firstname = $row_user["surname"];
            }
       
            include_once "helper/helper.php";

    if(isset($_SESSION["success"])){
        echo "<div class='bg-success success' style='padding: 10px;'><p class='text-success text-center'>".$_SESSION["success"]."</p></div>";
       unset($_SESSION["success"]);
   }
   if(isset($_SESSION["error"])){
    echo "<div class='bg-danger dager' style=''><p class='text-danger text-center'>".$_SESSION["error"]."</p></div>";
    unset($_SESSION["error"]);
}
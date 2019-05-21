<?php

    function get_error($error){
           $display = "<ul class='bg-danger'>";
                         foreach($error as $errors){
                            $display .= "<p class='text-danger text-center'>".$errors."</p>";
                         }
           $display .= "</ul>";
           return $display;
    }
    
  function login($user_id){
      $_SESSION["id"] = $user_id;
      global $connect;
      $date = date("Y-m-d H:i:s");
      $sql = "UPDATE users SET last_login='$date' WHERE id='$user_id'";
      mysqli_query($connect,$sql);
      $_SESSION["success"] = "you are logged in! ";
      header("Location: index.php");
  }
  function login_user(){
      if(isset($_SESSION["id"]) && $_SESSION["id"] > 0){
         return true;
      }
      return false;
  }

  function login_redirect(){
    $_SESSION["error"] = "You must login to access that page";
      header("location: login.php");
     
  }

  function permission($permission = "admin"){
        global $row_user;//located in the connection page
        $permite = explode(",",$row_user["permission"]);
       if(in_array($permission,$permite,true)){
         return true;
        }
        return false;
  }

  function  permissions_redirect(){
    $_SESSION["error"] = "you are not allowed to access that page if you are not the admin";
    header("Location: index.php");
  }


   function fancy_date($date){
    return date("M d, Y H:i A",strtotime($date));
   }

   function money($money){
     return " &#8358; ".number_format($money,2);
   }
   
   function sizes_array($sizesString){
    $size_item = explode(",", $sizesString);
    $sizes_array = array();
    foreach($size_item as $keys){
         $item_sizes = explode(":", $keys);
         $sizes_array[] = array("sizes" => $item_sizes[0], "quantity" => $item_sizes[1]);
    }
    return $sizes_array;
   }

   function sizetoString($new_sizes){
        $sizes_string = "";
        foreach($new_sizes as $keys_size){
          $sizes_string .=  $keys_size["sizes"].":".$keys_size["quantity"].",";
        }
        $trim = rtrim($sizes_string, ",");
        return $trim;
   }
   

  
?>
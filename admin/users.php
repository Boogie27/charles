<?php
   require_once $_SERVER["DOCUMENT_ROOT"]."/boutique/connection.php";
   if(!login_user()){
    login_redirect();
   }
   if(!permission()){
    permissions_redirect();
   }
   include "include/header.php";
   include "include/navigation.php";
   $error = array();
?>

<?php   if(isset($_GET["add"])){ 
             //add a user-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
             $surname =  ((isset($_POST["surname"]) && !empty($_POST["surname"])) ? mysqli_real_escape_string($connect,$_POST["surname"]) :'');
             $name = ((isset($_POST["name"]) && !empty($_POST["name"])) ? mysqli_real_escape_string($connect,$_POST["name"]) :'');
             $email =  ((isset($_POST["email"]) && !empty($_POST["email"])) ? mysqli_real_escape_string($connect,$_POST["email"]) : '');
             $password = ((isset($_POST["password"]) && !empty($_POST["password"])) ? mysqli_real_escape_string($connect,$_POST["password"]) : '');
             $confirm_password =  ((isset($_POST["confirm_password"]) && !empty($_POST["confirm_password"])) ? mysqli_real_escape_string($connect,$_POST["confirm_password"]) : '');
             $permission =  ((isset($_POST["permission"]) && !empty($_POST["permission"])) ? mysqli_real_escape_string($connect,$_POST["permission"]) : '');
             $home_adress =  ((isset($_POST["home_adress"]) && !empty($_POST["home_adress"])) ? mysqli_real_escape_string($connect,$_POST["home_adress"]) : '');
             $phone_number =  ((isset($_POST["phone_number"]) && !empty($_POST["phone_number"])) ? mysqli_real_escape_string($connect,$_POST["phone_number"]) : '');
             $state =  ((isset($_POST["state"]) && !empty($_POST["state"])) ? mysqli_real_escape_string($connect,$_POST["state"]) : '');
             $country =  ((isset($_POST["country"]) && !empty($_POST["country"])) ? mysqli_real_escape_string($connect,$_POST["country"]) : '');
           
             if(isset($_POST["add_user"])){
               if(empty($full_name) || empty($surname) || empty($email) || empty($password) || empty($confirm_password) || empty($permission) || empty($home_adress) || empty($phone_number) || empty($state) || empty($country)){
                    $error[] = "One or more fields is Empty!";
                }else{
                    $sql = "SELECT * FROM users WHERE email = '$email'";
                    $result = mysqli_query($connect,$sql); 
                    if(mysqli_num_rows($result) > 0){
                        $error[] = "<strong>".$email."</strong> has already Been Used!";
                    }else{
                        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                            $error[] = "You must insert a valid email!";
                        }else{
                            if($password != $confirm_password){
                                $error[] = "Password Does not Match the Comfirm Password!";
                             }else{
                                if(strlen($password) < 6 || strlen($password) > 12){
                                    $error[] = "Password Should be between 6 to 12 characters!";
                                 }else{
                                    if(!preg_match("/^[0-9]*$/",$phone_number)){
                                        $error[] = "Wrong Phone Number Format";
                                     }else{
                                        if(strlen($phone_number) < 11 || strlen($phone_number) > 11 ){
                                            $error[] = "Phone Number must be of 11 characters!";
                                         }else{
                                            if(!empty($_FILES["image"])){
                                                $file = $_FILES["image"];
                                                $file_name = $file["name"];
                                                $file_tmp_name = $file["tmp_name"];
                                                $file_size = $file["size"];
                                                $file_type = $file["type"];
                                                $file_error = $file["error"];
                                                  $file_ext = explode(".",$file_name);
                                                  $file_actual_ext = strtolower(end($file_ext));
                                                  $file_allowed = array('jpg','jpeg','png');
                                                  $file_new_name = "image"."_".md5(microtime()).".".$file_actual_ext;
                                                  $file_location = "users_images/".$file_new_name;
                                                  $db_path = "/boutique/admin/users_images/".$file_new_name;
                                                  if(!in_array($file_actual_ext,$file_allowed)){
                                                       $error[] = "image must be jpg, jpeg or png!";   
                                                  }else{
                                                    if($file_error === 1){
                                                        $error[] = "There was an Errror uploading this file!"; 
                                                   }else{
                                                    if($file_size > 1000000){
                                                        $error[] = "The Image File size your are tryign to upload is too big";
                                                    }
                                                   }
                                                  }      
                                        }
                            
                                         }
                                     }
                                 }
                             }
                        }
                    }
                }
               
               


                 
                

           
                   
        //check for errors
          if(!empty($error)){ ?>
          <div class='error_text'><p><?= get_error($error); ?> </p></div>
     <?php   }else{
        $date_join = date("Y-m-d H:i:s");
         $password_hash = password_hash($password,PASSWORD_DEFAULT);
         move_uploaded_file($file_tmp_name,$file_location);
         $sql = "INSERT INTO users(surname,full_name,email,password,permission,address,phone,state,country,image,date_join) 
                           VALUES('$surname','$name',$email','$password_hash','$permission','$home_adress','$phone_number','$state','$country','$db_path','$date_join')";
        mysqli_query($connect,$sql);
        $_SESSION["success"] = "User has been added sucessfuly!";
         header("Location: users.php?");
          }
        }
     ?>
     
<div class='container'>
     <h2 class='text-center'>Add Users</h2><hr>
         <div class='signup_form' id='signup_form'>
              <form action='users.php?add=1' method='POST' enctype='multipart/form-data'>
                   <div class='form-group col-md-6'>
                        <label for='surname'>Surname :</label>
                          <input type='text' name='surname' id='surname'class='form-control' value='<?=$surname ;?>'>
                       </div>
                   <div class='form-group col-md-6'>
                        <label for='full_name'>Full Name :</label>
                        <input type='text' name='name' id='name'class='form-control' value='<?=$name ;?>'>
                   </div>
                   <div class='form-group col-md-6'>
                        <label for='email'>Email :</label>
                        <input type='email' name='email' id='email'class='form-control' value='<?=$email ;?>' >
                   </div>
                   <div class='form-group col-md-6'>
                        <label for='home_adress'>Home Address :</label>
                        <input type='text' name='home_adress' id='home_adress'class='form-control' value='<?=$home_adress ;?>' >
                   </div>
                   <div class='form-group col-md-6'>
                        <label for='tele'>Phone Number :</label>
                        <input type='text' name='phone_number' id='phone_number'class='form-control' value='<?=$phone_number ;?>' >
                   </div>
                   <div class='form-group col-md-6'>
                        <label for='state'>State Of Origin:</label>
                        <select name='state' id='state' class='form-control'>
                             <option value=''></option>
                             <?php $sql_state = "SELECT * FROM state  ORDER BY state";
                                    $result_state = mysqli_query($connect,$sql_state);
                                    while($state = mysqli_fetch_assoc($result_state)):?>
                             <option value='<?=$state["state"] ;?>'<?=(($state == $state["state"])? 'selected': '') ;?>><?=$state["state"] ;?></option>
                             <?php endwhile ;?>
                        </select>
                   </div>
                   <div class='form-group col-md-6'>
                        <label for='country'>Country Of Origin:</label>
                        <select name='country' id='country' class='form-control'>
                             <option value=''></option>
                             <?php $sql_country = "SELECT * FROM countries";
                                    $result_country = mysqli_query($connect,$sql_country);
                                    while($country = mysqli_fetch_assoc($result_country)):?>
                             <option value='<?=$country["country"] ;?>'<?=(($country == $country["country"])? 'selected': '') ;?>><?=$country["country"] ;?></option>
                             <?php endwhile ;?>
                        </select>
                   </div>
                   <div class='form-group col-md-6'>
                        <label for='full_name'>Password :</label>
                        <input type='password' name='password' id='password'class='form-control' value='' >
                   </div>
                   <div class='form-group col-md-6'>
                        <label for='full_name'>Confirm Password :</label>
                        <input type='password' name='confirm_password' id='confirm_password'class='form-control' value='' >
                   </div>
                   <div class='form-group col-md-6'>
                           <label for='permission'>Permission :</label>
                           <select name='permission' id='permission' class='form-control'>
                               <option value=''></option>
                               <option value='admin'>Admin</option>
                               <option value='editor'>Editor</option>
                               <option value='admin,editor'>Admin / Editor</option>
                               <option value='agent'>Agent</option>
                           </select>
                        </div>
                        <div class='form-group col-md-6'>
                           <input type='file' name='image' id='image'class='form-control' value='' >
                        </div>
                        <div class='form-group pull-right'>
                            <a href='users.php' class='btn btn-default'>Cancle</a>
                            <input type='submit' name='add_user' id='add_user'class='btn btn-success' value='Add User'>
                        </div>
                   </div>
              </form>
     </div>
</div></div><br><br>



   <?php } else{ ?>
<?php
//active user-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   if(isset($_GET["active"])){
           $active = (int)$_GET["active"];
           $active = mysqli_real_escape_string($connect,$active);
           $active_id = (int)$_GET["id"];
           $active_id = mysqli_real_escape_string($connect,$active_id);
           $sql_update = "UPDATE users SET active = '$active' WHERE id='$active_id'";
           $result = mysqli_query($connect,$sql_update);
           header("Location: users.php");
   }

?>
 


       <div class='container'>
            <h2 class='text-center'>Users</h2>
            <a href='users.php?add=1' class='btn btn-success pull-right' id='add_product'>Add user</a><hr>
            <table class='table table-bordered table-stripped table-condensed'>
                <thead><th>detail</th> <th>Name</th> <th>Email</th> <th>Join-date</th> <th>Last-Login</th> <th>Permission</th> <th>active</th> <th>Delete</th></thead>
                <tbody>
                <?php $sql = "SELECT * FROM users";
                       $result = mysqli_query($connect,$sql);
                       while($row = mysqli_fetch_assoc($result)): ?>
                   <tr style='color: <?=(($row["active"] == 0)? '' : "red") ;?>'>
                      <td><a href='user_detail.php?detail=<?=$row["id"] ;?>' class='btn btn-xs btn-warning'>Detail</a></td>
                      <td><?=$row["name"] ;?></td>
                      <td><?=$row["email"] ;?></td>
                      <td><?=$row["date_join"] ;?></td>
                      <td><?=(($row["last_login"] == "0000-00-00 00:00:00")? 'Never': fancy_date($row["last_login"])) ;?></td>
                      <td><?=$row["permission"] ;?></td>
                      <td>
                      <?php  if($row["id"] != $row_user["id"]): ?>
                                <a href='users.php?id=<?=$row["id"] ;?>&active=<?=(($row["active"] == 0)? 1 : 0) ;?>' class='btn btn-xs btn-default'><span style='color: <?=(($row["active"] == 0)? "green" : "red") ;?>' class='glyphicon glyphicon-<?=(($row["active"] == 0)? "minus" : "plus") ;?>'></span></a>
                            <?php endif ;?>
                      </td>
                      <td><?php if($row["id"] != $row_user["id"]):?>
                          <a href='users.php?delete=<?=$row["id"] ;?>' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></a>
                        <?php endif ;?>
                     </td>
                    </tr>
                      <?php endwhile; ?>
                </tbody>
            </table>
     </div>  




<?php } include "include/footer.php" ;?>
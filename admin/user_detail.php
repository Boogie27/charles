<?php
   require_once $_SERVER["DOCUMENT_ROOT"]."/boutique/connection.php";
   include "include/header.php";
   include "include/navigation.php";
   $error = array();
?>
<?php
          
     if(isset($_GET["detail"])){
           $detail_id = (int)$_GET["detail"];
           $detail_id = mysqli_real_escape_string($connect,$detail_id);
           $sql = "SELECT * FROM users WHERE id='$detail_id'";
           $result = mysqli_query($connect,$sql);
           $row = mysqli_fetch_assoc($result);
           $state_id = $row["state"];
           $country_id = $row["country"];
          
     
//detail name fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$name = ((isset($_POST["name"]) && !empty($_POST["name"]))? mysqli_real_escape_string($connect,$_POST["name"]) : $row["name"] );
   if(isset($_POST["name_edit"])){
        $name = mysqli_real_escape_string($connect,$_POST["name"]);
        if(empty($name)){
            $_SESSION["error"] = "The name field is Empty!";
            header("Location: user_detail.php?detail=$detail_id&name=$detail_id"); 
        }else{
             if(!preg_match("/^[a-zA-Z]*$/",$name)){
                $_SESSION["error"] = "Only alphabets are allowed in the name field!";
                header("Location: user_detail.php?detail=$detail_id&name=$detail_id"); 
             }else{
                 $sql = "UPDATE users SET name = '$name' WHERE id='$detail_id'";
                 mysqli_query($connect,$sql);
                 header("Location: user_detail.php?detail=".$detail_id);
             }
        }
     }
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//detail surname fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$surname = ((isset($_POST["surname"]) && !empty($_POST["surname"]))? mysqli_real_escape_string($connect,$_POST["surname"]) : '' );
if(isset($_POST["surname_edit"])){
    $surname = mysqli_real_escape_string($connect,$_POST["surname"]);
    if(empty($surname)){
        $_SESSION["error"] = "The surname field is Empty!";
        header("Location: user_detail.php?detail=$detail_id&surname=$detail_id"); 
    }else{
         if(!preg_match("/^[a-zA-Z]*$/",$surname)){
            $_SESSION["error"] = "Only alphabets are allowed in the surname field!";
            header("Location: user_detail.php?detail=$detail_id&surname=$detail_id"); 
         }else{
             $sql = "UPDATE users SET surname = '$surname' WHERE id='$detail_id'";
             mysqli_query($connect,$sql);
             header("Location: user_detail.php?detail=".$detail_id);
         }
    }
 }
 //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  //email fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
  $email = ((isset($_POST["email"]) && !empty($_POST["email"]))? mysqli_real_escape_string($connect,$_POST["email"]) : '' );
  if(isset($_POST["email_edit"])){
    $email = mysqli_real_escape_string($connect,$_POST["email"]);
    if(empty($email)){
        $_SESSION["error"] = "The Email field is Empty!";
        header("Location: user_detail.php?detail=$detail_id&email=$detail_id"); 
    }else{
         if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION["error"] = "Wrong Email Format!";
            header("Location: user_detail.php?detail=$detail_id&email=$detail_id"); 
         }else{
             $sql = "UPDATE users SET email = '$email' WHERE id='$detail_id'";
             mysqli_query($connect,$sql);
             header("Location: user_detail.php?detail=".$detail_id);
         }
    }
 }
 //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//address fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$address = ((isset($_POST["address"]) && !empty($_POST["address"]))? mysqli_real_escape_string($connect,$_POST["address"]) : '' );
if(isset($_POST["address_edit"])){
    $address = mysqli_real_escape_string($connect,$_POST["address"]);
    if(empty($address)){
        $_SESSION["error"] = "The address field is Empty!";
        header("Location: user_detail.php?detail=$detail_id&address=$detail_id"); 
    }else{
        $sql = "UPDATE users SET address = '$address' WHERE id='$detail_id'";
        mysqli_query($connect,$sql);
        header("Location: user_detail.php?detail=".$detail_id);
    }
 }
 //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//state fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$state = ((isset($_POST["state"]) && !empty($_POST["state"]))? mysqli_real_escape_string($connect,$_POST["state"]) : '' );
if(isset($_POST["state_edit"])){
    $state = mysqli_real_escape_string($connect,$_POST["state"]);
    if(empty($state)){
        $_SESSION["error"] = "The State field is Empty!";
        header("Location: user_detail.php?detail=$detail_id&state=$detail_id"); 
    }else{
        $sql = "UPDATE users SET state = '$state' WHERE id='$detail_id'";
        mysqli_query($connect,$sql);
        header("Location: user_detail.php?detail=".$detail_id);
    }
 }
 //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//country fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$country = ((isset($_POST["country"]) && !empty($_POST["country"]))? mysqli_real_escape_string($connect,$_POST["country"]) : '' );
if(isset($_POST["country_edit"])){
    $country = mysqli_real_escape_string($connect,$_POST["country"]);
    if(empty($country)){
        $_SESSION["error"] = "The Country field is Empty!";
        header("Location: user_detail.php?detail=$detail_id&country=$detail_id"); 
    }else{
        $sql = "UPDATE users SET country = '$country' WHERE id='$detail_id'";
        mysqli_query($connect,$sql);
        header("Location: user_detail.php?detail=".$detail_id);
    }
 }
 //------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//detail surname fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
$phone = ((isset($_POST["phone"]) && !empty($_POST["phone"]))? mysqli_real_escape_string($connect,$_POST["phone"]) : '' );
if(isset($_POST["phone_edit"])){
    $phone = mysqli_real_escape_string($connect,$_POST["phone"]);
    
    if(empty($phone)){
        $_SESSION["error"] = "The Phone field is Empty!";
        header("Location: user_detail.php?detail=$detail_id&phone=$detail_id"); 
    }else{
         if(!preg_match("/^[0-9]*$/",$phone)){
            $_SESSION["error"] = "Only number Digits are allowed in the Phone field!";
            header("Location: user_detail.php?detail=$detail_id&phone=$detail_id"); 
         }else{
             if(strlen($phone) < 11 || strlen($phone) > 11){
                $_SESSION["error"] = "Number should be of 11 digits!";
                header("Location: user_detail.php?detail=$detail_id&phone=$detail_id"); 
             }else{
                $sql = "UPDATE users SET phone = '$phone' WHERE id='$detail_id'";
                mysqli_query($connect,$sql);
                header("Location: user_detail.php?detail=".$detail_id);
             }
         }
    }
 }
}
 //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 //permission fields----------------------------------------------------------------------------------------------------------------------------------------------------------------------
 $permission = ((isset($_POST["permission"]) && !empty($_POST["permission"]))? mysqli_real_escape_string($connect,$_POST["permission"]) : '' );
 if(isset($_POST["permission_edit"])){
     $permission = mysqli_real_escape_string($connect,$_POST["permission"]);
     if(empty($permission)){
         $_SESSION["error"] = "The permission field is Empty!";
         header("Location: user_detail.php?detail=$detail_id&permission=$detail_id"); 
     }else{
          if(!preg_match("/^[a-zA-Z ,]*$/",$permission)){
             $_SESSION["error"] = "Only alphabets are allowed in the permission field!";
             header("Location: user_detail.php?detail=$detail_id&permission=$detail_id"); 
          }else{
              $sql = "UPDATE users SET permission = '$permission' WHERE id='$detail_id'";
              mysqli_query($connect,$sql);
              header("Location: user_detail.php?detail=".$detail_id);
          }
     }
  }
  //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


 if(!empty($error)){
     echo get_error($error);
     }

?>
     

     <div class='container'>
         <form action='user_detail.php?<?=((isset($_GET["edit"]))? 'edit='.$detail_id : 'detail='.$detail_id) ;?>' method='POST' enctype='multipart/form-data'>
         <h2 class='text-center'>User Detail</h2>
            <a href='users.php' class='btn btn-default pull-right' id='back-button'>Back</a>
         <hr>
         <div class='col-md-4' id='user_images'>
             <img src='<?=$row["image"] ;?>' alt='charles'>
        </div>
         <div class='col-md-4'>
         <div class='form-group' id='edit_field'>
                     <label for='surname'>Surname : </label>
                     <?php if(isset($_GET["surname"])):?>
                     <div class='form-group'> 
                       <h4><?=$row["surname"] ;?></h4>                    
                       <input type='text' name='surname' id='form_field' value='<?=$surname ;?>'>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='surname_edit' id='surname_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                     <?php else :?>
                         <h4><?=$row["surname"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."surname=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4> 
                      <?php endif ;?>
            </div>
            <div class='form-group' id='edit_field'>
                 <label for='name'>Name : </label>
                     <?php if(isset($_GET["name"])):?>
                     <h4><?=$row["name"] ;?></h4>
                     <div class='form-group'>
                       <input type='text' name='name' id='form_field' value='<?=$name ;?>'>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='name_edit' id='name_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                     <?php else :?>
                        <h4><?=$row["name"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."name=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4>                          
                     <?php endif ;?>
            </div>
            <div class='form-group' id='e_field'>
                 <label for='email'>Email : </label>
                     <?php if(isset($_GET["email"])):?>
                     <h4><?=$row["email"] ;?></h4>
                     <div class='form-group'>
                       <input type='email' name='email' id='form_field' value='<?=$email ;?>'>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='email_edit' id='email_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                     <?php else :?>
                     <h4><?=$row["email"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."email=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4> 
                      <?php endif ;?>
            </div>
        
         <div class='form-group' id='address_field'>
                 <label for='permission'>Permission : </label>
                     <?php if(isset($_GET["permission"])):?>
                     <h4><?=$row["permission"] ;?></h4>
                     <div class='form-group'>
                       <input type='text' name='permission' id='form_field' value='<?=$permission ;?>'>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='permission_edit' id='permission_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                     <?php else :?>
                       <h4><?=$row["permission"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."permission=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4> 
                      <?php endif ;?>
            </div>
            </div>

         <div class='col-md-4'>
         <div class='form-group' id='address_field'>
                 <label for='address'>Address : </label>
                     <?php if(isset($_GET["address"])):?>
                     <h4><?=$row["address"] ;?></h4>
                     <div class='form-group'>
                       <input type='text' name='address' id='form_field' value='<?=$address ;?>'>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='address_edit' id='address_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                     <?php else :?>
                       <h4><?=$row["address"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."address=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4> 
                      <?php endif ;?>
            </div>
            <div class='form-group' id='address_field'>
                 <label for='state'>State Of Origin: </label>
                 <?php $rows = $connect->query("SELECT * FROM state WHERE id='$state_id'");
                         $state_row = mysqli_fetch_assoc($rows);
                        ?>
                     <?php if(isset($_GET["state"])):?>
                     <h4><?=$state_row["state"] ;?></h4>
                     <div class='form-group'>
                       <select name='state' id='form_field'>
                           <option value=''></option>
                           <?php $sql = $connect->query("SELECT * FROM state");
                                  while($row_state = mysqli_fetch_assoc($sql)):?>
                           <option value='<?=$row_state["id"] ;?>'><?=$row_state["state"] ;?></option>
                           <?php endwhile ;?>
                       </select>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='state_edit' id='state_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                     <?php else :?>
                     <h4><?=$state_row["state"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."state=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4> 
                     <?php endif ;?>
            </div>
            <div class='form-group' id='address_field'>
                 <label for='country'>Country : </label>
                 <?php $rows_country = $connect->query("SELECT * FROM countries WHERE id='$country_id'");
                                  $country_row = mysqli_fetch_assoc($rows_country); ?>
                     <?php if(isset($_GET["country"])):?>
                     <h4><?=$country_row["country"] ;?></h4>
                     <div class='form-group'>
                       <select name='country' id='form_field'>
                           <option value=''></option>
                           <?php $sql_country = $connect->query("SELECT * FROM countries");
                                  while($row_country = mysqli_fetch_assoc($sql_country)):?>
                           <option value='<?=$row_country["id"] ;?>'><?=$row_country["country"] ;?></option>
                           <?php endwhile ;?>
                       </select>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='country_edit' id='country_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                     <?php else :?>
                     <h4><?=$country_row["country"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."country=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4> 
                     <?php endif ;?>
            </div>
            <div class='form-group' id='edit_field'>
                 <label for='phone'>Phone Number : </label>
                     <?php if(isset($_GET["phone"])):?>
                     <div class='form-group'>
                     <h4><?= $row["phone"] ;?></h4> 
                       <input type='text' name='phone' id='form_field' value='<?=$phone ;?>'>
                       <a href='user_detail.php?<?="detail=".$detail_id ;?>' class='btn btn-xs btn-default'>Cancle</a>
                       <input type='submit' name='phone_edit' id='phone_edit' class='btn btn-xs btn-success' value='Edit'>
                     </div>
                      <?php else :?>
                      <h4><?=$row["phone"] ;?> <a href='user_detail.php?<?="detail=".$detail_id."&"."phone=".$detail_id ;?>'><span class='glyphicon glyphicon-pencil btn btn-xs'></span></a> </h4> 
                       <?php endif ;?>
            </div>
         </div>
            
    </form>
    </div>
<br><br>
<?php  include "include/footer.php" ;?>
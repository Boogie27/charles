<?php
   require_once $_SERVER["DOCUMENT_ROOT"]."/boutique/connection.php";
   if(! login_user()){
    login_redirect();
   }
   include "include/header.php";
   $error = array();
?>
<?php
        $id = $row_user["id"];
        $old_psw = $row_user["password"];//form the connection page

        $old_password = ((isset($_POST["old_password"]) && !empty($_POST["old_password"])) ? mysqli_real_escape_string($connect,$_POST["old_password"]) : '');
        $password = ((isset($_POST["password"]) && !empty($_POST["password"])) ? mysqli_real_escape_string($connect,$_POST["password"]) : '');
        $confirm_password = ((isset($_POST["confirm_password"]) && !empty($_POST["confirm_password"])) ? mysqli_real_escape_string($connect,$_POST["confirm_password"]) : '');
        if(isset($_POST["change_password"])){
            $old_password = mysqli_real_escape_string($connect,$_POST["old_password"]);
            $password = mysqli_real_escape_string($connect,$_POST["password"]);
            $confirm_password = mysqli_real_escape_string($connect,$_POST["confirm_password"]);
            if(empty($old_password) || empty($password) || empty($confirm_password)){
                $error[] = "you must fill the password fields!";
            }else{
                if(strlen($old_password) < 6 ){
                    $error[] = "Old Password must be at least 6 characters long!";
                }else{
                    if(!password_verify($old_password,$old_psw)){
                        $error[] = "Password does not match please try again!";
                      }else{
                          if(strlen($password) < 6 ){
                            $error[] = "New Password must be at least 6 characters long!";
                          }else{
                              if($password != $confirm_password){
                                $error[] = "New password does not match confirm password";
                              }else{
                                  $pwd_hash = password_hash($password,PASSWORD_DEFAULT);
                                  $sql = "UPDATE users SET password = '$pwd_hash' WHERE id='$id'";
                                  mysqli_query($connect,$sql);
                                  $_SESSION["success"] = "Password has been changed successfuly";                                
                                  header("Location: index.php");
                              }
                          }
                      }
                    }
            
                  }  
                }
            
            
        
?>


    <div class='container'>
         <div class='password-form' id='password-form'>
             <form action='password_change.php' method='POST'>
             <h3 class='text-center'>Change Password</h3>
              <div>
             <?php //check for errors
                    if(!empty($error)){ ?>
                      <div class='error_text'><p><?= get_error($error); ?> </p></div>
                     <?php   } ?>
    
                </div>
                <hr>
             <div class='form-group'>
                     <label for='old_password'>Old Password :</label>
                     <input type='password' name='old_password' id='old_password' class='form-control' value='<?=$old_password ;?>'>
             </div>
             <div class='form-group'>
                     <label for='password'>New Password :</label>
                     <input type='password' name='password' id='password' class='form-control' value='<?=$password ;?>'>
             </div>
             <div class='form-group'>
                     <label for='confirm_password'>Confirm Password :</label>
                     <input type='password' name='confirm_password' id='confirm_password' class='form-control' value=''>
             </div>
             <div class='form-group'>
                     <a href='index.php' class='btn btn-default'>Cancle</a>
                     <input type='submit' name='change_password' id='change_password' class='btn btn-success' value='Change Password'>
                    </div>
                   <p class='text-right'><a href='../index.php' >Visit site</a></p>
           </form>
      </div>
    </div>

<?php  include "include/footer.php" ;?>
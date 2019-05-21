<?php
   require_once $_SERVER["DOCUMENT_ROOT"]."/boutique/connection.php";
   include "include/header.php";
   $error = array();
?>
<?php
        $email = ((isset($_POST["email"]) && !empty($_POST["email"])) ? mysqli_real_escape_string($connect,$_POST["email"]) : '');
        $password = ((isset($_POST["password"]) && !empty($_POST["password"])) ? mysqli_real_escape_string($connect,$_POST["password"]) : '');
        if(isset($_POST["login"])){
            $email = mysqli_real_escape_string($connect,$_POST["email"]);
            $password = mysqli_real_escape_string($connect,$_POST["password"]);
            if(empty($email) || empty($password)){
                $error[] = "you must fill the email and password fields!";
            }else{
                    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                        $error[] = "Wrong Email format!";
                    }else{
                        $sql = "SELECT * FROM users WHERE email ='$email'";
                        $result = mysqli_query($connect,$sql);
                        $row = mysqli_fetch_assoc($result);
                        if(mysqli_num_rows($result) == 0){
                            $error[] = " The Email <strong>".$email."</strong> has been deactivated, contact the admin!";
                        }else{
                            $sql_active = "SELECT * FROM users WHERE email ='$email' AND active = 0";
                            $result_active = mysqli_query($connect,$sql_active);
                            $row_active = mysqli_fetch_assoc($result_active);
                            if(mysqli_num_rows($result_active) == 0){
                                $error[] = " The Email <strong>".$email."</strong> is been deactivated!";
                            }else{
                              if(strlen($password) < 6 || strlen($password) > 12){
                                $error[] = "Password must be between 6 to 12 characters long";
                            }else{
                                if(password_verify($password,$row["password"])){
                                    $error[] = "Password does not match,Please try again!"; 
                                }else{
                                    $user_id = $row_active["id"];
                                    login($user_id);
                                }
                            }
                           
                        }
                    }
                }
            }
        }
        
?>

<style>
     body{
         background-image: url("/boutique/images/beauty-casual-curly-794064.jpg");
         background-repeat: no-repeat;
         background-size: 100vw 100vh;
         background-attachment: fixed;
     } 
</style>

    <div class='container'>
         <div class='login-form' id='login-form'>
             <form action='login.php' method='POST'>
             <h2 class='text-center'>Login</h2>
              <div>
             <?php //check for errors
                    if(!empty($error)){ ?>
                      <div class='error_text'><p><?= get_error($error); ?> </p></div>
                     <?php   } ?>
    
                </div>
                <hr>
             <div class='form-group'>
                     <label for='email'>Email :</label>
                     <input type='email' name='email' id='email' class='form-control' value=''>
             </div>
             <div class='form-group'>
                     <label for='password'>Password :</label>
                     <input type='password' name='password' id='password' class='form-control' value=''>
             </div>
             <div class='form-group'>
                     <input type='submit' name='login' id='login' class='btn btn-success' value='Login'>
             </div>
                   <p class='text-right'><a href='../index.php' >Visit site</a></p>
           </form>
      </div>
    </div>

<?php  include "include/footer.php" ;?>
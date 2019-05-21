
<?php
   include "connection.php";
   include "include/header.php";
   $error = array();
   $message = "";
?>
<?php

 
    if(!empty($cookie_id)){
        $cookie_id;
        $id = $_POST["id"];
        $sub_total = $_POST["sub_total"];
        $grand_total = $_POST["grand_total"];
        $tax= $_POST["tax"];
        $description= $_POST["description"];
        
        $full_name = ((isset($_POST["full_name"]) && !empty($_POST["full_name"]))?  $_POST["full_name"] : "");
   if(isset($_POST["payment"])){
    $full_name = ((isset($_POST["full_name"]) && !empty($_POST["full_name"]))?  $_POST["full_name"] : "");
    $email = $_POST["email"];
    $street = $_POST["street"];
    $street_2 = $_POST["street_2"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zip_code = $_POST["zip_code"];
    $country = $_POST["country"];
    //parser files
    $id = $_POST["id"];
    $sub_total = $_POST["sub_total"];
    $grand_total = $_POST["grand_total"];
    $tax = $_POST["tax"];
    $description = $_POST["description"];
   
   if(empty($full_name) || empty($email) || empty($street) || empty($street_2) || empty($city) || empty($state) || empty($zip_code) || empty($country) ){
    $error[]  = "all fields are required!";
   }else{
       if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error[]  =  "the email ".$email." is not a valid Email";
       }else{
          
       }
   }
        if(!empty($error)){
            $message =   get_error($error);
            }else{
            $charge_id = 6;
            $update = $connect->query("UPDATE cart SET paid = 1 WHERE id='$cookie_id'");
            $sql = "INSERT INTO transaction (charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,sub_total,tax,grand_total,description,transaction_type) 
            VALUES('$charge_id','$cookie_id','$full_name','$email','$street','$street_2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description','test')";
            mysqli_query($connect,$sql);
            setcookie(CART_COOKIE,"",1,"/");
            //update boutique sizes
            $sql = $connect->query("SELECT * FROM cart WHERE id ='$cookie_id'");
            $result = mysqli_fetch_assoc($sql);
            $item_cart = $result["item"];
            $strip_item = stripslashes($item_cart);
            $item_decode = json_decode($strip_item, true);
            foreach($item_decode as $keys){
                  $item_id = $keys["item_id"];
                  $sql_bout = $connect->query("SELECT * FROM boutique WHERE id = '$item_id'");
                  $result_bout = mysqli_fetch_assoc($sql_bout);
                  $size = $result_bout["sizes"];
                  $sizes = sizes_array($size);
                  $new_sizes = array();
                  foreach($sizes as $item){
                       if($item["sizes"] == $keys["item_sizes"] ){
                         $new_quantity = $item["quantity"] -  $keys["item_quantity"]; 
                         $new_sizes[] = array("sizes" => $item["sizes"], "quantity" => $new_quantity);
                     }else{
                         $new_sizes[] = array("sizes" => $item["sizes"], "quantity" => $item["quantity"]);
                     }
                  }
                 $sizes_string = sizetoString($new_sizes);
                 $update = $connect->query("UPDATE boutique SET sizes = '$sizes_string' WHERE id = '$item_id'");
            }
            $_SESSION["success"] = "cart has been ordered successfully!";
            header("Location: index.php"); 
        } 

    } 
      
    }else{
        header("Location: cart.php");
    }
    if(!isset($_POST["id"])){
        header("Location: cart.php");
    }
    
?>
           <div class='container'>
                <div class='row'>
                <form action='payment.php' method='POST'>
                   <div class='error'><?= $message;?></diV>
                    <div id='step1'>
                          <div class='form-group col-md-6'>
                              <label for='name'>Name:</label>
                              <input type='text' name='full_name' id='name' class='form-control' value='<?=$full_name ;?>'>
                          </div>
                          <div class='form-group col-md-6'>
                              <label for='email'>Email:</label>
                              <input type='email' name='email' id='email' class='form-control'>
                          </div>
                          <div class='form-group col-md-6'>
                              <label for='street'>Street 1:</label>
                              <input type='text' name='street' id='street_1' class='form-control'>
                          </div>
                          <div class='form-group col-md-6'>
                              <label for='street_2'>Street 2:</label>
                              <input type='text' name='street_2' id='street_2' class='form-control'>
                          </div>
                          <div class='form-group col-md-6'>
                              <label for='city'>City:</label>
                              <input type='text' name='city' id='city' class='form-control'>
                          </div>
                          <div class='form-group col-md-6'>
                              <label for='state'>State:</label>
                              <select name='state' id='state' class='form-control'>
                                 <option value=''></option>
                                 <?php $sql = $connect->query("SELECT * FROM state");
                                    while($row = mysqli_fetch_assoc($sql)):
                                 ?>
                                 <option value='<?=$row["state"] ;?>'><?=$row["state"] ;?></option>
                                    <?php endwhile; ?>
                             </select>
                          </div>
                          <div class='form-group col-md-6'>
                              <label for='zip_code'>Zip Code:</label>
                              <input type='text' name='zip_code' id='zip_code' class='form-control'>
                          </div>
                          <div class='form-group col-md-6'>
                              <label for='country'>Country:</label>
                             <select name='country' id='country' class='form-control'>
                                 <option value=''></option>
                                 <?php $sql = $connect->query("SELECT * FROM countries");
                                    while($row = mysqli_fetch_assoc($sql)):
                                 ?>
                                 <option value='<?=$row["country"] ;?>'><?=$row["country"] ;?></option>
                                    <?php endwhile; ?>
                             </select>
                          </div>
                        </div>
                     <div id='step2'>
                         <div class='col-md-6'></div>
                    </div>
                        <input type='hidden' name='id' value='<?=$id ;?>'>
                        <input type='hidden' name='sub_total' value='<?=$sub_total;?>'>
                        <input type='hidden' name='grand_total' value='<?=$grand_total ;?>'>
                        <input type='hidden' name='tax' value='<?=$tax ;?>'>
                        <input type='hidden' name='description' value='<?=$description ;?>'>
                    <div class='form-group pull-right'>
                       <a href='cart.php' class='btn btn-default'>Cancle</a>
                       <input type='submit' name='payment' class='btn btn-primary' value='Next >>'>
                     </div>
                 </form>
                </div>
           </div>

 <!--FOOTER SECION -->                            
<?php
 include "include/footer.php"; 
 ?>
<?php
 include "connection.php";
 include "include/header.php";
 include "include/navigation.php";
 include "include/category_header_image.php"; 
 include "include/left_bar.php";
 $message = '';
 ?>


<?php  

     if(isset($_GET["detail"])){
         $detail_id = (int)$_GET["detail"];
         $sql_detail = "SELECT * FROM boutique WHERE  feautured = 0 AND deleted= 0 AND id='$detail_id'";
         $result_detail = mysqli_query($connect,$sql_detail);
         $row_detail = mysqli_fetch_assoc($result_detail);
         $brand_detail = $row_detail["brand"];
         $sql = "SELECT * FROM brand WHERE id='$brand_detail'";
         $result_brand = mysqli_query($connect,$sql);
         $brand = mysqli_fetch_assoc($result_brand);
         
     if(isset($_GET["id"])){
       $id = (int)$_GET["id"];
     }

      
    if(isset($_POST["add_Item"])){
           $item_array = array(
               "item_id" => $_POST["hidden_id"],
               "item_title" => $_POST["hidden_title"],
               "item_price" => $_POST["hidden_price"],
               "item_sizes" => $_POST["sizes"],
               "item_quantity" => $_POST["quantity"],
           );
           $item_data[] = $item_array;
        //  var_dump($item_data);
        $size_array = $row_detail["sizes"];
        $sizes = explode(",",$size_array);
        foreach($sizes as $size_item){
        $item = explode(":",$size_item);
        $size =  $item[0];
        if($size ==  $_POST["sizes"]){
            $available =  $item[1];
        }
       }
          if(empty( $_POST["sizes"]) || empty($_POST["quantity"])){
                 $message = "<p class='text-center text-danger bg-danger'>Both the size and Quantity fields are required!</p>";
          }else{
              if($_POST["quantity"] > $available){
                $message = "<p class='text-center text-danger bg-danger'>There are Only ".$available." available!</p>";
              }else{
                  if(!empty($cookie_id)){

                      $sql_cookie = "SELECT * FROM cart WHERE id = '$cookie_id'";
                       $result_cookie = mysqli_query($connect,$sql_cookie);
                       $row = mysqli_fetch_assoc($result_cookie);
                       $item_row = $row["item"];
                       $item_strip = stripslashes($item_row);
                       $item_decode = json_decode($item_strip,true);
                       $item_match = 0;
                       foreach($item_decode as $item){
                          $item_id = $item["item_id"];
                           $sql = "SELECT * FROM boutique WHERE id = '$item_id'";
                           $result = mysqli_query($connect,$sql);
                           $row = mysqli_fetch_assoc($result);
                           $size_array = $row["sizes"];
                           $sizes = explode(",",$size_array);
                           foreach($sizes as $size_item){
                           $item_size = explode(":",$size_item);
                           $size =  $item_size[0];
                           if($size == $item["item_sizes"]){
                              $available =  $item_size[1];
                           }
                          }
                        
                       if($item["item_id"] == $item_array["item_id"] && $item["item_sizes"] == $item_array["item_sizes"]){
                            $item["item_quantity"] = $item["item_quantity"] + $item_array["item_quantity"] ;
                            if( $item["item_quantity"] > $available){
                                $item["item_quantity"] = $available;
                            }
                            $item_match = 1;
                        }
                        $new_item[] = $item;
                       }
                       if($item_match != 1){
                        $new_item = array_merge($item_data,$item_decode);
                       }

                       $item_json = json_encode($new_item);
                       $date = date("Y-m-d H:i:s", strtotime("+30 days"));
                       $sql = "UPDATE cart SET item='$item_json' , expire_date='$date' WHERE id='$cookie_id'";
                       mysqli_query($connect,$sql);
                       setcookie(CART_COOKIE,$cookie_id,EXPIRE_DATE,"/");
                       header("Location:  category.php?id=$id&success=1"); 

                  }else{
                    $item_encode = json_encode($item_data);
                    $date = date("Y-m-d H:i:s", strtotime("+30 days"));
                    $sql = "INSERT INTO cart (item,expire_date) VALUES ('$item_encode','$date')";
                    mysqli_query($connect,$sql);
                    $cart_id = $connect->insert_id;
                    setcookie(CART_COOKIE,$cart_id,EXPIRE_DATE,"/");
                    header("Location: category.php?id=$id&success=1");
                  }
              }
          }
          
    }   
      

         ?>

<!--MIDDLE BAR-->
<!--Item Detail-->
<div class='col-md-8'>
                   <div class='row'>
                  
                          <h2 class='text-center'><?=$row_detail["title"] ;?></h2><hr>
                               <?=$message ;?>
                                   <div class='col-md-6'>
                                   <form action='category.php?id=<?=$id ?>&detail=<?=$detail_id ?>' method='POST'>
                                        <img src='<?= $row_detail['image'] ?>' alt='<?= $row_detail['title'] ?>' class='image-thumb'>
                                    </div>
                                    <div class='col-md-6'>
                                        <h4 class=''><b>Details</b></h4>
                                        <p><?= $row_detail['description'] ?></p><hr>
                                        <p><b>Price:</b> &#8358; <?= $row_detail['price'] ?></p>
                                        <p><b>Brand:</b> <?=$brand["brand"];?></p>
                                           <div class='form-group'>
                                                <label for='quantity'>Quantity: </label>
                                                <input type='number' name='quantity'  class='form-control' id='quantity' value='' min='0'>
                                                <input type='hidden' name='hidden_id' value='<?=$row_detail["id"] ;?>'>
                                                <input type='hidden' name='hidden_title' value='<?=$row_detail["title"] ;?>'>
                                                <input type='hidden' name='hidden_price' value='<?=$row_detail["price"] ;?>'>
                                            </div>
                                            <p><b>Available:</b> 3</p>
                                            <div class='form-group'>
                                                <label for='sizes'>Sizes: </label>
                                                <select name='sizes' id='sizes' class='form-control'>
                                                    <option value=''></option>
                                                    <?php 
                                                        $size_array = $row_detail["sizes"];
                                                        $sizes = explode(",",$size_array);
                                                        foreach($sizes as $size_item){
                                                        $item = explode(":",$size_item);
                                                        $size =  $item[0];
                                                        $available =  $item[1];
                                                        if($available > 0){
                                                            echo " <option value=".$size.">".$size." ( ".$available." Available )</option>";
                                                        }
                                                     }
                                                        ?>
                                                 </select>
                                            </div>
                                            <div class='form-group'>
                                                <a href='category.php?id=<?=$id;?>' class='btn btn-default'>Cancle</a>
                                                <button type='submit'class='btn btn-warning' name='add_Item'><span class='glyphicon glyphicon-shopping-cart'></span>Add to cart</button>
                                            </div>
                                    </div> 
                            </form>       
                    </div>
               </div>









     <?php  }else{ 
    if(isset($_GET["id"])){
      $id = (int)$_GET["id"];
    }
    if(!isset($_GET["id"]) || $_GET["id"] == ""){
        header("Location: index.php");
    }

    if(isset($_GET["success"])){
        $message = "item has been added to your cart";
       } 
?>
     
<!--MIDDLE BAR-->
           <div class='col-md-8'>
                <div class='row'><?php  $sql = "SELECT * FROM boutique WHERE feautured = 0 AND deleted= 0 AND categories = '$id'";
                           $result = mysqli_query($connect,$sql);
                           $sql_cat = "SELECT * FROM category WHERE id='$id'";
                           $result_cat = mysqli_query($connect,$sql_cat);
                           $row_cat = mysqli_fetch_assoc($result_cat);
                            $child = $row_cat["category"];
                            $parent_id = $row_cat["parent"];
                            $sql_parent = "SELECT * FROM category WHERE id='$parent_id'";
                            $result_parent = mysqli_query($connect,$sql_parent);
                            $row_parent = mysqli_fetch_assoc($result_parent);
                            $parent =  $row_parent["category"];
                            $category =  $parent." - ".$child; ?>
                    <h2 class='text-center'><?=$category ;?></h2><hr>
                        <p class='text-success text-center bg-success'><?=$message ;?></p>
                         <?php  while($row = mysqli_fetch_assoc($result)) : ?><!--STARTING OF WHILE LOOP-->
                                   <div class='col-md-3'>
                                       <div class='thumb'>
                                        <h4 class=''><?= $row['title'] ?></h4>
                                        <img src='<?= $row['image'] ?>' alt='<?= $row['title'] ?>' class='image-thumb'>
                                         <p class='list-price text-danger text-center'>List price: <s> &#8358;<?= $row['list_price'] ?></s></p>
                                        <p class='price text-center'>Our price: &#8358;<?= $row['price'] ?></p>
                                      <div class='text-center'><a href='category.php?id=<?=$id ;?>&detail=<?=$row['id'] ;?>'  class='btn btn-xs btn-info'>Detail</a></div>
                                         <br><br>
                                    </div> 
                                 </div>         
                  <?php endwhile;?><!--END OF THE WHILE LOOP-->
                </div>
            </div>
            

       
       

       

        
<?php } include "include/right_bar.php"; ?><br><br>
         <?php include "include/footer.php" ?>

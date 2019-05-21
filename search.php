<?php
 include "connection.php";
 include "include/header.php";
 include "include/navigation.php";
 ((isset($_GET["detail"]))? include "include/category_header_image.php" : include "include/header_image.php");
   include "include/left_bar.php";
   $error = array();
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
         
          
        if(isset($_POST["add_to_cart"])){
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
            $available =  $item[1];
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
                           header("Location: index.php?success");

                      }else{
                        $item_encode = json_encode($item_data);
                        $date = date("Y-m-d H:i:s", strtotime("+30 days"));
                        $sql = "INSERT INTO cart (item,expire_date) VALUES ('$item_encode','$date')";
                        mysqli_query($connect,$sql);
                        $cart_id = $connect->insert_id;
                        setcookie(CART_COOKIE,$cart_id,EXPIRE_DATE,"/");
                        header("Location: index.php?success");
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
                                   <form action='index.php?detail=<?=$row_detail["id"] ;?>' method='POST'>
                                        <img src='<?= $row_detail['image'] ?>' alt='<?= $row_detail['title'] ?>' class='image-thumb'>
                                    </div>
                                    <div class='col-md-6'>
                                        <h4 class=''><b>Details</b></h4>
                                        <p><?= $row_detail['description'] ?></p><hr>
                                        <p><b>Price:</b> <?= money($row_detail['price'],2); ?></p>
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
                                                <a href='index.php' class='btn btn-default'>Cancle</a>
                                                <button type='submit'class='btn btn-warning' name='add_to_cart'><span class='glyphicon glyphicon-shopping-cart'></span>Add to cart</button>
                                            </div>
                                    </div> 
                            </form>       
                    </div>
               </div>



                 <?php  
                
                }else{
                   if(isset($_GET["success"])){
                    $message = "item has been added to your cart";
                   } 

                  $category_id = ((isset($_POST["id"]))? mysqli_real_escape_string($connect,$_POST["id"]) : "" );
                  $min_price = ((isset($_POST["min_price"]))? mysqli_real_escape_string($connect,$_POST["min_price"]) : "");
                  $max_price = ((isset($_POST["max_price"]))? mysqli_real_escape_string($connect,$_POST["max_price"]) : "");
                  $brand = ((isset($_POST["brand_search"]))? mysqli_real_escape_string($connect,$_POST["brand_search"]) : "");
                  $price_sort = ((isset($_POST["price_sort"]))? mysqli_real_escape_string($connect,$_POST["price_sort"]) : "");
    
                  $sql = "SELECT * FROM boutique";
                  if(empty($category_id)){
                    $sql .= " WHERE deleted = 0";
                  }else{
                    $sql .= " WHERE categories = '{$category_id}' AND deleted = 0";
                  }

                  if(!empty($min_price)){
                    $sql .= " AND price >= '{$min_price}' ";
                  }
                  if(!empty($max_price)){
                    $sql .= " AND price <= '{$max_price}' ";
                  }
                  if(!empty($brand)){
                    $sql .= " AND brand = '{$brand}'"; 
                  }
                 
                  if($price_sort == "low"){
                    $sql .= " ORDER BY price";
                  }
                  if($price_sort == "high"){
                    $sql .= " ORDER BY price DESC";
                  }
                    ?>
     
<!--MIDDLE BAR-->
           <div class='col-md-8'>
           <div class='row'>
                <?php if(!empty($category_id)):
                    $sql_select = $connect->query("SELECT * FROM category WHERE id = '$category_id'");
                    $sql_row = mysqli_fetch_assoc($sql_select);
                    $child = $sql_row["category"];
                    $parent_id = $sql_row["parent"];
                    $sql_parent = $connect->query("SELECT * FROM category WHERE id = '$parent_id'");
                    $row_sql = mysqli_fetch_assoc($sql_parent);
                     $parent = $row_sql["category"];
                    ?>
                         <h2 class='text-center'><?=$parent."-".$child ;?></h2>
                <?php else: ?>
                <h2 class='text-center'>FEAUTURE PRODUCTS</h2>
                <?php endif ;?>
                  <hr>
                         <p class='text-center text-success bg-success'><?=$message ;?></p>
                    <?php  
                           $result = mysqli_query($connect,$sql);
                           while($row = mysqli_fetch_assoc($result)) : ?><!--STARTING OF WHILE LOOP-->
                                   <div class='col-md-3'>
                                     <div class='thumb'>
                                        <h4 class=''><?= $row['title'] ?></h4>
                                        <img src='<?= $row['image'] ?>' alt='<?= $row['title'] ?>' class='image-thumb'>
                                         <p class='list-price text-danger text-center'>List price: <s> &#8358;<?= $row['list_price'] ?></s></p>
                                        <p class='price text-center'>Our price: &#8358;<?= $row['price'] ?></p>
                                       <div class='text-center'> <a href='index.php?detail=<?=$row['id'] ;?>'  class=' btn btn-xs btn-info'>Detail</a></div>
                                         <br><br>
                                    </div> 
                                    </div>         
                  <?php endwhile;?><!--END OF THE WHILE LOOP-->
                </div>
            </div>
            
    
       
       

       
   
        
    <?php }   include "include/right_bar.php"; ?><br><br>
    <?php  include "include/footer.php" ?>

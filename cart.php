<?php
 include "connection.php";
 include "include/header.php";
 include "include/navigation.php";
 include "include/category_header_image.php"; 
 ?>

<?php
  if(!empty($cookie_id)){
      $sql_select = "SELECT * FROM cart WHERE id = '$cookie_id'";
      $result_select = mysqli_query($connect,$sql_select);
      $row_cart = mysqli_fetch_assoc($result_select);
      $item_get =  $row_cart["item"];
      $get_strip = stripslashes($item_get);
      $get_decode = json_decode($get_strip,true);
      $item_array = array();
      if(isset($_GET["remove"])){
           $remove_id = (int)$_GET["remove"];
           $sizes = (int)$_GET["sizes"];
           foreach($get_decode as $items){
                  if($remove_id == $items["item_id"] && $sizes == $items["item_sizes"]){
                        $items["item_quantity"] = $items["item_quantity"] - 1;
                  }
                  if($items["item_quantity"] > 0){
                    $item_array[] = $items;
                  }else{
                      $sqldelete = "UPDATE cart SET item = '' WHERE id = '$cookie_id'";
                      mysqli_query($connect,$sqldelete);
                      header("Location: cart.php");
                  }
           }
      }
   
      if(isset($_GET["add"])){
        $add_id = (int)$_GET["add"];
        $sizes = (int)$_GET["sizes"];
        foreach($get_decode as $items){
               if($add_id == $items["item_id"] && $sizes == $items["item_sizes"]){
                  $items["item_quantity"] = $items["item_quantity"] + 1;
               }
                 $item_array[] = $items;
        }
   }

   if(!empty($item_array)){
       $get_encode = json_encode($item_array);
       $date = date("Y-m-d H:i:s", strtotime("+30 days"));
       $sql = "UPDATE cart SET item = '$get_encode' , expire_date = '$date' WHERE id = '$cookie_id'";
       mysqli_query($connect,$sql);
       header("Location: cart.php");
   }
   
      
                if(empty($item_get) || isset($_POST["delete_cart"])){
                $sql = "DELETE FROM cart WHERE id = '$cookie_id'";
                mysqli_query($connect,$sql);
                setcookie(CART_COOKIE,"",1,"/");
                header("Location: cart.php");       
            } 
        
 }
?>


                <div class='container cart'>
                     <h2 class='text-center'>My Shopping Cart</h2> 
                     <?php if(!empty($cookie_id)): ?>
                     <form action='cart.php' method='POST'> 
                         <?php if(isset($_GET["delete_cart"])): ?>  
                         <div class='form-group pull-right' id='back-button'> 
                              <a href='cart.php' class='btn btn-default'>Cancle</a> 
                              <input type='submit' name='delete_cart'  class='btn btn-default btn-danger' value='Click to Delete Cart'>
                         </div>                                               
                         <?php else: ?>
                         <a href='cart.php?delete_cart=1' class='btn btn-warning pull-right' id='back-button'>Delete cart</a>   
                         <?php endif ;?>
                    </form>
                    <?php endif ;?>
                    </div><hr>
                        <?php if(empty($cookie_id)):?>
                               <p class='text-center text-danger bg-danger'>There are no Item in your Cart!</p>
                          <?php else:?>
                          <div class='container cart'>
                             <div class='row'>
                             <?php 
                             $sql = "SELECT * FROM cart WHERE id = '$cookie_id'";
                             $result = mysqli_query($connect,$sql);
                             $row = mysqli_fetch_assoc($result);
                             $item_cart = $row["item"];
                             $item_strip = stripslashes($item_cart);
                             $item_decode = json_decode($item_strip,true);
                             $total = 0; $number = 0; $total_items = 0; $total_price = 0; $tax = 0; $grand_total = 0;
                              foreach($item_decode as $item){
                                   $id_cart = $item["item_id"];
                                   $sql = "SELECT * FROM boutique WHERE id ='$id_cart'";
                                   $result = mysqli_query($connect,$sql);
                                   $row_cat = mysqli_fetch_assoc($result);
                                   $size_array = explode(",",$row_cat["sizes"]);
                                   $number++;                                   
                                   foreach($size_array as $size_item){//to get the sizes and quantity
                                       $size_data = explode(":",$size_item);
                                       $size = $size_data[0];
                                       $total = $item["item_quantity"] * $row_cat["price"];
                                       if($size == $item["item_sizes"]){
                                            $available = $size_data[1];
                                       }
                                   } ?>
                                   
                                      <div class='col-md-6 my_cart'>
                                      <div class='number'><p><?=$number ;?></p></div><br>
                                          <div class='col-md-6'>
                                               <img src='<?=$row_cat["image"] ;?>' class=''>
                                          </div><br>
                                          <div class='col-md-6'>
                                             <p><b>Name:</b> <?=$row_cat["title"] ;?></p>
                                             <p><b>Size:</b> <?=$item["item_sizes"] ;?></p>
                                             <p><b>Quantity Ordered:</b> <?=$item["item_quantity"] ;?> <?=(($item["item_quantity"] >= $available)? "<span class='text-danger'>max</span>" : "" );?></p>
                                             <a href='cart.php?remove=<?=$item["item_id"] ;?>&sizes=<?=$item["item_sizes"] ;?>' class='btn btn-default btn-xs button'><span class='glyphicon glyphicon-minus text-danger'></span></a>    
                                             <?php if($item["item_quantity"] < $available): ?>
                                              <a href='cart.php?add=<?=$item["item_id"] ;?>&sizes=<?=$item["item_sizes"] ;?>' class='btn btn-default btn-xs button'><span class='glyphicon glyphicon-plus text-success'></span></a>    
                                             <?php endif ;?>
                                              <p><b>Price:</b> <?=money($row_cat["price"]) ;?></p>
                                             <p class='text-success bg-success'><b>Total Price:</b> <?=money($total) ;?></p>
                                          </div>
                                      </div>

                           <?php 
                        $total_items += $item["item_quantity"] ;
                        $total_price += ($row_cat["price"] * $item["item_quantity"]);
                        $tax = TAXRATE * $total_price;
                        $grand_total = $tax + $total_price;
                          }
                             
                             ?>
                             </div>
                             </div>
                      <div class='container'>
                             <table class='table table-condensed table-stripped table-bordered total_table'>
                                 <legend class='text-center bg-warning'>Total</legend>
                                 <thead><th>Total items</th><th>Total price</th><th>Tax</th><th>Grand Total</th></thead>
                                 <tbody>
                                     <tr>
                                         <td><?=$total_items ;?></td>
                                         <td><?=money($total_price) ;?></td>
                                         <td><?=money($tax) ;?></td>
                                         <td class='total_price'><?=money($grand_total) ;?></td> 
                                     </tr>
                                 </tbody>
                             </table>
                            <form class='pull-right'action='payment.php' method='POST'>
                                    <input type='hidden' name='id' value='<?=$item["item_id"] ;?>'>
                                    <input type='hidden' name='sub_total' value='<?=$total_price;?>'>
                                    <input type='hidden' name='grand_total' value='<?=$grand_total;?>'>
                                    <input type='hidden' name='tax' value='<?=$tax ;?>'>
                                    <input type='hidden' name='description' value=' <?= $total_items .' '. (($total_items > 1)? "items" :"item" ) ;?>'>
                                    <a href='index.php' class='btn btn-default'>cancle</a>
                                    <button type='submit' name='check_out' class='btn btn-info'>Check Out >></button></td>
                                </form>
                        </div>
                            <?php endif;?>
                
 
<br><br>
         <?php include "include/footer.php" ?>
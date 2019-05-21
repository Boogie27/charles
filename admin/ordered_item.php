<?php
   include "../connection.php";
   if(!login_user()){
    header("Location: login.php");
   }
   include "include/header.php";
   include "include/navigation.php";
?>
<?php
    
               if(isset($_GET["detail"])){
                $order_id = mysqli_real_escape_string($connect,$_GET["detail"]);
                if(isset($_GET["cancle"]) && $_GET["cancle"] == 1){
                    echo $cart_id = mysqli_real_escape_string($connect, $_GET["cancle"]);
                        $sql_update = $connect->query("UPDATE cart SET ordered = 0 WHERE id = '$order_id'"); 
                        $_SESSION["success"] = "Shipped Item has been cancled";
                        header("Location: index.php");
                  }
              
                   ?>
             
                <div class='container'>
                    <h3 class='text-center'>Items Shipped</h3>
                    <div class='row'>
                      <?php
                           $sql_cart = $connect->query("SELECT * FROM cart WHERE id = '$order_id'");
                           $row_cart = mysqli_fetch_assoc($sql_cart);
                           $cart_item = $row_cart["item"];
                           $strip = stripslashes($cart_item);
                           $item_decode = json_decode($strip, true);
                       // var_dump($item_decode);
                           foreach($item_decode as $item): 
                               $id = $item["item_id"];
                               $sql_bout = $connect->query("SELECT * FROM boutique WHERE id = '$id'");
                               $row_bout = mysqli_fetch_assoc($sql_bout);
                               $actegories = $row_bout["categories"];
                               $sql_category = $connect->query("SELECT * FROM category WHERE id = '$actegories'");
                               $row_cat = mysqli_fetch_assoc($sql_category);
                               $child = $row_cat["category"];
                               $parent_id = $row_cat["parent"];
                               $sql_parent = $connect->query("SELECT * FROM category WHERE id = '$parent_id'");
                               $row_parent = mysqli_fetch_assoc($sql_parent);
                               $parent = $row_parent["category"];
                               $category = $parent."-".$child;
                           ?>
                         <div class='col-md-6'>
                             <div class='col-md-6'><img src = '<?= $row_bout["image"];?>'></div>
                             <div class='col-md-6'>
                                <h5>Name: <strong><?=$row_bout["title"] ;?></strong></h5>
                                <h5>Category: <strong><?=$category ;?></strong></h5>
                                <h5>Size: <strong><?=$item["item_sizes"] ;?></strong></h5>
                                <h5>Quantity: <strong><?=$item["item_quantity"] ;?></strong></h5>
                                <?php  if($item["item_quantity"] > 1): ?>
                                     <h5>Price: <strong><?=money($row_bout["price"] * $item["item_quantity"]) ;?></strong></h5>        
                           <?php else: ?>
                                     <h5>Price: <strong><?=money($row_bout["price"]) ;?></strong></h5>
                                <?php endif; ?>
                             </div>
                         </div><br>
                         <?php endforeach ;?>
                    <div>
                </div>
             
             <div class='container'>
                 <table class='table table-condensed table-bordered table_striped'>
                     <thead><th>Sub Total</th><th>Tax</th><th>Grand Total</th><th>Order Date</th></thead>
                     <tbody>
                     <?php
                         $address = array();
                         $sql_transaction = $connect->query("SELECT * FROM transaction WHERE cart_id = '$order_id'");
                           while($row_t = mysqli_fetch_assoc($sql_transaction)):
                             $address = array(
                                 "street" => $row_t["street"],
                                 "street2" => $row_t["street2"],
                                 "city" => $row_t["city"],
                                 "state" => $row_t["state"],
                                 "country" => $row_t["country"],
                             );
                           ?>
                         <tr>
                             <td><?=money($row_t["sub_total"]) ;?></td>
                             <td><?=money($row_t["tax"]) ;?></td>
                             <td><?=money($row_t["tax"]) ;?></td>
                             <td><?=fancy_date($row_t["transaction_date"]);?></td>
                         </tr>
                         <?php endwhile ;?>
                     </tbody>
                 </table>
                   <div class=''>
                       <h3 class='text-center'>Shipping Address</h3>
                       <p><strong>Address:</strong> <?=$address["street"];?></p>
                       <p><strong>Address2:</strong> <?=$address["street2"];?></p>
                       <p><strong>City:</strong> <?=$address["city"];?></p>
                       <p><strong>State:</strong> <?=$address["state"];?></p>
                       <p><strong>Country:</strong> <?=$address["country"];?></p>
                         <div class='pull-right'>
                           <a href='ordered_item.php' class='btn btn-default'>Cancle</a>
                           <a href='ordered_item.php?detail=<?=$order_id ;?>&cancle=1' class='btn btn-primary'>Cancle Shippment</a>
                         </div>
                   </div>
             </div>
                
                 
                
             <?php  }else{ ?>
               
                    <div class='container'>
                    <br>
                        <h3 class='text-center'>Shipped Order</h3>
                        <?php  $sql_cart = $connect->query("SELECT * FROM cart WHERE paid = 1 AND ordered = 1");
                                if(mysqli_num_rows($sql_cart) > 0):  ?>
                        <table class='table table-condensed table-bordered table-striped'>
                             <thead><th></th><th>Name</th><th>Description</th><th>Total</th><th>Date</th></thead>
                             <tbody>
                               <?php 
                               
                                        while( $row_cart = mysqli_fetch_assoc($sql_cart)):
                                            $cart_id = $row_cart["id"]; 
                                            $sql_trans = $connect->query("SELECT * FROM transaction WHERE cart_id = '$cart_id'");
                                            while( $row_transc = mysqli_fetch_assoc($sql_trans)):?>
                                            <tr>
                                                <td><a href='ordered_item.php?detail=<?=$row_transc["cart_id"];?>' class='btn btn-xs btn-info'>Detail</a></td>
                                                <td><?=$row_transc["full_name"] ; ?></td>
                                                <td><?=$row_transc["description"] ;?></td>
                                                <td><?=money($row_transc["grand_total"]) ;?></td>
                                                <td><?=fancy_date($row_transc["grand_total"]) ;?></td>
                                            </tr>
                                        <?php endwhile; ?> 
                                    <?php endwhile; ?>
                                <?php else: ?>
                                     <p class='text-center text-danger bg-danger'>There are no Shipped items Yet.</p>
                                 <?php endif; ?>          
                             </tbody>
                        </table>
                    </div> 




<?php } include "include/footer.php" ;?>
<?php
   include "../connection.php";
   if(!login_user()){
    header("Location: login.php");
   }
   include "include/header.php";
   include "include/navigation.php";
?>

<?php

     if(isset($_GET["id"])){
         $order_id = mysqli_real_escape_string($connect,$_GET["id"]);
     }
     if(isset($_GET["complete"]) && $_GET["complete"] == 1){
        echo $cart_id = mysqli_real_escape_string($connect, $_GET["cancle"]);
            $connect->query("UPDATE cart SET ordered = 1 WHERE id = '$order_id'"); 
            $_SESSION["success"] = "Item has been shipped";
            header("Location: index.php");
      }
  
    
?>

   <div class='container'>
       <h3 class='text-center'>Items Ordered</h3>
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
    <table class='table table-condensed table-bordered table_stripped'>
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
              <a href='index.php' class='btn btn-default'>Cancle</a>
              <a href='order.php?id=<?=$order_id ;?>&complete=1' class='btn btn-primary'>Complete</a>
            </div>
      </div>
</div>
   

<?php include "include/footer.php" ;?>
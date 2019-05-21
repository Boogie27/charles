<div class='widget' id='widget'>
    <h3 class='text-center'>Shopping Cart</h3>
    <?php 
    
    if(empty($cookie_id)):?>
        <div class='bg-danger'><p class='text-danger'>There are no item in your Cart</p></div>
    <?php else: ?>
    <?php $sql = $connect->query("SELECT * FROM cart WHERE id = '$cookie_id'");
           $Result = mysqli_fetch_assoc($sql);
           $item = $Result["item"];
           $strip = stripslashes($item);
           $item_decode = json_decode($strip, true); ?>
           <table class='table table-condensed table-stripped table-bordered'>
              <tbody>
        <?php  
         $sub_total = 0;
         foreach($item_decode as $keys):
                    $item_id = $keys["item_id"]; 
                    $sql_bout = $connect->query("SELECT * FROM boutique WHERE id = '$item_id'");
                    $result_bout = mysqli_fetch_assoc($sql_bout);
                    $sub_total += $result_bout["price"] * $keys["item_quantity"]; ?>
                     <tr>
                        <td><?=$keys["item_quantity"] ;?></td>
                        <td><?=substr($result_bout["title"],0,15 );?></td>
                        <td><?= money($result_bout["price"]);?></td>
                     <tr>
           <?php endforeach;?>
                     <tr>
                         <td></td>
                         <td><strong>Sub Total</strong></td>
                        <td><strong><?=money($sub_total, 2) ;?></strong></td>
                     </tr>
                </tbody>
            </table>
            <div class='text-right'>
                <a href='cart.php' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-shopping-cart'></span>view cart</a>
            </div>
    <?php endif ;?>
</div>
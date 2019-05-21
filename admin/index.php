<?php
   include "../connection.php";
   if(!login_user()){
    header("Location: login.php");
   }
   include "include/header.php";
   include "include/navigation.php";
?>


               
                    <div class='container'>
                    <br>
                        <h3 class='text-center'>Shipping Order</h3>
                        <table class='table table-condensed table-bordered table-stripped'>
                             <thead><th></th><th>Name</th><th>Description</th><th>Total</th><th>Date</th></thead>
                             <tbody>
                               <?php 
                                $sql_cart = $connect->query("SELECT * FROM cart WHERE paid = 1 AND ordered = 0");
                                while( $row_cart = mysqli_fetch_assoc($sql_cart)):
                                       $cart_id = $row_cart["id"]; 
                                    $sql_trans = $connect->query("SELECT * FROM transaction WHERE cart_id = '$cart_id'");
                                    while( $row_transc = mysqli_fetch_assoc($sql_trans)): ?>
                                       <tr>
                                           <td><a href='order.php?id=<?=$row_transc["cart_id"];?>' class='btn btn-xs btn-info'>Detail</a></td>
                                           <td><?=$row_transc["full_name"] ;?></td>
                                           <td><?=$row_transc["description"] ;?></td>
                                           <td><?=money($row_transc["grand_total"]) ;?></td>
                                           <td><?=fancy_date($row_transc["grand_total"]) ;?></td>
                                       </tr>
                                 <?php endwhile; ?> 
                                 <?php endwhile; ?>          
                             </tbody>
                        </table>
                    </div> 




<?php include "include/footer.php" ;?>
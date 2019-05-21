<?php
   include "../connection.php";
   if(!login_user()){
    login_redirect(); 
   }
   include "include/header.php";
   include "include/navigation.php";
   $error = array();
?>


<?php
//retore products----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_GET["restore"])){
     $restored_id = (int)$_GET["restore"];
     $restored_id = mysqli_real_escape_string($connect,$restored_id);
     $sql = "UPDATE boutique SET deleted = 0 WHERE id='$restored_id'";
     $result = mysqli_query($connect,$sql);
     header("Location: product.php?restored=".$restored_id);
     
    
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//delete products permanently----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    if(isset($_GET["delete"])){
         $delete_id = (int)$_GET["delete"];
         $delete_id = mysqli_real_escape_string($connect,$delete_id);
         $sql = "DELETE FROM boutique WHERE id='$delete_id'";
         $result = mysqli_query($connect,$sql);
     header("Location: archive.php?");
    }
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?>

<div class='container'>
    <h2 class='text-center'>Archive</h2><hr>
    <table class='table table-condensed table-bordered table-stripped'>
        <thead> <th>Restore</th><th>Product</th> <th>Price</th> <th>Category</th> <th>Sold</th> <th>delete</th></thead>
        <tbody>
        <?php $sql = "SELECT * FROM boutique WHERE deleted=1";
                  $result = mysqli_query($connect,$sql);
                  while( $row = mysqli_fetch_assoc($result)) :
                       $row_boutique = $row["categories"];
                       $sql_category = "SELECT * FROM category WHERE id='$row_boutique'";
                       $result_category = mysqli_query($connect,$sql_category);
                       $row_items = mysqli_fetch_assoc($result_category);
                        $parent_id = $row_items["parent"];
                        $sql_parent = "SELECT * FROM category WHERE id='$parent_id'";
                        $result_parent = mysqli_query($connect,$sql_parent);
                        $parent_items = mysqli_fetch_assoc($result_parent);
                        $category = $parent_items["category"]." - ".$row_items["category"];
                     ?>
            <tr>
                <td><a href='archive.php?restore=<?=$row["id"] ;?>' class='btn btn-xs btn-warning'><span class='glyphicon glyphicon-refresh'></span></a></td>
                <td><?=$row["title"] ;?></td>
                <td>&#8358; <?= $row["price"] ;?></td>
                <td><?=$category ;?></td>
                 <td>0</td>
                <td><a href='archive.php?delete=<?=$row["id"] ;?>' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></a></td>
             </tr>
             <?php endwhile ;?>
        </tbody>
    </table>
</div>



<?php include "include/footer.php" ;?>
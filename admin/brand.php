<?php
   include "../connection.php";
   if(! login_user()){
    login_redirect();
   }
   include "include/header.php";
   include "include/navigation.php";
   $error = array();
?>
<?php
   //Adding a brand -----------------------------------------------------------------------------------------------------------------------------------
            if(isset($_POST["add_brand"])){
                   $brand = mysqli_real_escape_string($connect,$_POST["brand"]);
                 if(!empty($brand)){
                    $sql_check = "SELECT * FROM brand WHERE brand='$brand'";
                    $result_check = mysqli_query($connect,$sql_check);
                    if(mysqli_num_rows($result_check) > 0){
                        $error[] = "<strong>".$brand."</strong> Brand  Exist In Database";
                    }else{
                        $sql_insert = "INSERT INTO brand(brand) VALUES('$brand')";
                        if(isset($_GET["edit"])){
                            $edit_id = $_GET["edit"];
                            $sql_insert = "UPDATE brand SET brand='$brand' WHERE id='$edit_id'";  
                        }
                        mysqli_query($connect,$sql_insert);
                        $_SESSION["success"] = $brand." has been added";
                        header("Location: brand.php");
                    }
                 }else{
                    $error[] = "The Brand Field is Empty"; 
                 }
            }
 //-----------------------------------------------------------------------------------------------------------------------------------------------------
 //Editing the brand-------------------------------------------------------------------------------------------------------------------------------------
 $brand = ((isset($_POST["brand"]) && !empty($_POST["brand"]))? $_POST["brand"] : '');
 if(isset($_GET["edit"])){
           $edit_id = (int)$_GET["edit"];
           $sql_select = "SELECT * FROM brand WHERE id='$edit_id'";
           $result =  mysqli_query($connect,$sql_select);
           $row_brand = mysqli_fetch_assoc($result);
           $brand = ((isset($_POST["brand"]) && !empty($_POST["brand"]))? $_POST["brand"] : $row_brand["brand"]);
      }
 //------------------------------------------------------------------------------------------------------------------------------------------------------  
 //Delete brand item-------------------------------------------------------------------------------------------------------------------------------------------------------
        if(isset($_GET["delete"])){
             $delete_id = $_GET["delete"];
             $sql = "DELETE FROM brand WHERE id='$delete_id'";
             mysqli_query($connect,$sql);
             $_SESSION["success"] = "Item has been deleted Successfuly!";
             header("Location: brand.php");

        }
 
  //------------------------------------------------------------------------------------------------------------------------------------------------------------
   if(!empty($error)){
            echo get_error($error);
        } 
   ?>




        <div class='container text-center'>
            <h2 class='text-center'><?=((isset($_GET["edit"]))? 'Edit Brand' : 'Add Brand') ;?></h2><hr>
            <form class='form-inline' action='brand.php<?=((isset($_GET["edit"]))? '?edit='.$edit_id : '') ;?>' class='form-inline' method='POST'>
            <div class='form-group'>
                <label for='brand'> Brand :</label>
                <input type='text' name='brand' id='brand' class='form-control' value='<?=$brand;?>'>
                <input type='submit' class='btn btn-success'name='add_brand' value='<?=((isset($_GET["edit"]))? 'Edit Brand' : 'Add Brand') ;?>'>
                <?php if(isset($_GET["edit"])):?>
                <a href='brand.php' class='btn btn-default'>Cancle</a>
                <?php endif; ?>
               </div>
            </form>
         </div>
       <br>
        
        <table class='table table-bordered table-stripped table-auto table-condensed'>
            <thead>
                <th></th> <th>Brands</th> <th></th>
            </thead>
            <tbody>
                <?php 
                     $sql = $connect->query("SELECT * FROM brand ORDER BY brand"); 
                     while($row = mysqli_fetch_assoc($sql)): ?>
                <tr>
                    <td><a href='brand.php?edit=<?=$row["id"] ;?>' class='btn btn-xs btn-default'><span class='glyphicon glyphicon-pencil'></span></a></td> 
                    <td><?=$row["brand"] ;?></td>
                    <td><a href='brand.php?delete=<?=$row["id"] ;?>' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></a></td> 
                </tr>
                <?php endwhile ;?>
            </tbody>
        </table>



<?php include "include/footer.php" ;?>
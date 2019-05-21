<?php
   require_once $_SERVER["DOCUMENT_ROOT"]."/boutique/connection.php";
   if(! login_user()){
    login_redirect();
   }
   include "include/header.php";
   include "include/navigation.php";
   $error = array();
?>


<?php
     if(isset($_GET["add"]) || isset($_GET["edit"])){ 
     //getting the sizes and quantity----------------------------------------------------------------------------------------------------------------------------------------------------------------------------        
         if($_POST){
             $sizes_array = array();
              if(!empty($_POST["sizes"])){
                   $size_string = mysqli_real_escape_string($connect,$_POST["sizes"]);
                   $size_string = rtrim($size_string,",");
                   $sizes_array = explode(",",$size_string);
                    $s_array = array();
                    $q_array = array();
                   foreach($sizes_array as $size_sq){
                    $size_item = explode(":",$size_sq);
                    $s_array[] = $size_item[0];
                    $q_array[] = $size_item[1];
                   }
              }
         }
    
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
     $title = ((isset($_POST["title"]) && !empty($_POST["title"]))? mysqli_real_escape_string($connect,$_POST["title"])  : ''); 
     $brand = ((isset($_POST["brand"]) && !empty($_POST["brand"]))? mysqli_real_escape_string($connect,$_POST["brand"]) : '');
     $catgeory = ((isset($_POST["category"]) && !empty($_POST["category"]))? mysqli_real_escape_string($connect,$_POST["category"]) : '');
     $child = ((isset($_POST["child"]) && !empty($_POST["child"]))? mysqli_real_escape_string($connect,$_POST["child"]) : '');
     $price = ((isset($_POST["price"]) && !empty($_POST["price"]))? mysqli_real_escape_string($connect,$_POST["price"]) : '');
     $list_price = ((isset($_POST["list_price"]) && !empty($_POST["list_price"]))? mysqli_real_escape_string($connect,$_POST["list_price"]) :'');
     $sizes =((isset($_POST["sizes"]) && !empty($_POST["sizes"]))? mysqli_real_escape_string($connect,$_POST["sizes"]) :'');
     $description = ((isset($_POST["description"]) && !empty($_POST["description"]))? mysqli_real_escape_string($connect,$_POST["description"]) : '');
     $image = '';
     if(isset($_GET["edit"])){
        $id = (int)$_GET["edit"];
        $sql = "SELECT * FROM boutique WHERE id='$id'";
        $result = mysqli_query($connect,$sql);
        $edit = mysqli_fetch_assoc($result);
        $edit_id = $edit["id"];
        $title = ((isset($_POST["title"]) && !empty($_POST["title"]))? mysqli_real_escape_string($connect,$_POST["title"]) : $edit["title"]);
        $brand = ((isset($_POST["brand"]) && !empty($_POST["brand"]))? mysqli_real_escape_string($connect,$_POST["brand"]) : $edit["brand"]);
        $child = ((isset($_POST["child"]) && !empty($_POST["child"]))? mysqli_real_escape_string($connect,$_POST["child"]) : $edit["categories"]);
        $price = ((isset($_POST["price"]) && !empty($_POST["price"]))? mysqli_real_escape_string($connect,$_POST["price"]) : $edit["price"]);
        $list_price = ((isset($_POST["list_price"]))? mysqli_real_escape_string($connect,$_POST["list_price"]) : $edit["list_price"]);
        $sizes =((isset($_POST["sizes"]) && !empty($_POST["sizes"]))? mysqli_real_escape_string($connect,$_POST["sizes"]) : $edit["sizes"]);
        $description = ((isset($_POST["description"]))? mysqli_real_escape_string($connect,$_POST["description"]) : $edit["description"]);
        $id_category = $edit["categories"];
        $sql_category = "SELECT * FROM category WHERE id='$id_category'";
        $result_category = mysqli_query($connect,$sql_category);
        $edit_cat = mysqli_fetch_assoc($result_category);
        $edit_cat["parent"];
        $catgeory = ((isset($_POST["category"]) && !empty($_POST["category"]))? mysqli_real_escape_string($connect,$_POST["category"]) : $edit_cat["parent"]);
        $image = ((!empty($edit["image"] ))? $edit["image"] : '');
        $db_path = $image;
    
     //delete edit image-------------------------------------------------------------------------------------------------------------------------------------------------------------------
            if(isset($_GET["delete_image"])){
                $delete_id = $_GET["delete_image"];
                $image_link = $_SERVER["DOCUMENT_ROOT"]. $edit["image"];
                unlink( $image_link);
                $sql = "UPDATE boutique SET image='' WHERE id='$delete_id'";
                $result = mysqli_query($connect,$sql);
                header("Location: product.php?edit=".$edit_id); 
            }
    //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    }
       
       if(isset($_POST["add_product"])){

           if(empty($title) || empty($brand) ||  empty($child) ||  empty($catgeory)  ||  empty($sizes)){
               $error[] = "All fields with <strong>*</strong> are required";
           }
        
           if(!empty($_FILES)){
                 $file = $_FILES["image"];
                 $file_name = $file["name"];   
                 $file_tmp_name = $file["tmp_name"];   
                 $file_size = $file["size"];   
                 $file_type = $file["type"];   
                 $file_error = $file["error"]; 
                   $file_ext = explode(".",$file_name);
                   $file_actual_Ext = strtolower(end($file_ext));
                   $file_new_name = "pic"."_".md5(microtime()). ".".$file_actual_Ext;
                   $file_location = "uploads/".$file_new_name;
                   $db_path = "/boutique/admin/uploads/".$file_new_name;
                   $File_allowed = array('jpg', 'jpeg', 'png');
                   if(!in_array($file_actual_Ext,$File_allowed)){
                        $error[] =  " Image File type must be jpg, jpeg or png";
                   } 
                   if($file_error === 1){
                        $error[] ="There was an error uploading this file";
                   }
                   if($file_size > 1000000){
                    $error[] ="The file you are trying to upload is too big";
               }
              
            }
           //error message
           if(!empty($error)){
            echo get_error($error);
           }else{
            $sizes = rtrim($sizes);
            move_uploaded_file($file_tmp_name,$file_location);
            $sql = "INSERT INTO boutique(title,price,list_price,brand,categories,description,image,sizes) 
            VALUES('$title','$price','$list_price','$brand','$child','$description','$db_path','$sizes')";
            if(isset($_GET["edit"])){
                   $sql = "UPDATE boutique SET title='$title', price='$price', list_price='$list_price', brand='$brand', 
                   categories='$child', description='$description', image='$db_path', sizes='$sizes' WHERE id='$edit_id'";
            }
            mysqli_query($connect,$sql);
            header("location: product.php?success");
        }
    }
       

           
       ?>
         
  

        
          <div class='container'>
                <h2 class='text-center'> <?=((isset($_GET["edit"]))?'Edit ' : 'Add') ;?>Products</h2><hr>
                <form action='product.php?<?=((isset($_GET["edit"]))?"edit=".$edit_id : "add=1") ;?>' method='POST' enctype='multipart/form-data'>
                      <div class='form-group col-md-3'>
                         <label for='title'>Title *:</label>
                         <input type='text' name='title' id='title' class='form-control' value='<?=$title ;?>'>
                      </div>
                      <div class='form-group col-md-3'>
                        <label for='brand'>Brand *:</label>
                        <select name='brand' class='form-control' id='brand'>
                        <option value=''></option>
                        <?php $sql_brand = $connect->query("SELECT * FROM brand ORDER BY brand");
                               while($row_brand = mysqli_fetch_assoc($sql_brand)):?>
                         <option value='<?=$row_brand["id"] ;?>'<?=(($brand == $row_brand["id"])? 'selected' : '') ;?>><?=$row_brand["brand"] ;?></option>
                         <?php endwhile ;?>
                        </select>
                      </div>
                         <div class='form-group col-md-3'>
                             <label for='category'>Category *:</label>
                             <select name='category' class='form-control' id='category'>
                             <option value=''></option>
                             <?php $sql_category = $connect->query("SELECT * FROM category WHERE parent = 0 ORDER BY category");
                               while($row_category = mysqli_fetch_assoc($sql_category)):?>
                                  <option value='<?=$row_category["id"] ;?>'<?=(($catgeory == $row_category["id"])? 'selected': '')?>><?=$row_category["category"] ;?></option>
                                <?php endwhile ;?>
                             </select>
                         </div>
                         <div class='form-group col-md-3'>
                             <label for='brand'>Child *:</label>
                             <select name='child' class='form-control' id='child'>
                                  <option value=''></option>
                                  <option value=''></option>
                             </select>
                         </div>
                         <div class='form-group col-md-3'>
                             <label for='price'>Price *:</label>
                             <input type='text' name='price' id='price' class='form-control' value='<?=$price ;?>'>
                         </div>
                         <div class='form-group col-md-3'>
                             <label for='list_price'>List Price :</label>
                             <input type='text' name='list_price' id='list_price' class='form-control' value='<?=$list_price ;?>'>
                         </div>
                         <div class='form-group col-md-3'>
                         <label for='qty_size'>Quantity & Sizes:</label>                         
                             <button class='form-control btn btn-warning' onclick="jQuery('#Sizesmodal').modal('toggle'); return false;">Quantity & Sizes</button>
                         </div>
                         <div class='form-group col-md-3'>
                            <label for='qty'>Quantity & Sizes Review:</label>                         
                            <input type='text' class='form-control' name='sizes' id='sizes' value='<?=$sizes ;?>' readonly>
                         </div>
                         <div class='form-group col-md-6'>                      
                            <?php if($image != ''):?>
                                <div class='image_container'>
                                  <img src='<?=$image ;?>' alt='<?=$image ;?>'><br>
                                  <a href='product.php?edit=<?=$edit_id ;?>&delete_image=<?=$edit_id ;?>' class='btn btn-danger'>Delete</a>
                                </div>
                                 <?php else:?>
                           <label for='image'>Image *:</label>  
                           <input type='file' name='image' class='form-control' value=''>
                             <?php endif; ?>
                         </div>
                         <div class='form-group col-md-6'>
                            <label for='description'>Description :</label>                         
                            <textarea name='description' id='description' class='form-control' rows='6'><?=$description ;?></textarea>
                         </div>
                         <div class='form-group pull-right'>                        
                            <input type='submit' name='add_product' class='btn btn-success' value=" <?=((isset($_GET["edit"]))?'Edit Product' : 'Add Product') ;?>">
                            <a href='product.php' class='btn btn-default'>Cancle</a>
                         </div>
                 </form>
            </div>

                      <!--Quantity & size modal-->
                      <div class="modal fade" id="Sizesmodal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                      <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                             <div class="modal-header">
                                 <h4 class="modal-title" id='myModalLabel'>Quantity & size</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label='close'>&times;</button>
                             </div>
                           <div class="modal-body">
                              <div class='container-fluid'>
                                 <?php for($i=1; $i <= 12; $i++):?>
                                       <div class='form-group col-md-4'>
                                            <label value=''>Size:</label>
                                            <input type='text' name='size<?= $i;?>' id='size<?= $i;?>' class='form-control' value='<?=((!empty( $s_array[$i - 1]))?  $s_array[$i - 1] : '') ;?>'>
                                       </div>
                                       <div class='form-group col-md-2'>
                                            <label value=''>Quantity:</label>
                                            <input type='number' name='Qty<?= $i;?>' id='Qty<?= $i;?>' class='form-control' min='0' value='<?=((!empty( $q_array[$i - 1]))?  $q_array[$i - 1] : '') ;?>'>
                                       </div>
                                 <?php endfor;?>
                               </div>
                             </div>
                            <div class="modal-footer">
                                <button type="button" class='btn btn-default' data-dismiss="modal">Close</button>
                                <button type='button' class='btn btn-success' onclick=" updatesize();jQuery('#Sizesmodal').modal('toggle');return false;">Save</button>
                            </div>
        
                            </div>
                        </div>
                     </div>
                    <!-- end Quantity & size modal-->
    
    <?php }else{

    //Feautured product -----------------------------------------------------------------------------------------------------------------------------------------------
     if(isset($_GET["feautured"])){
              $feautured_id = (int)$_GET["id"];
               $feautured = (int)$_GET["feautured"];
               $feautured = mysqli_real_escape_string($connect,$feautured);
               $sql_feautured = "UPDATE boutique SET feautured='$feautured' WHERE id='$feautured_id'";
               mysqli_query($connect, $sql_feautured);
               header("Location: product.php");
     }
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------    
//delete products move to archive-----------------------------------------------------------------------------------------------------------------------------------------------------------------------
    if(isset($_GET["delete"])){
         $delete_id = (int)$_GET["delete"];
         $delete_id = mysqli_real_escape_string($connect,$delete_id);
         $sql = "SELECT * FROM boutique WHERE id='$delete_id'";
         $result = mysqli_query($connect,$sql);
         $row = mysqli_fetch_assoc($result);
         $sql_delete = "UPDATE boutique SET feautured = 1, deleted = 1 WHERE id='$delete_id'";
         mysqli_query($connect, $sql_delete);
         $_SESSION["success"] = "<strong>".$row["title"]."</strong> has been deleted!";
         header("Location: product.php");
    }
//display retore alert message on product page----------------------------------------------------------------------------------------------------------------------------------
  if(isset($_GET["restored"])){
       $restored = (int)$_GET["restored"];
       $restored = mysqli_real_escape_string($connect,$restored);
       $sqliii = "SELECT * FROM boutique WHERE id='$restored'";
       $result_restored = mysqli_query($connect,$sqliii);
       $row_restored = mysqli_fetch_assoc($result_restored);
       header("Location: product.php");
       $_SESSION["success"] = "<strong>".$row_restored["title"]."</strong> has been restored!";
  }
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    ?>

<div class='container'>
    <h2 class='text-center'>Products</h2>
    <a href='product.php?add=1' class='btn btn-success pull-right' id='add_product'>Add Product</a><hr>
    <table class='table table-condensed table-bordered table-stripped'>
        <thead><th>edit</th> <th>Product</th> <th>Price</th> <th>Category</th> <th>Feautured</th> <th>Sold</th> <th>delete</th></thead>
        <tbody>
            <?php $sql = "SELECT * FROM boutique WHERE deleted = 0";
                  $result = mysqli_query($connect,$sql);
                   while($row = mysqli_fetch_assoc($result)) :
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
            <td><a href='product.php?edit=<?=$row["id"] ;?>'class='btn btn-xs btn-default'><span class='glyphicon glyphicon-pencil text-success'></span></a></td>
            <td><?=$row["title"] ;?></td>
            <td>&#8358; <?=$row["price"] ;?></td>
            <td><?=$category ;?></td>
            <td><a href='product.php?id=<?=$row["id"];?>&feautured=<?=(($row["feautured"] == 0)? 1 : 0) ;?>' class='btn btn-xs btn-default'><span class='glyphicon glyphicon-<?=(($row["feautured"] == 1)? 'plus' : 'minus') ;?>'></span></a>
            &nbsp<?=(($row["feautured"] == 1)? 'Not Feautured' : ' Feautured') ;?></td>
            <td>0</td>
            <td><a href='product.php?delete=<?=$row["id"] ;?>' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-remove'></span></a></td>
            </tr>
            <?php endwhile ;?>
        </tbody>
    </table>
</div>



<?php } include "include/footer.php" ;?>
<script>
        jQuery("document").ready(function(){
            get_child_option('<?= $child;?>');
        });
     </script>
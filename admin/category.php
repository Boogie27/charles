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

  $parent = ((isset($_POST["parent"]) && !empty($_POST["parent"]))?$_POST["parent"] : '');
  $category = ((isset($_POST["category"]) && !empty($_POST["category"]))?$_POST["category"] : '');
 if(isset($_GET["edit"]) && !empty($_GET["edit"])){
    $edit_id = (int)$_GET["edit"];
    $edit_id = mysqli_real_escape_string($connect,$edit_id);
    $sql = "SELECT * FROM category WHERE id='$edit_id'";
    $result_edit = mysqli_query($connect,$sql);
    $edit = mysqli_fetch_assoc($result_edit);
    $parent = ((isset($_POST["parent"]) && !empty($_POST["parent"]))?$_POST["parent"] : $edit["parent"]);
    $category = ((isset($_POST["category"]) && !empty($_POST["category"]))?$_POST["category"] : $edit["category"]);

}
                 if(isset($_POST["add_category"])){
                          $parent = mysqli_real_escape_string($connect,$_POST["parent"]);
                          $category = mysqli_real_escape_string($connect,$_POST["category"]);
                          if(empty($category)){
                            $error[] = "The Category Field is Empty";
                          }else{
                              //check if category exists in database
                              $sql = "SELECT * FROM category WHERE category = '$category' AND parent = $parent";
                              $result = mysqli_query($connect,$sql);
                              if(mysqli_num_rows($result) > 0){
                                 $error[] = "The Category <strong>".$category."</strong> Exist in database";
                              }else{
                                  $sql_insert = "INSERT INTO category(category,parent) VALUES('$category','$parent')";
                                  if(isset($_GET["edit"])){
                                       $edit_id = $_GET["edit"];
                                       $sql_insert = "UPDATE category SET category='$category' , parent=$parent  WHERE id='$edit_id'";
                                  }
                                  mysqli_query($connect,$sql_insert);
                                  $_SESSION["success"] = "<strong>".$category."</strong> Item added successfuly";
                                  header("Location: category.php");
                              }
                          }
                 }
//delete category----------------------------------------------------------------------------------------------------------------------------
if(isset($_GET["delete"])){
   $delete_id = (int)$_GET["delete"];
   $delete_id = mysqli_real_escape_string($connect,$delete_id);
   //check database for category before deleting
   $sql_check = "SELECT * FROM category WHERE id='$delete_id'";
   $result_chect = mysqli_query($connect,$sql_check);
   $row = mysqli_fetch_assoc($result_chect);
   if($row["parent"] == 0){
        $delete = "DELETE FROM category WHERE parent='$delete_id'";
        mysqli_query($connect,$delete);
   }
   $delete = "DELETE FROM category WHERE id='$delete_id'";
   mysqli_query($connect,$delete);
   $error[] = "item is being Deleted from your database";
   header("Location: category.php?category=deleted");
   exit();
}

//error message-----------------------------------------------------------------------------------------------------------------------------------
if(!empty($error)){
     echo get_error($error);
    }
?>

              <div class='container'>
                  <h2 class='text-center'>Category<h2><hr>
              </div>
              <div class='container'>
                  <div class='col-md-6'>
                     <form action='category.php<?=((isset($_GET["edit"]))?'?edit='.$edit_id : '') ;?>' method='POST'>
                        <h3 class='text-center'>Add Category</h3></hr>
                        <label for='parent'>Parent :</label>
                        <select name='parent' class='form-control'>
                        <option value='0'>Parent</option>
                         <?php $sql = "SELECT * FROM category WHERE parent = 0";
                              $result = mysqli_query($connect,$sql);
                              while($row = mysqli_fetch_assoc($result)):?>
                          <option value='<?=$row["id"] ;?>'<?=(( $parent == $row["id"]) ?'selected' : '') ;?>><?=$row["category"];?></option>
                          <?php endwhile ;?>
                        <select>
                        <label for='category'>Category :</label>
                        <input type='text' name='category' class='form-control' value='<?=$category;?>'><br>
                        <input type='submit' name='add_category' class='btn btn-success' value='Add Category'>
                         <a href='category.php' class='btn btn-default'>Cancle</a>
                      </form>
                  </div>
                  <div class='col-md-6'>
                      <table class='table table-condensed table-stripped table-bordered'>
                          <thead><th>Edit</th> <th>Category</th> <th>Parent</th> <th>Eelete</th> </thead>
                          <tbody>
                          <?php 
                               $sql_parent = "SELECT * FROM category WHERE parent = 0";
                               $result_parent = mysqli_query($connect,$sql_parent);
                               while($row_parent = mysqli_fetch_assoc($result_parent)) :
                                     $id_parent = $row_parent["id"];?>
                              <tr class='bg-primary'>
                                 <td><a href='category.php?edit=<?= $id_parent;?>'  class='btn btn-xs btn-default'><span class='glyphicon glyphicon-pencil'></span></a></td>
                                 <td><?=$row_parent["category"] ;?></td>
                                 <td>Parent</td>
                                 <td><a href='category.php?delete=<?= $id_parent;?>' class='btn btn-xs btn-danger'><span class='glyphicon glyphicon-trash'></span></a></td>
                              </tr>
                                 <?php     
                                 $sql_category = "SELECT * FROM category WHERE parent='$id_parent'";
                                 $result_category = mysqli_query($connect,$sql_category);
                                 while($row_categoryt = mysqli_fetch_assoc($result_category)) :
                                    $id_category = $row_categoryt["id"] ;?>    
                                     <tr class='bg-info'>
                                       <td><a href='category.php?edit=<?= $id_category;?>'  class='btn btn-xs btn-default'><span class='glyphicon glyphicon-pencil'></span></a></td>
                                       <td><?=$row_categoryt["category"] ;?></td>
                                       <td><?=$row_parent["category"] ;?></td>
                                       <td><a href='category.php?delete=<?= $id_category;?>' class='btn btn-xs btn-default'><span class='glyphicon glyphicon-trash'></span></a></td>
                              </tr>
                                 <?php endwhile ;?>
                             <?php endwhile ;?>
                          </tbody>
                      <table>
                  </div>
              </div>


<br><br><br>

<?php include "include/footer.php" ;?>
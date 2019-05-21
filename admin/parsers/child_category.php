<?php
   require_once $_SERVER["DOCUMENT_ROOT"]."/boutique/connection.php";
   $parent_ID = (int)$_POST["parentID"];
   $selected = (int)$_POST["selected"];
   $child_select = $connect->query("SELECT * FROM category WHERE parent='$parent_ID' ORDER BY category");
   ob_start();?>
        <option value=''></option>
        <?php while($row_child = mysqli_fetch_assoc($child_select)): ?>
       <option value='<?=$row_child["id"] ;?>'<?=(($selected == $row_child["id"])? ' selected' : '') ;?>><?=$row_child["category"] ;?></option>
        <?php endwhile; ?>
   <?= ob_get_clean();?>
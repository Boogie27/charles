<div class='recent' id='recent'>
    <h3 class='text-center'>Popular Items</h3>
       <?php  
           $sql = $connect->query("SELECT * FROM cart WHERE paid = 1 ORDER BY id DESC LIMIT 5");
           $item_array = array();
           while($row = mysqli_fetch_assoc($sql)){
            $item_array[] = $row;
           }
           $item_count = $sql->num_rows;
           $json_array = array();
           for($i=0; $i<$item_count; $i++){
                  $item_json = $item_array[$i]["item"];
                  $item_strip = stripslashes($item_json);
                  $item_decode = json_decode($item_strip,true);
                  foreach($item_decode as $item){
                        if(!in_array($item["item_id"], $json_array)){
                          $json_array[] = $item["item_id"];
                        }
                  }
          }
       ?>
       <table class='table table-condensed table-stripped table-bordered'>
          <?php 
              foreach($json_array as $id):
                 $sql_id = $connect->query("SELECT id,title FROM boutique WHERE id = '$id'");
                  while($row = mysqli_fetch_assoc($sql_id)):
              ?>
                      <tr style='background-color: <?=((isset($_GET["detail"]) && $_GET["detail"] == $row["id"])? '#f1f1f1' : '') ;?>'>
                          <td><?=$row["title"] ;?></td>
                          <td><a href='index.php?detail=<?=$row["id"] ;?>'>view</a></td>
                      </tr>
                <?php endwhile ;?>
            <?php endforeach ;?>
       </table>
</div>
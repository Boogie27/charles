<?php
    $price_sort = ((isset($_REQUEST["price_sort"]))? mysqli_real_escape_string($connect,$_REQUEST["price_sort"]) : "");
    $min_price = ((isset($_REQUEST["min_price"]))? mysqli_real_escape_string($connect,$_REQUEST["min_price"]) : "");
    $max_price = ((isset($_REQUEST["max_price"]))? mysqli_real_escape_string($connect,$_REQUEST["max_price"]) : "");
    $brand = ((isset($_REQUEST["brand_search"]))? mysqli_real_escape_string($connect,$_REQUEST["brand_search"]) : "");
    $category_id = ((isset($_REQUEST["id"]))? mysqli_real_escape_string($connect,$_REQUEST["id"]) : "");
    $sql = $connect->query("SELECT * FROM brand ORDER BY brand");
?>


<div class='filter'>
     <h3 class='text-center'>Search By:</h3>
     <h4 class='text-center'>Price:</h4>
     <form action='search.php' method='POST'>
         <input type='hidden' name='id' value='<?=$category_id ;?>'>
         <input type='hidden' name='price_sort' value=''>
          <input type='radio' name='price_sort' value='low'<?=(($price_sort == "low")? "checked": "") ;?>>Low To High<br>
          <input type='radio' name='price_sort' value='high'<?=(($price_sort == "high")? "checked": "") ;?>>High To Low<br>
          <input type='text' class='price_input' name='min_price' value='<?=$min_price;?>' placeholder='min price'> To
          <input type='text' class='price_input' name='max_price' value='<?=$max_price;?>' placeholder='max price'><br><br>
          <h4 class='text-center'>Search By Brand:</h4>
          <input type='radio' class='brand_search' name='brand_search' value=''<?=(($brand == "")? "checked" : "") ;?>>All<br>
          <?php while($row = mysqli_fetch_assoc($sql)): ?>
             <input type='radio' class='brand_search' name='brand_search' value='<?=$row["id"];?>'<?=(($brand == $row["id"])? "checked" : "") ;?>><?=$row["brand"];?><br>
          <?php endwhile ;?><br>
          <input type='submit' class='btn btn-xs btn-primary' name='search' value='Search'> 
     </form>
</div>
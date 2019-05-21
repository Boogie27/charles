<?php 
   $alert_message = '';
?>
    <!--Navigation-->
    <section class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="conatiner">
            <div class="navbar-header">
                <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand">Lucy's Boutique</a>    
            </div>
            <!--MENU LINK-->
                  <div class="collapse navbar-collapse" id='navigation'>
                          <ul class='nav navbar-nav navbar-right '>
                               <?php $sql_nav = "SELECT * FROM category WHERE parent = 0";
                                      $result_nav = mysqli_query($connect,$sql_nav);
                                      while($row_nav = mysqli_fetch_assoc($result_nav)):
                                        $id = $row_nav["id"];?>
                                     <li class='drop-down'>
                                       <a href='#' class='dropdown-toggle' data-toggle='dropdown'><?=$row_nav["category"] ;?><span class='caret'></span></a>
                                       <ul class='dropdown-menu' role='menu'>
                                        <?php $sql_child = "SELECT * FROM category WHERE parent='$id'";
                                         $result_child = mysqli_query($connect,$sql_child);
                                         while($row_child = mysqli_fetch_assoc($result_child)):?>
                                         <li><a href='category.php?id=<?=$row_child["id"] ;?>'><?=$row_child["category"] ;?></a></li> 
                                          <?php endwhile ;?> 
                                     </ul>
                                   </li>
                               <?php endwhile;?>
                               <li> <a href='cart.php' class='btn text-warning'><span class='glyphicon glyphicon-shopping-cart'></span>view cart</a></li>
                        </ul>
                   </div>

        </div>
    </section>
    
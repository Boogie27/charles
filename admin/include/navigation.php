  <!--TOP NAVIGATION-->
  <nav class='navbar navbar-default navbar-fixed-top'>
           <div class="navbar-header">
                <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand"><i class="fa fa-home"></i>Lucy's Boutique</a>     
            </div>
                        <ul class='nav navbar-nav collapse navbar-collapse'>
                            <li><a href='brand.php'>Brands</a></li>
                            <li><a href='category.php'>Category</a></li>
                            <li><a href='product.php'>Products</a></li>
                            <li><a href='archive.php'>Archive</a></li>
                            <li><a href='ordered_item.php'>Ordered</a></li>
                           <?php if(permission()) :?>
                            <li><a href='users.php'>users</a></li>
                            <?php endif; ?>
                            <li class='dropdown'>
                                 <a href='#' class='dropdown-toggle' data-toggle='dropdown'>Hello <?=$firstname ;?>!<span class='caret'></span></a>
                                 <ul class='dropdown-menu' role='menu'>
                                     <li><a href='password_change.php'>Change Password</a></li>
                                     <li><a href='logout.php'>Logout</a></li>
                                 </ul>
                            </li>
                        </ul>
                     </div>
               </nav>
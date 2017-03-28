<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">

            <?php if(isset($user->images[0]->url)): ?>
                <img src="<?php echo getImageProfile($user, 150, 150);?>" class="img-circle" alt="User Image" />
            <?php else: ?>
            <?php   if(isset($user->gender) && ($user->gender == 'female')): ?>
            <?php       $img_rand = 'f01'; ?>
            <?php   else: ?>
            <?php       $img_rand = 'm01'; ?>
            <?php   endif; ?>
                <img src="<?php echo getImageLink('img', 'avatar', $img_rand, 'png', 150, 150);?>" class="img-circle" alt="User Image" />
            <?php endif; ?>
                
            </div>
            <div class="pull-left info">
                <p><?php echo isset($user->name)?$user->name:'';?></p>

                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..." />
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?php echo (preg_match( "/navigations|banners|products|news|members/",Request::segment(1))) ? '' : 'active ';?>treeview">
                <a href="<?php echo URL::to('');?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <li class="<?php echo Request::is('navigations*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('navigations');?>">
                    <i class="fa fa-files-o"></i> <span>Navigations</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('navigations') ? 'active' : '';?>"><a href="<?php echo URL::to('navigations');?>"><i class="fa fa-circle-o"></i> List Navigations</a></li>
                    <li class="<?php echo Request::is('navigations/add*') ? 'active' : '';?>"><a href="<?php echo URL::to('navigations/add');?>"><i class="fa fa-circle-o"></i> Add Navigations</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('banners*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('banners');?>">
                    <i class="fa fa-files-o"></i> <span>Banners</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('banners') ? 'active' : '';?>"><a href="<?php echo URL::to('banners');?>"><i class="fa fa-circle-o"></i> List Banner</a></li>
                    <li class="<?php echo Request::is('banners/add*') ? 'active' : '';?>"><a href="<?php echo URL::to('banners/add');?>"><i class="fa fa-circle-o"></i> Add Banners</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('products*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('products');?>">
                    <i class="fa fa-files-o"></i> <span>Products</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('products') ? 'active ' : '';?>"><a href="<?php echo URL::to('products');?>"><i class="fa fa-circle-o"></i> List Products</a></li>
                    <li class="<?php echo Request::is('products/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('products/add');?>"><i class="fa fa-circle-o"></i> Add Products</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('news*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('news');?>">
                    <i class="fa fa-files-o"></i> <span>News</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('news') ? 'active ' : '';?>"><a href="<?php echo URL::to('news');?>"><i class="fa fa-circle-o"></i> List News</a></li>
                    <li class="<?php echo Request::is('news/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('news/add');?>"><i class="fa fa-circle-o"></i> Add News</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('pages*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('pages');?>">
                    <i class="fa fa-files-o"></i> <span>Pages</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('pages') ? 'active ' : '';?>"><a href="<?php echo URL::to('pages');?>"><i class="fa fa-circle-o"></i> List Pages</a></li>
                    <li class="<?php echo Request::is('pages/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('pages/add');?>"><i class="fa fa-circle-o"></i> Add Pages</a></li>
                </ul>
            </li>
<?php //if(isset($user->roles[0]->id) && ($user->roles[0]->id == '1')): ?>
            <li class="<?php echo Request::is('members*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('members');?>">
                    <i class="fa fa-users"></i> <span>Members</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('members') ? 'active ' : '';?>"><a href="<?php echo URL::to('members');?>"><i class="fa fa-circle-o"></i> List Members</a></li>
                    <li class="<?php echo Request::is('members/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('members/add');?>"><i class="fa fa-circle-o"></i> Add Members</a></li>
                </ul>
            </li>
<?php //endif;?>
			<li class="<?php echo Request::is('categories*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('categories');?>">
                    <i class="fa fa-files-o"></i> <span>Category</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('categories') ? 'active ' : '';?>"><a href="<?php echo URL::to('categories');?>"><i class="fa fa-circle-o"></i> List Category</a></li>
                    <li class="<?php echo Request::is('categories/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('categories/add');?>"><i class="fa fa-circle-o"></i> Add Category</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('blockwords*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('blockwords');?>">
                    <i class="fa fa-files-o"></i> <span>Blockwords</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('blockwords') ? 'active ' : '';?>"><a href="<?php echo URL::to('blockwords');?>"><i class="fa fa-circle-o"></i> List Blockwords</a></li>
                    <li class="<?php echo Request::is('blockwords/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('blockwords/add');?>"><i class="fa fa-circle-o"></i> Add Blockwords</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('comments*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('comments');?>">
                    <i class="fa fa-files-o"></i> <span>Comments</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('comments') ? 'active ' : '';?>"><a href="<?php echo URL::to('comments');?>"><i class="fa fa-circle-o"></i> List Comments</a></li>
                    <li class="<?php echo Request::is('comments/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('comments/add');?>"><i class="fa fa-circle-o"></i> Add Comments</a></li>
                    <li class="<?php echo Request::is('comments/blockwords*') ? 'active ' : '';?>"><a href="<?php echo URL::to('comments/blockwords');?>"><i class="fa fa-circle-o"></i> Blockwords Comments</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('contacts*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('contacts');?>">
                    <i class="fa fa-files-o"></i> <span>Contacts</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('contacts') ? 'active ' : '';?>"><a href="<?php echo URL::to('contacts');?>"><i class="fa fa-circle-o"></i> List Contacts</a></li>
                    <!-- <li class="<?php echo Request::is('contacts/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('contacts/add');?>"><i class="fa fa-circle-o"></i> Add Contacts</a></li> -->
                </ul>
            </li>
            <li class="<?php echo Request::is('quotes*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('quotes');?>">
                    <i class="fa fa-files-o"></i> <span>Quotes</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('quotes') ? 'active ' : '';?>"><a href="<?php echo URL::to('quotes');?>"><i class="fa fa-circle-o"></i> List Quotes</a></li>
                    <li class="<?php echo Request::is('quotes/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('quotes/add');?>"><i class="fa fa-circle-o"></i> Add Quotes</a></li>
                </ul>
            </li>
            <li class="<?php echo Request::is('layouts*') ? 'active ' : '';?>treeview">
                <a href="<?php echo URL::to('layouts');?>">
                    <i class="fa fa-files-o"></i> <span>Layouts</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo Request::is('layouts') ? 'active ' : '';?>"><a href="<?php echo URL::to('layouts');?>"><i class="fa fa-circle-o"></i> List Layouts</a></li>
                    <li class="<?php echo Request::is('layouts/add*') ? 'active ' : '';?>"><a href="<?php echo URL::to('layouts/add');?>"><i class="fa fa-circle-o"></i> Add Layouts</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#">
                    <i class="fa fa-files-o"></i>
                    <span>----------------------------------</span>
                    <span class="label label-primary pull-right">4</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/widgets.html">
                    <i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>
                </a>
            </li>
            <li class="treeview">
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Charts</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#">
                    <i class="fa fa-laptop"></i>
                    <span>UI Elements</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#">
                    <i class="fa fa-edit"></i> <span>Forms</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#">
                    <i class="fa fa-table"></i> <span>Tables</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
                </ul>
            </li>
            <li>
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/calendar.html">
                    <i class="fa fa-calendar"></i> <span>Calendar</span>
                    <small class="label pull-right bg-red">3</small>
                </a>
            </li>
            <li>
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/mailbox/mailbox.html">
                    <i class="fa fa-envelope"></i> <span>Mailbox</span>
                    <small class="label pull-right bg-yellow">12</small>
                </a>
            </li>
            <li class="treeview">
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#">
                    <i class="fa fa-folder"></i> <span>Examples</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#">
                    <i class="fa fa-share"></i> <span>Multilevel</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o"></i> Level One</a></li>
                    <li>
                        <a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
            </li>
            <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
            <li class="header">LABELS</li>
            <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
            <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
            <li><a href="<?php echo URL::to('public/themes/adminlte2');?>/#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
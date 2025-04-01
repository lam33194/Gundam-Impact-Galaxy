<div data-simplebar class="h-100">
    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" key="t-menu">Dashboards</li>


            <li>
                <a href="" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Dashboards</span>
                </a>
            </li>

            <li class="menu-title" key="t-administration">Administration</li>

            <li>
                <a href="" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-categories">Categories</span>
                </a>
            </li>

            <li class="{{ activeMenuLi('admin/users') }}">
                <a href="" class="waves-effect {{ activeMenu('admin/users') }}">
                    <i class="bx bx-user"></i>
                    <span key="t-users">Users</span>
                </a>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-share-alt"></i>
                    <span key="t-multi-level">Products</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li>
                        <a href="#" key="t-level-1-1">
                            List Product
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow" key="t-level-1-2">Variants</a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="" key="t-level-2-1">Colors</a></li>
                            <li><a href="" key="t-level-2-2">Sizes</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="{{ activeMenuLi('admin/comments') }}">
                <a href="" class="waves-effect">
                    <i class="bx bx-receipt"></i>
                    <span key="t-comments">Comments</span>
                </a>
            </li>

            <li>
                <a href="#" class="waves-effect">
                    <span class="badge rounded-pill bg-danger float-end" key="t-hot">2</span>
                    <i class="bx bx-receipt"></i>
                    <span key="t-orders">Orders</span>
                </a>
            </li>

            <li>
                <a href="#" class="waves-effect">
                    <i class="bx bx-receipt"></i>
                    <span key="t-flash-sale">Flash Sale</span>
                </a>
            </li>

            <li>
                <a href="#" class="waves-effect">
                    <i class="bx bx-receipt"></i>
                    <span key="t-coupons">Coupons</span>
                </a>
            </li>

            <li class="menu-title" key="t-settings">Settings</li>

            <li>
                <a href="#" class="waves-effect">
                    <i class="bx bx-receipt"></i>
                    <span key="t-banner">Banner</span>
                </a>
            </li>


            <li>
                <a href="#" class="waves-effect">
                    <i class="bx bx-receipt"></i>
                    <span key="t-menu">Menu</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- Sidebar -->
</div>

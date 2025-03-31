<div data-simplebar class="h-100">
    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" key="t-menu">Dashboards</li>


            <li>
                <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Trang chủ</span>
                </a>
            </li>

            <li class="menu-title" key="t-administration">Administration</li>

            <li>
                <a href="{{ route('admin.categories.index') }}" class="waves-effect">
                    <i class="fas fa-list"></i>
                    <span key="t-categories">Quản lý danh mục</span>
                </a>
            </li>

            <li class="{{ activeMenuLi('admin/users') }}">
                <a href="{{ route('admin.users.index') }}" class="waves-effect {{ activeMenu('admin/users') }}">
                    <i class="bx bx-user"></i>
                    <span key="t-users">Quản lý tài khoản</span>
                </a>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-share-alt"></i>
                    <span key="t-multi-level">Sản phẩm</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li>
                        <a href="{{ route('admin.products.index') }}" key="t-level-1-1">
                            Danh sách
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="has-arrow" key="t-level-1-2">Biến thể</a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li><a href="{{ route('admin.product-colors.index') }}" key="t-level-2-1">Màu</a></li>
                            <li><a href="{{ route('admin.product-sizes.index') }}" key="t-level-2-2">Kích thước</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="fas fa-receipt"></i>
                    <span key="t-multi-level">Mã giảm giá</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li>
                        <a href="{{ route('admin.vouchers.index') }}" key="t-level-1-1">
                            Quản lý voucher
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.user-vouchers.index') }}" key="t-level-1-1">
                            Quản lý user voucher
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('admin.tags.index') }}" class="waves-effect">
                    <i class="fas fa-bookmark"></i>
                    <span key="t-tags">Quản lý thẻ</span>
                </a>
            </li>

            <li class="{{ activeMenuLi('admin/comments') }}">
                <a href="{{ route('admin.comments.index') }}" class="waves-effect">
                    <i class="bx bx-receipt"></i>
                    <span key="t-comments">Quản lý bình luận</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.orders.index') }}" class="waves-effect">
                    <span class="badge rounded-pill bg-danger float-end" key="t-hot">2</span>
                    <i class="bx bx-receipt"></i>
                    <span key="t-orders">Đơn hàng</span>
                </a>
            </li>

            {{-- <li class="menu-title" key="t-settings">Settings</li>

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
            </li> --}}
        </ul>
    </div>
    <!-- Sidebar -->
</div>

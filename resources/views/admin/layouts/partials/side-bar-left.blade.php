<div data-simplebar class="h-100">
    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">

            <li class="menu-title" key="t-menu">Trang chủ</li>

            <li>
                <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                    <i class="bx bx-home-circle"></i>
                    <span key="t-chat">Tổng quan</span>
                </a>
            </li>

            <li class="menu-title" key="t-administration">Quản lý</li>

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
                    <span key="t-multi-level">Quản lý biến thể</span>
                </a>
                <ul class="sub-menu" aria-expanded="true">
                    <li>
                        <a href="{{ route('admin.product-colors.index') }}" key="t-level-1-1">
                            Màu
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.product-sizes.index') }}" key="t-level-1-1">
                            Tỉ lệ
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ activeMenuLi('admin/products') }}">
                <a href="{{ route('admin.products.index') }}" class="waves-effect {{ activeMenu('admin/products') }}">
                    <i class='bx bx-cube-alt'></i>
                    <span key="t-users">Quản lý sản phẩm</span>
                </a>
            </li>

            <li class="{{ activeMenuLi('admin/vouchers') }}">
                <a href="{{ route('admin.vouchers.index') }}" class="waves-effect {{ activeMenu('admin/vouchers') }}">
                    <i class="fas fa-receipt"></i>
                    <span key="t-multi-level">Mã giảm giá</span>
                </a>
            </li>

            <!-- <li>
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
                        <a href="{{ route('admin.user_vouchers.index') }}" key="t-level-1-1">
                            Quản lý user voucher
                        </a>
                    </li>
                </ul>
            </li> -->

            <li>
                {{-- <a href="{{ activeMenuLi('admin/tags') }}" class="waves-effect"> --}}
                    <a href="{{ route('admin.tags.index') }}" class="waves-effect {{ activeMenu('admin/tags') }}">
                    <i class="fas fa-bookmark"></i>
                    <span key="t-tags">Quản lý thẻ</span>
                </a>
            </li>

            <li class="{{ activeMenuLi('admin/comments') }}">
                <a href="{{ route('admin.comments.index') }}" class="waves-effect">
                    <i class='bx bx-chat'></i>
                    <span key="t-comments">Quản lý bình luận</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.posts.index') }}" class="waves-effect">
                    <i class="bx bx-share-alt"></i>
                    <span key="t-multi-level">Quản lý bài viết</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.orders.index') }}" class="waves-effect">
                    <span class="badge rounded-pill bg-danger float-end fs-6" key="t-hot">{{ \App\Models\Order::pending()->count() }}</span>
                    <i class='bx bx-cart-alt {{ !\App\Models\Order::pending()->exists() ?: 'bx-tada' }}' ></i>
                    <span key="t-orders">Đơn hàng</span>
                </a>
            </li>

            <!-- <li class="menu-title" key="t-administration">Thống kê</li> -->

            <!-- <li>
                <a href="{{ route('admin.stats.revenue') }}" class="waves-effect">
                    <i class="bx bx-share-alt"></i>
                    <span key="t-multi-level">Thống kê doanh thu</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.product_statistics.index') }}" class="waves-effect">
                    <i class="bx bx-share-alt"></i>
                    <span key="t-multi-level">Thống kê sản phẩm</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.stats.user') }}" class="waves-effect">
                    <i class="bx bx-share-alt"></i>
                    <span key="t-multi-level">Thống kê người dùng</span>
                </a>
            </li> -->
           
        </ul>
    </div>
    <!-- Sidebar -->
</div>
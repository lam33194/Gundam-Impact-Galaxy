<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="Datn GunDam" name="description" />
    <meta content="Datn GunDam" name="author" />

    @include('admin.layouts.partials.css')
    @yield('style')

    <title>@yield('title')</title>
</head>

<body data-sidebar="dark">

    <div id="layout-wrapper">

        <header id="page-topbar">
            @include('admin.layouts.partials.header-topbar')
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            @include('admin.layouts.partials.side-bar-left')
        </div>
        <!-- Left Sidebar End -->

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <footer class="footer">
                @include('admin.layouts.partials.footer')
            </footer>
        </div>

    </div>

    <div class="rightbar-overlay"></div>

    @include('admin.layouts.partials.config')
    @include('admin.layouts.partials.script')
    @yield('script')
</body>

</html>

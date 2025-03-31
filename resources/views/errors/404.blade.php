<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>404 Error Page | Skote - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/theme/admin/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/theme/admin/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/theme/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/theme/admin/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- App js -->
    <script src="{{ asset('assets/theme/admin/js/plugin.js') }}"></script>

</head>

<body>

    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1 class="display-2 fw-medium">4<i class="bx bx-buoy bx-spin text-primary display-3"></i>4</h1>
                        <h4 class="text-uppercase">Sorry, page not found</h4>
                        <div class="mt-5 text-center">
                            <a class="btn btn-primary waves-effect waves-light" href="{{ route('admin.dashboard') }}">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-6">
                    <div>
                        <img src="{{ asset('assets/theme/admin/images/error-img.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/theme/admin/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/theme/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/theme/admin/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/theme/admin/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/theme/admin/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ asset('assets/theme/admin/js/app.js') }}"></script>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('auth.layouts.partials.css');
    <title>@yield('title')</title>
</head>

<body>

    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">

                    @yield('content')

                </div>
            </div>
        </div>
    </div>

    @include('auth.layouts.partials.script');
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield ('title')</title>
<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
<link href="/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet"/>
<link href="/style.css" rel="stylesheet"/>
<!--Fix for the icons not being aligned correctly in IE if Helvetica-->
<!--[if lte IE 9]><style>body, p { font-family: sans-serif; }</style><![endif]-->
<script src="/js/jquery-1.7.1.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
</head>

<body>

<div class="container">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container" style="width: auto;">

                <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <a class="brand" href="/">K-Net</a>

                <div class="nav-collapse">
                    <ul class="nav pull-right">
                        <li class="home>"><a href="/"><i class="icon-home icon-white"></i>Home</a></li>
                        <li class="dropdown about">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">About<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="about">"><a href="/about">About K-Net</a></li>
                                <li class="technicalsetup">"><a href="/technicalsetup">Technical setup</a></li>
                                <li class="routingstats">"><a href="/routingstats">Routing stats</a></li>
                                <li class="abuse">"><a href="/abuse">Abuse</a></li>
                                <li class="volunteering">"><a href="/volunteering">Volunteering</a></li>
                            </ul>
                        </li>
                        <li class=""><a href="https://user.k-net.dk/"><i class="icon-user icon-white"></i>User settings</a></li>
                        <li class="support"><a href="/support">Support</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="container content">

@yield ('content')


<hr/>

<footer class="footer">
<p>&copy; {{ date('Y') }} K-Net Association, CVR: 30772652</p>
</footer>

</div>

</body>
</html>
